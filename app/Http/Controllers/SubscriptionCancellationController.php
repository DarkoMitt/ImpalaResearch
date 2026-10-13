<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\SubscriptionCancellationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class SubscriptionCancellationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subscription_id' => ['required', 'integer'],
            'type' => [
                'required',
                Rule::in(['period_end', 'early_refund']),
            ],
            'reason' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($validated['type'] === 'period_end') {
            return $this->changeRenewal(
                $request,
                (int) $validated['subscription_id'],
                true
            );
        }

        $result = DB::transaction(function () use (
            $request,
            $validated
        ) {
            $subscription = Subscription::query()
                ->with('plan')
                ->whereKey($validated['subscription_id'])
                ->where('user_id', $request->user()->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$this->isActive($subscription)) {
                return 'inactive';
            }

            if (
                !$subscription->plan ||
                (int) $subscription->plan->duration_months <= 1
            ) {
                return 'invalid_refund';
            }

            $existing = SubscriptionCancellationRequest::query()
                ->where('subscription_id', $subscription->id)
                ->where('status', 'pending')
                ->exists();

            if ($existing) {
                return 'duplicate';
            }

            SubscriptionCancellationRequest::query()->create([
                'user_id' => $request->user()->id,
                'subscription_id' => $subscription->id,
                'type' => 'early_refund',
                'status' => 'pending',
                'reason' => $validated['reason'] ?? null,
            ]);

            return 'created';
        }, 3);

        return match ($result) {
            'created' => back()->with(
                'success',
                'Your early refund request has been submitted.'
            ),
            'duplicate' => back()->with(
                'error',
                'A refund request is already pending.'
            ),
            'invalid_refund' => back()->with(
                'error',
                'Early refunds are only available for multi-month plans.'
            ),
            default => back()->with(
                'error',
                'This subscription is not eligible.'
            ),
        };
    }

    public function resume(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subscription_id' => ['required', 'integer'],
        ]);

        return $this->changeRenewal(
            $request,
            (int) $validated['subscription_id'],
            false
        );
    }

    private function changeRenewal(
        Request $request,
        int $subscriptionId,
        bool $cancel
    ): RedirectResponse {
        $subscription = Subscription::query()
            ->whereKey($subscriptionId)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        if (!$this->isActive($subscription)) {
            return back()->with(
                'error',
                'This subscription is no longer active.'
            );
        }

        if ($subscription->is_demo) {
            if (
                !app()->environment('local', 'testing') ||
                $subscription->stripe_subscription_id !== null
            ) {
                return back()->with(
                    'error',
                    'Invalid demo subscription configuration.'
                );
            }

            return $this->changeDemoRenewal(
                $subscription,
                $cancel
            );
        }

        if (!$subscription->stripe_subscription_id) {
            return back()->with(
                'error',
                'This subscription has no verified Stripe subscription ID.'
            );
        }

        return $this->changeStripeRenewal(
            $subscription,
            $cancel
        );
    }

    private function changeDemoRenewal(
        Subscription $subscription,
        bool $cancel
    ): RedirectResponse {
        $result = DB::transaction(function () use (
            $subscription,
            $cancel
        ) {
            $locked = Subscription::query()
                ->whereKey($subscription->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                !$this->isActive($locked) ||
                !$locked->is_demo ||
                $locked->stripe_subscription_id !== null
            ) {
                return 'invalid';
            }

            if ($cancel && $locked->cancel_at !== null) {
                return 'already_cancelled';
            }

            if (!$cancel && $locked->cancel_at === null) {
                return 'already_active';
            }

            $locked->update([
                'cancel_at' => $cancel
                    ? $locked->ends_at
                    : null,
            ]);

            return 'updated';
        }, 3);

        if ($result === 'invalid') {
            return back()->with(
                'error',
                'The demo subscription cannot be updated.'
            );
        }

        if ($result === 'already_cancelled') {
            return back()->with(
                'success',
                'Cancellation is already scheduled.'
            );
        }

        if ($result === 'already_active') {
            return back()->with(
                'success',
                'Automatic renewal is already enabled.'
            );
        }

        return back()->with(
            'success',
            $cancel
                ? 'Demo subscription cancellation scheduled.'
                : 'Demo subscription cancellation removed.'
        );
    }

    private function changeStripeRenewal(
        Subscription $subscription,
        bool $cancel
    ): RedirectResponse {
        $secret = (string) config('services.stripe.secret');

        // Only test-mode Stripe operations are enabled in this phase.
        if (!str_starts_with($secret, 'sk_test_')) {
            return back()->with(
                'error',
                'Stripe test mode is not configured.'
            );
        }

        try {
            $stripe = new StripeClient($secret);

            $remote = $stripe->subscriptions->update(
                $subscription->stripe_subscription_id,
                [
                    'cancel_at_period_end' => $cancel,
                ]
            );

            if (
                (bool) $remote->cancel_at_period_end !== $cancel ||
                $remote->status === 'canceled'
            ) {
                return back()->with(
                    'error',
                    'Stripe did not confirm the change.'
                );
            }

            // Stripe is authoritative. Do not overwrite the paid period.
            $subscription->refresh();

            if (!$this->isActive($subscription)) {
                return back()->with(
                    'error',
                    'Stripe was updated, but local synchronization requires review.'
                );
            }

            $subscription->update([
                'cancel_at' => $cancel
                    ? $subscription->ends_at
                    : null,
            ]);

            return back()->with(
                'success',
                $cancel
                    ? 'Subscription cancellation scheduled.'
                    : 'Automatic renewal restored.'
            );
        } catch (ApiErrorException $exception) {
            report($exception);

            return back()->with(
                'error',
                'Stripe could not update your subscription.'
            );
        }
    }

    private function isActive(Subscription $subscription): bool
    {
        return $subscription->status === 'active'
            && $subscription->ends_at !== null
            && $subscription->ends_at->isFuture();
    }
}
