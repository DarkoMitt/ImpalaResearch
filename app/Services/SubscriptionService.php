<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\StripePaidInvoice;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SubscriptionService
{
    public function activatePaidPeriod(
        string $invoiceId,
        string $stripeSubscriptionId,
        int $userId,
        int $planId,
        int $amountPaidCents,
        CarbonImmutable $periodStart,
        CarbonImmutable $periodEnd,
        ?string $stripeCustomerId = null
    ): Subscription {
        if (
            $invoiceId === '' ||
            $stripeSubscriptionId === '' ||
            $userId <= 0 ||
            $planId <= 0 ||
            $amountPaidCents <= 0 ||
            $periodEnd->lessThanOrEqualTo($periodStart) ||
            $periodStart->greaterThan(CarbonImmutable::now()->addMinutes(10))
        ) {
            throw new RuntimeException(
                'Invalid paid subscription invoice or period.'
            );
        }

        return DB::transaction(function () use (
            $invoiceId,
            $stripeSubscriptionId,
            $userId,
            $planId,
            $amountPaidCents,
            $periodStart,
            $periodEnd,
            $stripeCustomerId
        ) {
            $user = User::query()
                ->whereKey($userId)
                ->lockForUpdate()
                ->firstOrFail();

            $plan = SubscriptionPlan::query()
                ->findOrFail($planId);

            if (
                (int) $plan->price_cents <= 0 ||
                !in_array((int) $plan->duration_months, [1, 6, 12], true)
            ) {
                throw new RuntimeException(
                    'Subscription plan has an invalid billing configuration.'
                );
            }

            $existingInvoice = StripePaidInvoice::query()
                ->where('stripe_invoice_id', $invoiceId)
                ->first();

            if ($existingInvoice) {
                if (
                    $existingInvoice->stripe_subscription_id
                    !== $stripeSubscriptionId
                ) {
                    throw new RuntimeException(
                        'Invoice belongs to another subscription.'
                    );
                }

                $existingSubscription = Subscription::query()
                    ->findOrFail($existingInvoice->subscription_id);

                if (
                    (int) $existingSubscription->user_id !== $userId ||
                    (int) $existingInvoice->amount_paid_cents
                    !== $amountPaidCents ||
                    !$existingInvoice->period_start->equalTo($periodStart) ||
                    !$existingInvoice->period_end->equalTo($periodEnd)
                ) {
                    throw new RuntimeException(
                        'Invoice data does not match the original payment.'
                    );
                }

                return $existingSubscription;
            }

            $subscription = Subscription::query()
                ->where(
                    'stripe_subscription_id',
                    $stripeSubscriptionId
                )
                ->lockForUpdate()
                ->first();

            if (
                $subscription &&
                (int) $subscription->user_id !== $userId
            ) {
                throw new RuntimeException(
                    'Stripe subscription belongs to another user.'
                );
            }

            if ($subscription && $subscription->is_demo) {
                throw new RuntimeException(
                    'A demo subscription cannot be converted into a paid Stripe subscription.'
                );
            }

            if (
                $subscription &&
                $subscription->status === 'cancelled'
            ) {
                throw new RuntimeException(
                    'A cancelled Stripe subscription cannot be reactivated by an invoice.'
                );
            }

            if ($subscription && $subscription->starts_at) {
                if (!$subscription->ends_at) {
                    throw new RuntimeException(
                        'Existing billing period has no end date.'
                    );
                }

                $currentEnd = CarbonImmutable::instance(
                    $subscription->ends_at
                );

                if ($periodStart->lessThan($currentEnd)) {
                    throw new RuntimeException(
                        'Invoice overlaps an existing paid period.'
                    );
                }

                if (
                    (int) $subscription->subscription_plan_id
                    !== $planId
                ) {
                    throw new RuntimeException(
                        'Plan changes require a separate billing workflow.'
                    );
                }
            }

            $anotherActiveSubscription = Subscription::query()
                ->where('user_id', $user->id)
                ->where('status', 'active')
                ->where('ends_at', '>', now())
                ->when(
                    $subscription,
                    fn ($query) => $query->where(
                        'id',
                        '!=',
                        $subscription->id
                    )
                )
                ->exists();

            if ($anotherActiveSubscription) {
                throw new RuntimeException(
                    'User already has another active subscription.'
                );
            }

            $values = [
                'user_id' => $user->id,
                'subscription_plan_id' => $plan->id,
                'status' => 'active',
                'is_demo' => false,
                'stripe_customer_id' => $stripeCustomerId
                    ?? $subscription?->stripe_customer_id,
                'stripe_subscription_id' => $stripeSubscriptionId,
                'price_paid_cents' => $amountPaidCents,
                'starts_at' => $periodStart,
                'ends_at' => $periodEnd,
                'cancel_at' => null,
                'cancelled_at' => null,
                'respondents_granted' =>
                    (int) $plan->initial_respondents,
                'respondents_used' => 0,
                'months_granted' => 1,
                'next_allowance_at' =>
                    (int) $plan->duration_months > 1
                        ? $periodStart->addMonthNoOverflow()
                        : null,
            ];

            if ($subscription) {
                $subscription->update($values);
            } else {
                $subscription = Subscription::query()->create($values);
            }

            StripePaidInvoice::query()->create([
                'stripe_invoice_id' => $invoiceId,
                'stripe_subscription_id' => $stripeSubscriptionId,
                'subscription_id' => $subscription->id,
                'amount_paid_cents' => $amountPaidCents,
                'period_start' => $periodStart,
                'period_end' => $periodEnd,
            ]);

            return $subscription->refresh();
        }, 3);
    }

    public function grantDueMonthlyAllowances(
        Subscription $subscription
    ): int {
        return DB::transaction(function () use ($subscription) {
            $locked = Subscription::query()
                ->with('plan')
                ->whereKey($subscription->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                $locked->status !== 'active' ||
                !$locked->starts_at ||
                !$locked->ends_at ||
                $locked->ends_at->lessThanOrEqualTo(now())
            ) {
                return 0;
            }

            if (
                !$locked->is_demo &&
                !$locked->stripe_subscription_id
            ) {
                return 0;
            }

            if (
                $locked->is_demo &&
                !app()->environment('local', 'testing')
            ) {
                return 0;
            }

            $plan = $locked->plan;

            if (
                !$plan ||
                (int) $plan->duration_months <= 1
            ) {
                return 0;
            }

            $now = CarbonImmutable::now();
            $start = CarbonImmutable::instance(
                $locked->starts_at
            );
            $end = CarbonImmutable::instance(
                $locked->ends_at
            );

            $monthsGranted = (int) $locked->months_granted;
            $newlyGranted = 0;

            while (
                $monthsGranted < (int) $plan->duration_months
            ) {
                $nextDate = $start->addMonthsNoOverflow(
                    $monthsGranted
                );

                if (
                    $nextDate->greaterThan($now) ||
                    $nextDate->greaterThanOrEqualTo($end)
                ) {
                    break;
                }

                $locked->respondents_granted +=
                    (int) $plan->monthly_respondents;

                $monthsGranted++;
                $newlyGranted++;
            }

            $locked->months_granted = $monthsGranted;

            $locked->next_allowance_at =
                $monthsGranted < (int) $plan->duration_months
                    ? $start->addMonthsNoOverflow(
                        $monthsGranted
                    )
                    : null;

            $locked->save();

            return $newlyGranted;
        }, 3);
    }
}
