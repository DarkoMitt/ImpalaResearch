<?php

namespace App\Http\Controllers;

use App\Models\StripeWebhookEvent;
use App\Models\Subscription;
use App\Services\StripeSubscriptionGateway;
use App\Services\StripeSubscriptionIdentity;
use App\Services\SubscriptionService;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use Throwable;
use UnexpectedValueException;

class StripeWebhookController extends Controller
{
    public function handle(
        Request $request,
        SubscriptionService $subscriptions,
        StripeSubscriptionGateway $gateway,
        StripeSubscriptionIdentity $identity
    ): JsonResponse {
        $webhookSecret = config('services.stripe.webhook_secret');

        if (!is_string($webhookSecret) || $webhookSecret === '') {
            return response()->json([
                'message' => 'Webhook is not configured.',
            ], 503);
        }

        try {
            $event = Webhook::constructEvent(
                $request->getContent(),
                $request->header('Stripe-Signature', ''),
                $webhookSecret
            );
        } catch (
            UnexpectedValueException |
            SignatureVerificationException $exception
        ) {
            return response()->json([
                'message' => 'Invalid Stripe webhook.',
            ], 400);
        }

        $type = (string) $event->type;

        $supported = [
            'invoice.paid',
            'invoice.payment_failed',
            'checkout.session.completed',
            'customer.subscription.updated',
            'customer.subscription.deleted',
        ];

        try {
            $record = StripeWebhookEvent::query()->firstOrCreate(
                ['stripe_event_id' => $event->id],
                [
                    'event_type' => $type,
                    'stripe_object_id' =>
                        $event->data->object->id ?? null,
                    'status' => in_array($type, $supported, true)
                        ? 'pending_processing'
                        : 'ignored',
                    'payload' => $event->toArray(),
                    'processed_at' => in_array($type, $supported, true)
                        ? null
                        : now(),
                ]
            );

            if (in_array(
                $record->status,
                ['processed', 'ignored'],
                true
            )) {
                return response()->json(['received' => true]);
            }

            DB::transaction(function () use (
                $record,
                $event,
                $type,
                $subscriptions,
                $gateway,
                $identity
            ) {
                $lockedEvent = StripeWebhookEvent::query()
                    ->whereKey($record->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (in_array(
                    $lockedEvent->status,
                    ['processed', 'ignored'],
                    true
                )) {
                    return;
                }

                $objectId = $event->data->object->id ?? null;

                if (
                    in_array($type, [
                        'invoice.paid',
                        'customer.subscription.updated',
                        'customer.subscription.deleted',
                    ], true) &&
                    (!is_string($objectId) || $objectId === '')
                ) {
                    throw new RuntimeException(
                        'Stripe event object ID is missing.'
                    );
                }

                if ($type === 'invoice.paid') {
                    $this->processPaidInvoice(
                        $objectId,
                        $subscriptions,
                        $gateway
                    );
                }

                if (in_array($type, [
                    'customer.subscription.updated',
                    'customer.subscription.deleted',
                ], true)) {
                    $this->synchronizeSubscription(
                        $objectId,
                        $gateway,
                        $identity
                    );
                }

                // Checkout completion and payment failure
                // never grant a paid period or respondents.
                $lockedEvent->update([
                    'status' => 'processed',
                    'processed_at' => now(),
                    'error_message' => null,
                ]);
            }, 1);

            return response()->json(['received' => true]);
        } catch (Throwable $exception) {
            report($exception);

            if (isset($record)) {
                try {
                    StripeWebhookEvent::query()
                        ->whereKey($record->id)
                        ->where('status', 'pending_processing')
                        ->update([
                            'error_message' => substr(
                                $exception->getMessage(),
                                0,
                                5000
                            ),
                        ]);
                } catch (Throwable $loggingException) {
                    report($loggingException);
                }
            }

            Log::error('Stripe webhook processing failed.', [
                'event_id' => $event->id,
                'type' => $type,
            ]);

            return response()->json([
                'message' => 'Webhook processing failed.',
            ], 500);
        }
    }

    private function synchronizeSubscription(
        string $stripeSubscriptionId,
        StripeSubscriptionGateway $gateway,
        StripeSubscriptionIdentity $identity
    ): void {
        $subscription = Subscription::query()
            ->where(
                'stripe_subscription_id',
                $stripeSubscriptionId
            )
            ->lockForUpdate()
            ->first();

        if (!$subscription) {
            // Activation is exclusively handled by invoice.paid.
            return;
        }

        if ($subscription->is_demo) {
            throw new RuntimeException(
                'Demo subscription has a Stripe ID.'
            );
        }

        $remote = $gateway->retrieve($stripeSubscriptionId);

        $identity->assertMatches($subscription, $remote);

        $status = (string) ($remote->status ?? '');

        if ($status === 'canceled') {
            $this->markCancelled($subscription);
            return;
        }

        // Never reactivate a locally cancelled subscription
        // from an updated/deleted webhook.
        if ($subscription->status === 'cancelled') {
            return;
        }

        if ($status !== 'active') {
            // Do not grant or extend a billing period.
            // Other payment states need separate billing policy.
            return;
        }

        if (
            $subscription->status !== 'active' ||
            !$subscription->ends_at ||
            !$subscription->ends_at->isFuture()
        ) {
            return;
        }

        $subscription->update([
            'cancel_at' =>
                (bool) ($remote->cancel_at_period_end ?? false)
                    ? $subscription->ends_at
                    : null,
        ]);
    }

    private function markCancelled(
        Subscription $subscription
    ): void {
        if ($subscription->status === 'cancelled') {
            return;
        }

        $now = now();

        $subscription->update([
            'status' => 'cancelled',
            'cancelled_at' => $now,
            'cancel_at' => null,
            'ends_at' => $subscription->ends_at
                ? min($subscription->ends_at, $now)
                : $now,
        ]);
    }

    private function processPaidInvoice(
        string $invoiceId,
        SubscriptionService $subscriptions,
        StripeSubscriptionGateway $gateway
    ): void {
        $invoice = $gateway->retrieveInvoice($invoiceId);

        if (
            $invoice->status !== 'paid' ||
            $invoice->paid !== true
        ) {
            throw new RuntimeException(
                'Invoice is not confirmed as paid.'
            );
        }

        $stripeSubscriptionId =
            $invoice->parent->subscription_details->subscription
            ?? null;

        if (
            !is_string($stripeSubscriptionId) ||
            $stripeSubscriptionId === ''
        ) {
            throw new RuntimeException(
                'Paid invoice has no Stripe subscription reference.'
            );
        }

        $remote = $gateway->retrieve($stripeSubscriptionId);

        $userId = (int) ($remote->metadata->user_id ?? 0);
        $planId = (int) ($remote->metadata->plan_id ?? 0);

        if ($userId <= 0 || $planId <= 0) {
            throw new RuntimeException(
                'Stripe subscription metadata is incomplete.'
            );
        }

        $invoiceCustomer = $invoice->customer ?? null;

        if (is_object($invoiceCustomer)) {
            $invoiceCustomer = $invoiceCustomer->id ?? null;
        }

        $remoteCustomer = $remote->customer ?? null;

        if (is_object($remoteCustomer)) {
            $remoteCustomer = $remoteCustomer->id ?? null;
        }

        if (
            !is_string($invoiceCustomer) ||
            $invoiceCustomer === '' ||
            $invoiceCustomer !== $remoteCustomer
        ) {
            throw new RuntimeException(
                'Invoice customer does not match subscription.'
            );
        }

        $existing = Subscription::query()
            ->where(
                'stripe_subscription_id',
                $stripeSubscriptionId
            )
            ->lockForUpdate()
            ->first();

        if ($existing) {
            if ($existing->status === 'cancelled') {
                throw new RuntimeException(
                    'Cancelled subscription cannot be renewed.'
                );
            }

            if (
                $existing->is_demo ||
                (int) $existing->user_id !== $userId ||
                (int) $existing->subscription_plan_id !== $planId ||
                $existing->stripe_customer_id !== $invoiceCustomer
            ) {
                throw new RuntimeException(
                    'Existing subscription identity mismatch.'
                );
            }
        }

        $period = null;

        foreach (($invoice->lines->data ?? []) as $line) {
            $lineSubscriptionId =
                $line->parent->subscription_item_details->subscription
                ?? null;

            if ($lineSubscriptionId === $stripeSubscriptionId) {
                $period = $line->period ?? null;
                break;
            }
        }

        if (
            !$period ||
            !isset($period->start, $period->end)
        ) {
            throw new RuntimeException(
                'Invoice billing period is missing.'
            );
        }

        $subscriptions->activatePaidPeriod(
            invoiceId: $invoiceId,
            stripeSubscriptionId: $stripeSubscriptionId,
            userId: $userId,
            planId: $planId,
            amountPaidCents: (int) $invoice->amount_paid,
            periodStart: CarbonImmutable::createFromTimestamp(
                $period->start,
                'UTC'
            ),
            periodEnd: CarbonImmutable::createFromTimestamp(
                $period->end,
                'UTC'
            ),
            stripeCustomerId: $invoiceCustomer
        );
    }
}
