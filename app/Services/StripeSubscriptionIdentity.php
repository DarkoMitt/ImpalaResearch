<?php

namespace App\Services;

use App\Models\Subscription;
use RuntimeException;

class StripeSubscriptionIdentity
{
    public function assertMatches(
        Subscription $subscription,
        object $remote
    ): void {
        if (
            $subscription->is_demo ||
            !$subscription->stripe_subscription_id ||
            ($remote->id ?? null)
                !== $subscription->stripe_subscription_id
        ) {
            throw new RuntimeException(
                'Stripe subscription identity mismatch.'
            );
        }

        if (
            (string) ($remote->metadata->user_id ?? '')
                !== (string) $subscription->user_id ||
            (string) ($remote->metadata->plan_id ?? '')
                !== (string) $subscription->subscription_plan_id
        ) {
            throw new RuntimeException(
                'Stripe subscription ownership mismatch.'
            );
        }

        $customer = $remote->customer ?? null;

        if (is_object($customer)) {
            $customer = $customer->id ?? null;
        }

        if (
            !is_string($customer) ||
            $customer === '' ||
            !is_string($subscription->stripe_customer_id) ||
            $subscription->stripe_customer_id === '' ||
            $customer !== $subscription->stripe_customer_id
        ) {
            throw new RuntimeException(
                'Stripe customer mismatch.'
            );
        }
    }
}
