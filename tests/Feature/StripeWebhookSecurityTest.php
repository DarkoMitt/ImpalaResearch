<?php

namespace Tests\Feature;

use App\Http\Controllers\StripeWebhookController;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Services\StripeSubscriptionGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Mockery;
use RuntimeException;
use Tests\TestCase;

class StripeWebhookSecurityTest extends TestCase
{
    use RefreshDatabase;

    private string $webhookSecret = 'whsec_test_security_suite';

    protected function setUp(): void
    {
        parent::setUp();

        config()->set(
            'services.stripe.webhook_secret',
            $this->webhookSecret
        );
    }

    private function webhookPath(): string
    {
        foreach (Route::getRoutes() as $route) {
            if (
                str_contains(
                    $route->getActionName(),
                    StripeWebhookController::class
                ) &&
                in_array('POST', $route->methods(), true)
            ) {
                return '/' . ltrim($route->uri(), '/');
            }
        }

        throw new RuntimeException(
            'Stripe webhook POST route was not found.'
        );
    }

    private function sendWebhook(
        string $eventId,
        string $eventType,
        string $objectId,
        bool $validSignature = true
    ) {
        $payload = json_encode([
            'id' => $eventId,
            'object' => 'event',
            'type' => $eventType,
            'created' => time(),
            'livemode' => false,
            'data' => [
                'object' => [
                    'id' => $objectId,
                    'object' => 'subscription',
                ],
            ],
        ], JSON_THROW_ON_ERROR);

        $timestamp = time();

        $signature = hash_hmac(
            'sha256',
            $timestamp . '.' . $payload,
            $validSignature
                ? $this->webhookSecret
                : 'incorrect_webhook_secret'
        );

        return $this->call(
            'POST',
            $this->webhookPath(),
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_STRIPE_SIGNATURE' =>
                    't=' . $timestamp . ',v1=' . $signature,
            ],
            $payload
        );
    }

    private function createSubscription(): Subscription
    {
        $user = User::factory()->create();

        $plan = SubscriptionPlan::query()->create([
            'slug' => 'webhook-test-plan',
            'name' => 'Webhook Test Plan',
            'price_cents' => 39000,
            'currency' => 'USD',
            'duration_months' => 1,
            'initial_respondents' => 500,
            'monthly_respondents' => 0,
            'is_active' => true,
        ]);

        return Subscription::query()->create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
            'is_demo' => false,
            'stripe_customer_id' => 'cus_webhook_test',
            'stripe_subscription_id' => 'sub_webhook_test',
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addMonth(),
            'respondents_granted' => 500,
            'respondents_used' => 125,
            'months_granted' => 1,
        ]);
    }

    private function mockCancelledStripeSubscription(
        Subscription $subscription,
        int $times = 1
    ): void {
        $remote = (object) [
            'id' => $subscription->stripe_subscription_id,
            'status' => 'canceled',
            'customer' => $subscription->stripe_customer_id,
            'cancel_at_period_end' => false,
            'metadata' => (object) [
                'user_id' => (string) $subscription->user_id,
                'plan_id' =>
                    (string) $subscription->subscription_plan_id,
            ],
        ];

        $gateway = Mockery::mock(
            StripeSubscriptionGateway::class
        );

        $gateway->shouldReceive('retrieve')
            ->times($times)
            ->with($subscription->stripe_subscription_id)
            ->andReturn($remote);

        $this->app->instance(
            StripeSubscriptionGateway::class,
            $gateway
        );
    }

    public function test_invalid_signature_is_rejected(): void
    {
        $this->sendWebhook(
            'evt_invalid_signature',
            'checkout.session.completed',
            'cs_test',
            false
        )->assertStatus(400);

        $this->assertDatabaseCount('stripe_webhook_events', 0);
    }

    public function test_missing_webhook_secret_is_rejected(): void
    {
        config()->set('services.stripe.webhook_secret', '');

        $this->sendWebhook(
            'evt_missing_secret',
            'checkout.session.completed',
            'cs_test'
        )->assertStatus(503);

        $this->assertDatabaseCount('stripe_webhook_events', 0);
    }

    public function test_checkout_completed_does_not_grant_access(): void
    {
        $this->sendWebhook(
            'evt_checkout_completed',
            'checkout.session.completed',
            'cs_test_checkout'
        )->assertOk();

        $this->assertDatabaseCount('subscriptions', 0);

        $this->assertDatabaseHas('stripe_webhook_events', [
            'stripe_event_id' => 'evt_checkout_completed',
            'status' => 'processed',
        ]);
    }

    public function test_payment_failed_does_not_grant_access(): void
    {
        $this->sendWebhook(
            'evt_payment_failed',
            'invoice.payment_failed',
            'in_test_failed'
        )->assertOk();

        $this->assertDatabaseCount('subscriptions', 0);
    }

    public function test_duplicate_event_is_processed_once(): void
    {
        $this->sendWebhook(
            'evt_duplicate',
            'checkout.session.completed',
            'cs_test_duplicate'
        )->assertOk();

        $this->sendWebhook(
            'evt_duplicate',
            'checkout.session.completed',
            'cs_test_duplicate'
        )->assertOk();

        $this->assertDatabaseCount('stripe_webhook_events', 1);

        $this->assertDatabaseHas('stripe_webhook_events', [
            'stripe_event_id' => 'evt_duplicate',
            'status' => 'processed',
        ]);
    }

    public function test_unknown_event_is_ignored(): void
    {
        $this->sendWebhook(
            'evt_unknown',
            'some.unknown.event',
            'obj_unknown'
        )->assertOk();

        $this->assertDatabaseHas('stripe_webhook_events', [
            'stripe_event_id' => 'evt_unknown',
            'status' => 'ignored',
        ]);
    }

    public function test_deleted_subscription_revokes_access(): void
    {
        $subscription = $this->createSubscription();

        $this->mockCancelledStripeSubscription($subscription);

        $this->sendWebhook(
            'evt_deleted',
            'customer.subscription.deleted',
            'sub_webhook_test'
        )->assertOk();

        $subscription->refresh();

        $this->assertSame('cancelled', $subscription->status);
        $this->assertNotNull($subscription->cancelled_at);

        $this->assertTrue(
            $subscription->ends_at->lessThanOrEqualTo(now())
        );

        $this->assertSame(500, $subscription->respondents_granted);
        $this->assertSame(125, $subscription->respondents_used);
    }

    public function test_repeated_deletion_does_not_grant_access(): void
    {
        $subscription = $this->createSubscription();

        $this->mockCancelledStripeSubscription(
            $subscription,
            2
        );

        $this->sendWebhook(
            'evt_deleted_first',
            'customer.subscription.deleted',
            'sub_webhook_test'
        )->assertOk();

        $this->sendWebhook(
            'evt_deleted_second',
            'customer.subscription.deleted',
            'sub_webhook_test'
        )->assertOk();

        $subscription->refresh();

        $this->assertSame('cancelled', $subscription->status);
        $this->assertSame(500, $subscription->respondents_granted);
        $this->assertSame(125, $subscription->respondents_used);
    }

    public function test_deletion_of_unknown_subscription_is_safe(): void
    {
        $this->sendWebhook(
            'evt_unknown_deleted',
            'customer.subscription.deleted',
            'sub_does_not_exist'
        )->assertOk();

        $this->assertDatabaseCount('subscriptions', 0);
    }

    public function test_updated_event_without_local_subscription_grants_nothing(): void
    {
        $this->sendWebhook(
            'evt_unknown_updated',
            'customer.subscription.updated',
            'sub_does_not_exist'
        )->assertOk();

        $this->assertDatabaseCount('subscriptions', 0);

        $this->assertDatabaseHas('stripe_webhook_events', [
            'stripe_event_id' => 'evt_unknown_updated',
            'status' => 'processed',
        ]);
    }
}
