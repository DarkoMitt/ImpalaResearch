<?php

namespace Tests\Feature;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Services\StripeSubscriptionGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Mockery;
use RuntimeException;
use Tests\TestCase;

class StripeGatewaySecurityTest extends TestCase
{
    use RefreshDatabase;

    private string $secret = 'whsec_gateway_tests';

    protected function setUp(): void
    {
        parent::setUp();

        config()->set(
            'services.stripe.webhook_secret',
            $this->secret
        );
    }

    private function subscription(): Subscription
    {
        $user = User::factory()->create();

        $plan = SubscriptionPlan::query()->create([
            'slug' => 'gateway-test',
            'name' => 'Gateway Test',
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
            'stripe_customer_id' => 'cus_gateway',
            'stripe_subscription_id' => 'sub_gateway',
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addMonth(),
            'respondents_granted' => 500,
            'respondents_used' => 125,
            'months_granted' => 1,
        ]);
    }

    private function remote(
        Subscription $subscription,
        string $status = 'active',
        bool $cancel = false,
        ?string $customer = null,
        ?string $userId = null
    ): object {
        return (object) [
            'id' => $subscription->stripe_subscription_id,
            'status' => $status,
            'customer' => $customer ?? 'cus_gateway',
            'cancel_at_period_end' => $cancel,
            'metadata' => (object) [
                'user_id' => $userId
                    ?? (string) $subscription->user_id,
                'plan_id' =>
                    (string) $subscription->subscription_plan_id,
            ],
        ];
    }

    private function webhook(
        string $eventId,
        string $type,
        string $subscriptionId = 'sub_gateway'
    ) {
        $route = null;

        foreach (Route::getRoutes() as $candidate) {
            if (
                in_array('POST', $candidate->methods(), true) &&
                str_contains(
                    $candidate->getActionName(),
                    'StripeWebhookController'
                )
            ) {
                $route = '/' . ltrim(
                    $candidate->uri(),
                    '/'
                );
                break;
            }
        }

        if (!$route) {
            throw new RuntimeException(
                'Stripe webhook route not found.'
            );
        }

        $payload = json_encode([
            'id' => $eventId,
            'object' => 'event',
            'type' => $type,
            'created' => time(),
            'livemode' => false,
            'data' => [
                'object' => [
                    'id' => $subscriptionId,
                    'object' => 'subscription',
                ],
            ],
        ], JSON_THROW_ON_ERROR);

        $timestamp = time();

        $signature = hash_hmac(
            'sha256',
            $timestamp . '.' . $payload,
            $this->secret
        );

        return $this->call(
            'POST',
            $route,
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_STRIPE_SIGNATURE' =>
                    "t={$timestamp},v1={$signature}",
            ],
            $payload
        );
    }

    public function test_updated_webhook_syncs_cancellation_without_extra_access(): void
    {
        $subscription = $this->subscription();
        $originalEnd = $subscription->ends_at;

        $gateway = Mockery::mock(
            StripeSubscriptionGateway::class
        );

        $gateway->shouldReceive('retrieve')
            ->once()
            ->with('sub_gateway')
            ->andReturn(
                $this->remote($subscription, 'active', true)
            );

        $this->app->instance(
            StripeSubscriptionGateway::class,
            $gateway
        );

        $this->webhook(
            'evt_gateway_cancel',
            'customer.subscription.updated'
        )->assertOk();

        $subscription->refresh();

        $this->assertTrue(
            $subscription->cancel_at->equalTo($originalEnd)
        );
        $this->assertTrue(
            $subscription->ends_at->equalTo($originalEnd)
        );
        $this->assertSame(500, $subscription->respondents_granted);
        $this->assertSame(125, $subscription->respondents_used);
    }

    public function test_late_updated_event_uses_current_stripe_state(): void
    {
        $subscription = $this->subscription();

        $subscription->update([
            'cancel_at' => $subscription->ends_at,
        ]);

        $gateway = Mockery::mock(
            StripeSubscriptionGateway::class
        );

        $gateway->shouldReceive('retrieve')
            ->once()
            ->andReturn(
                $this->remote($subscription, 'active', false)
            );

        $this->app->instance(
            StripeSubscriptionGateway::class,
            $gateway
        );

        $this->webhook(
            'evt_gateway_late',
            'customer.subscription.updated'
        )->assertOk();

        $this->assertNull(
            $subscription->refresh()->cancel_at
        );
    }

    public function test_wrong_customer_cannot_change_subscription(): void
    {
        $subscription = $this->subscription();

        $gateway = Mockery::mock(
            StripeSubscriptionGateway::class
        );

        $gateway->shouldReceive('retrieve')
            ->once()
            ->andReturn(
                $this->remote(
                    $subscription,
                    'active',
                    true,
                    'cus_someone_else'
                )
            );

        $this->app->instance(
            StripeSubscriptionGateway::class,
            $gateway
        );

        $this->webhook(
            'evt_gateway_wrong_customer',
            'customer.subscription.updated'
        )->assertStatus(500);

        $this->assertNull(
            $subscription->refresh()->cancel_at
        );

        $this->assertDatabaseHas(
            'stripe_webhook_events',
            [
                'stripe_event_id' =>
                    'evt_gateway_wrong_customer',
                'status' => 'pending_processing',
            ]
        );
    }

    public function test_late_deleted_event_does_not_cancel_active_remote(): void
    {
        $subscription = $this->subscription();

        $gateway = Mockery::mock(
            StripeSubscriptionGateway::class
        );

        $gateway->shouldReceive('retrieve')
            ->once()
            ->andReturn(
                $this->remote($subscription, 'active', false)
            );

        $this->app->instance(
            StripeSubscriptionGateway::class,
            $gateway
        );

        $this->webhook(
            'evt_gateway_late_deleted',
            'customer.subscription.deleted'
        )->assertOk();

        $this->assertSame(
            'active',
            $subscription->refresh()->status
        );
    }

    public function test_confirmed_remote_cancellation_revokes_access(): void
    {
        $subscription = $this->subscription();

        $gateway = Mockery::mock(
            StripeSubscriptionGateway::class
        );

        $gateway->shouldReceive('retrieve')
            ->once()
            ->andReturn(
                $this->remote($subscription, 'canceled')
            );

        $this->app->instance(
            StripeSubscriptionGateway::class,
            $gateway
        );

        $this->webhook(
            'evt_gateway_confirmed_deleted',
            'customer.subscription.deleted'
        )->assertOk();

        $subscription->refresh();

        $this->assertSame('cancelled', $subscription->status);
        $this->assertTrue(
            $subscription->ends_at->lessThanOrEqualTo(now())
        );
        $this->assertSame(500, $subscription->respondents_granted);
        $this->assertSame(125, $subscription->respondents_used);
    }

    public function test_stripe_api_failure_keeps_event_retryable(): void
    {
        $subscription = $this->subscription();

        $gateway = Mockery::mock(
            StripeSubscriptionGateway::class
        );

        $gateway->shouldReceive('retrieve')
            ->once()
            ->andThrow(
                new RuntimeException('Simulated Stripe outage')
            );

        $this->app->instance(
            StripeSubscriptionGateway::class,
            $gateway
        );

        $this->webhook(
            'evt_gateway_outage',
            'customer.subscription.updated'
        )->assertStatus(500);

        $this->assertDatabaseHas(
            'stripe_webhook_events',
            [
                'stripe_event_id' => 'evt_gateway_outage',
                'status' => 'pending_processing',
            ]
        );

        $this->assertNull(
            $subscription->refresh()->cancel_at
        );
    }

    public function test_cancel_and_resume_do_not_change_paid_entitlements(): void
    {
        $subscription = $this->subscription();
        $originalEnd = $subscription->ends_at;

        $gateway = Mockery::mock(
            StripeSubscriptionGateway::class
        );

        $gateway->shouldReceive('retrieve')
            ->twice()
            ->andReturn(
                $this->remote($subscription, 'active', false),
                $this->remote($subscription, 'active', true)
            );

        $gateway->shouldReceive('changeAutoRenewal')
            ->once()
            ->with('sub_gateway', true)
            ->andReturn(
                $this->remote($subscription, 'active', true)
            );

        $gateway->shouldReceive('changeAutoRenewal')
            ->once()
            ->with('sub_gateway', false)
            ->andReturn(
                $this->remote($subscription, 'active', false)
            );

        $this->app->instance(
            StripeSubscriptionGateway::class,
            $gateway
        );

        $this->actingAs($subscription->user)
            ->post(route('subscriptions.cancellation-request'), [
                'subscription_id' => $subscription->id,
                'type' => 'period_end',
            ])
            ->assertSessionHas('success');

        $this->assertNotNull(
            $subscription->refresh()->cancel_at
        );

        $this->actingAs($subscription->user)
            ->post(route('subscriptions.resume'), [
                'subscription_id' => $subscription->id,
            ])
            ->assertSessionHas('success');

        $subscription->refresh();

        $this->assertNull($subscription->cancel_at);
        $this->assertTrue(
            $subscription->ends_at->equalTo($originalEnd)
        );
        $this->assertSame(500, $subscription->respondents_granted);
        $this->assertSame(125, $subscription->respondents_used);
    }
}
