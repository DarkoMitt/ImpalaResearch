<?php

namespace Tests\Feature;

use App\Models\StripeWebhookEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StripeWebhookSecurityTest extends TestCase
{
    use RefreshDatabase;

    private string $webhookSecret = 'whsec_local_testing_only';

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.stripe.webhook_secret' =>
                $this->webhookSecret,
        ]);
    }

    private function payload(
        string $eventId,
        string $eventType = 'checkout.session.completed'
    ): string {
        return json_encode([
            'id' => $eventId,
            'object' => 'event',
            'type' => $eventType,
            'data' => [
                'object' => [
                    'id' => 'cs_test_example',
                    'object' => 'checkout.session',
                ],
            ],
        ], JSON_THROW_ON_ERROR);
    }

    private function signature(
        string $payload,
        string $secret
    ): string {
        $timestamp = time();

        $signedPayload = $timestamp . '.' . $payload;

        $signature = hash_hmac(
            'sha256',
            $signedPayload,
            $secret
        );

        return "t={$timestamp},v1={$signature}";
    }

    private function sendWebhook(
        string $payload,
        ?string $signature = null
    ) {
        return $this->call(
            'POST',
            '/stripe/webhook',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_Stripe-Signature' => $signature
                    ?? $this->signature(
                        $payload,
                        $this->webhookSecret
                    ),
            ],
            $payload
        );
    }

    public function test_valid_signature_is_accepted(): void
    {
        $response = $this->sendWebhook(
            $this->payload('evt_valid_001')
        );

        $response->assertOk();

        $this->assertDatabaseHas(
            'stripe_webhook_events',
            [
                'stripe_event_id' => 'evt_valid_001',
                'status' => 'processed',
            ]
        );
    }

    public function test_invalid_signature_is_rejected(): void
    {
        $payload = $this->payload('evt_invalid_001');

        $response = $this->sendWebhook(
            $payload,
            $this->signature(
                $payload,
                'whsec_wrong_secret'
            )
        );

        $response->assertStatus(400);

        $this->assertDatabaseMissing(
            'stripe_webhook_events',
            [
                'stripe_event_id' => 'evt_invalid_001',
            ]
        );
    }

    public function test_duplicate_event_is_not_inserted_twice(): void
    {
        $payload = $this->payload('evt_duplicate_001');

        $this->sendWebhook($payload)->assertOk();
        $this->sendWebhook($payload)->assertOk();

        $this->assertSame(
            1,
            StripeWebhookEvent::query()
                ->where(
                    'stripe_event_id',
                    'evt_duplicate_001'
                )
                ->count()
        );
    }

    public function test_unsupported_event_is_ignored(): void
    {
        $response = $this->sendWebhook(
            $this->payload(
                'evt_ignored_001',
                'customer.created'
            )
        );

        $response->assertOk();

        $this->assertDatabaseHas(
            'stripe_webhook_events',
            [
                'stripe_event_id' => 'evt_ignored_001',
                'status' => 'ignored',
            ]
        );
    }

    public function test_missing_webhook_secret_rejects_request(): void
    {
        config([
            'services.stripe.webhook_secret' => '',
        ]);

        $response = $this->sendWebhook(
            $this->payload('evt_no_secret_001')
        );

        $response->assertStatus(503);

        $this->assertDatabaseCount(
            'stripe_webhook_events',
            0
        );
    }
}
