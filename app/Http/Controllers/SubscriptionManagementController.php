<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class SubscriptionManagementController extends Controller
{
    public function cancel(Request $request): RedirectResponse
    {
        return $this->changeRenewal($request, true);
    }

    public function resume(Request $request): RedirectResponse
    {
        return $this->changeRenewal($request, false);
    }

    private function changeRenewal(
        Request $request,
        bool $cancel
    ): RedirectResponse {
        $validated = $request->validate([
            'subscription_id' => ['required', 'integer'],
        ]);

        $subscription = Subscription::query()
            ->whereKey($validated['subscription_id'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        if (
            $subscription->status !== 'active' ||
            !$subscription->ends_at ||
            $subscription->ends_at->lessThanOrEqualTo(now())
        ) {
            return back()->with(
                'error',
                'This subscription is no longer active.'
            );
        }

        $isScheduled = $subscription->cancel_at !== null;

        if ($cancel && $isScheduled) {
            return back()->with(
                'success',
                'Your subscription is already scheduled to cancel.'
            );
        }

        if (!$cancel && !$isScheduled) {
            return back()->with(
                'success',
                'Automatic renewal is already enabled.'
            );
        }

        if (empty($subscription->stripe_subscription_id)) {
            // Demo subscriptions are local-only.
            // They never represent a Stripe payment.
            DB::transaction(function () use ($subscription, $cancel) {
                $locked = Subscription::query()
                    ->whereKey($subscription->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (
                    $locked->status !== 'active' ||
                    !$locked->ends_at ||
                    $locked->ends_at->lessThanOrEqualTo(now())
                ) {
                    abort(409, 'Subscription is no longer active.');
                }

                $locked->cancel_at = $cancel
                    ? $locked->ends_at
                    : null;

                $locked->save();
            });

            return back()->with(
                'success',
                $cancel
                    ? 'Your demo subscription will end at the end of its current period.'
                    : 'Automatic renewal has been restored for your demo subscription.'
            );
        }

        $secret = (string) config('services.stripe.secret');

        if (!str_starts_with($secret, 'sk_test_')) {
            return back()->with(
                'error',
                'Stripe subscription management is not configured for testing.'
            );
        }

        try {
            $stripe = new StripeClient($secret);

            $remote = $stripe->subscriptions->update(
                $subscription->stripe_subscription_id,
                ['cancel_at_period_end' => $cancel]
            );

            if (
                $remote->status === 'canceled' ||
                (bool) $remote->cancel_at_period_end !== $cancel
            ) {
                return back()->with(
                    'error',
                    'Stripe did not confirm the requested change.'
                );
            }

            $subscription->refresh();

            // Synchronize only after Stripe confirms the change.
            $subscription->update([
                'cancel_at' => $cancel
                    ? $subscription->ends_at
                    : null,
            ]);

            return back()->with(
                'success',
                $cancel
                    ? 'Your subscription will not renew after the current period.'
                    : 'Automatic renewal has been restored.'
            );
        } catch (ApiErrorException $exception) {
            report($exception);

            return back()->with(
                'error',
                'Stripe could not update your subscription. Please try again.'
            );
        }
    }
}
