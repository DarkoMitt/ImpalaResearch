<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\SubscriptionCancellationRequest;
use App\Services\StripeSubscriptionGateway;
use App\Services\StripeSubscriptionIdentity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Throwable;

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
            (int) $request->user()->id,
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

        return match ($result) {
            'updated' => back()->with(
                'success',
                $cancel
                    ? 'Demo subscription cancellation scheduled.'
                    : 'Demo subscription cancellation removed.'
            ),
            'already_cancelled' => back()->with(
                'success',
                'Cancellation is already scheduled.'
            ),
            'already_active' => back()->with(
                'success',
                'Automatic renewal is already enabled.'
            ),
            default => back()->with(
                'error',
                'The demo subscription cannot be updated.'
            ),
        };
    }

    private function changeStripeRenewal(
        Subscription $subscription,
        int $userId,
        bool $cancel
    ): RedirectResponse {
        try {
            $result = DB::transaction(function () use (
                $subscription,
                $userId,
                $cancel
            ) {
                $locked = Subscription::query()
                    ->whereKey($subscription->id)
                    ->where('user_id', $userId)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (
                    !$this->isActive($locked) ||
                    $locked->is_demo ||
                    !$locked->stripe_subscription_id
                ) {
                    return 'invalid';
                }

                $gateway = app(
                    StripeSubscriptionGateway::class
                );

                $identity = app(
                    StripeSubscriptionIdentity::class
                );

                $remote = $gateway->retrieve(
                    $locked->stripe_subscription_id
                );

                $identity->assertMatches(
                    $locked,
                    $remote
                );

                if (($remote->status ?? null) !== 'active') {
                    return 'remote_inactive';
                }

                $remote = $gateway->changeAutoRenewal(
                    $locked->stripe_subscription_id,
                    $cancel
                );

                $identity->assertMatches(
                    $locked,
                    $remote
                );

                if (
                    ($remote->status ?? null) !== 'active' ||
                    (bool) ($remote->cancel_at_period_end ?? false)
                        !== $cancel
                ) {
                    return 'not_confirmed';
                }

                // Never modify paid dates or respondent balances.
                $locked->update([
                    'cancel_at' => $cancel
                        ? $locked->ends_at
                        : null,
                ]);

                return 'updated';
            }, 1);

            return match ($result) {
                'updated' => back()->with(
                    'success',
                    $cancel
                        ? 'Subscription cancellation scheduled.'
                        : 'Automatic renewal restored.'
                ),
                'remote_inactive' => back()->with(
                    'error',
                    'This subscription is not active in Stripe.'
                ),
                'not_confirmed' => back()->with(
                    'error',
                    'Stripe did not confirm the change.'
                ),
                default => back()->with(
                    'error',
                    'This subscription cannot be updated.'
                ),
            };

        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Subscription update could not be confirmed. Please check its status before retrying.'
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
