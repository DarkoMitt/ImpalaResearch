<?php

namespace App\Services;

use RuntimeException;
use Stripe\StripeClient;

class StripeSubscriptionGateway
{
    private function client(): StripeClient
    {
        $secret = config('services.stripe.secret');

        if (
            !is_string($secret) ||
            !str_starts_with($secret, 'sk_test_')
        ) {
            throw new RuntimeException(
                'Stripe test mode is not configured.'
            );
        }

        return new StripeClient($secret);
    }

    public function retrieve(string $subscriptionId): object
    {
        if ($subscriptionId === '') {
            throw new RuntimeException(
                'Stripe subscription ID is missing.'
            );
        }

        return $this->client()
            ->subscriptions
            ->retrieve($subscriptionId);
    }

    public function changeAutoRenewal(
        string $subscriptionId,
        bool $cancelAtPeriodEnd
    ): object {
        if ($subscriptionId === '') {
            throw new RuntimeException(
                'Stripe subscription ID is missing.'
            );
        }

        return $this->client()
            ->subscriptions
            ->update($subscriptionId, [
                'cancel_at_period_end' => $cancelAtPeriodEnd,
            ]);
    }

    public function retrieveInvoice(string $invoiceId): object
    {
        if ($invoiceId === '') {
            throw new RuntimeException(
                'Stripe invoice ID is missing.'
            );
        }

        return $this->client()
            ->invoices
            ->retrieve($invoiceId, [
                'expand' => ['lines'],
            ]);
    }
}
