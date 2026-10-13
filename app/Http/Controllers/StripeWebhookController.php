<?php

namespace App\Http\Controllers;

use App\Models\StripeWebhookEvent;
use App\Models\Subscription;
use App\Services\SubscriptionService;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Stripe\Exception\SignatureVerificationException;
use Stripe\StripeClient;
use Stripe\Webhook;
use Throwable;
use UnexpectedValueException;

class StripeWebhookController extends Controller
{
    public function handle(
        Request $request,
        SubscriptionService $subscriptions
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

        $supported = [
            'invoice.paid',
            'invoice.payment_failed',
            'checkout.session.completed',
            'customer.subscription.updated',
            'customer.subscription.deleted',
        ];

        $type = $event->type;
        $objectId = $event->data->object->id ?? null;

        try {
            $record = StripeWebhookEvent::query()->firstOrCreate(
                ['stripe_event_id' => $event->id],
                [
                    'event_type' => $type,
                    'stripe_object_id' => $objectId,
                    'status' => in_array($type, $supported, true)
                        ? 'pending_processing'
                        : 'ignored',
                    'payload' => $event->toArray(),
                    'processed_at' => in_array($type, $supported, true)
                        ? null
                        : now(),
                ]
            );

            if ($record->status === 'processed' ||
                $record->status === 'ignored') {
                return response()->json(['received' => true]);
            }

            DB::transaction(function () use (
                $record,
                $type,
                $event,
                $subscriptions
            ) {
                $locked = StripeWebhookEvent::query()
                    ->whereKey($record->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (in_array($locked->status, ['processed', 'ignored'])) {
                    return;
                }

                if ($type === 'invoice.paid') {
                    $this->processPaidInvoice(
                        $event->data->object->id,
                        $subscriptions
                    );
                }

                if ($type === 'customer.subscription.deleted') {
                    $stripeSubscriptionId = $event->data->object->id;

                    $subscription = Subscription::query()
                        ->where(
                            'stripe_subscription_id',
                            $stripeSubscriptionId
                        )
                        ->lockForUpdate()
                        ->first();

                    if ($subscription) {
                        $subscription->update([
                            'status' => 'cancelled',
                            'cancelled_at' => now(),
                            'ends_at' => $subscription->ends_at
                                ? min($subscription->ends_at, now())
                                : now(),
                        ]);
                    }
                }

                // Other events are recorded for now.
                // Renewal activation is controlled by invoice.paid.
                $locked->update([
                    'status' => 'processed',
                    'processed_at' => now(),
                    'error_message' => null,
                ]);
            }, 3);

            return response()->json(['received' => true]);

        } catch (Throwable $exception) {
            report($exception);

            if (isset($record)) {
                $record->update([
                    'status' => 'pending_processing',
                    'error_message' => substr(
                        $exception->getMessage(),
                        0,
                        5000
                    ),
                ]);
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

    private function processPaidInvoice(
        string $invoiceId,
        SubscriptionService $subscriptions
    ): void {
        $secret = config('services.stripe.secret');

        if (!is_string($secret) || !str_starts_with($secret, 'sk_test_')) {
            throw new RuntimeException(
                'Stripe test secret key is not configured.'
            );
        }

        $stripe = new StripeClient($secret);

        $invoice = $stripe->invoices->retrieve($invoiceId, [
            'expand' => ['lines'],
        ]);

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

        if (!is_string($stripeSubscriptionId) ||
            $stripeSubscriptionId === '') {
            throw new RuntimeException(
                'Paid invoice has no Stripe subscription reference.'
            );
        }

        $stripeSubscription = $stripe->subscriptions->retrieve(
            $stripeSubscriptionId
        );

        $userId = (int) (
            $stripeSubscription->metadata->user_id ?? 0
        );

        $planId = (int) (
            $stripeSubscription->metadata->plan_id ?? 0
        );

        if ($userId <= 0 || $planId <= 0) {
            throw new RuntimeException(
                'Stripe subscription metadata is incomplete.'
            );
        }

        $period = null;

        foreach ($invoice->lines->data as $line) {
            $lineSubscriptionId =
                $line->parent->subscription_item_details->subscription
                ?? null;

            if ($lineSubscriptionId === $stripeSubscriptionId) {
                $period = $line->period;
                break;
            }
        }

        if (!$period || !$period->start || !$period->end) {
            throw new RuntimeException(
                'Invoice subscription billing period is missing.'
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
            stripeCustomerId: is_string($invoice->customer)
                ? $invoice->customer
                : ($invoice->customer->id ?? null)
        );
    }
}
