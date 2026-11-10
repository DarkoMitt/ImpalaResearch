<?php

namespace Tests\Feature;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Notifications\SubscriptionStatusChanged;
use Illuminate\Support\Facades\Notification;
use App\Services\StripeSubscriptionGateway;
use App\Services\StripeSubscriptionIdentity;
use Mockery;

class SubscriptionCancellationTest extends TestCase
{
    use RefreshDatabase;

    private function createSubscription(
        User $user,
        bool $demo = true
    ): Subscription {
        $plan = SubscriptionPlan::query()->create([
            'slug' => 'cancellation-test-plan',
            'name' => 'Cancellation Test Plan',
            'price_cents' => 192000,
            'currency' => 'USD',
            'duration_months' => 6,
            'initial_respondents' => 500,
            'monthly_respondents' => 300,
            'is_active' => true,
        ]);

        return Subscription::query()->create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
            'is_demo' => $demo,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addMonths(6),
            'respondents_granted' => 500,
            'respondents_used' => 0,
            'months_granted' => 1,
        ]);
    }

    public function test_user_can_schedule_demo_cancellation(): void
    {
        $user = User::factory()->create();
        $subscription = $this->createSubscription($user);

        $this->actingAs($user)
            ->post(route('subscriptions.cancellation-request'), [
                'subscription_id' => $subscription->id,
                'type' => 'period_end',
            ])
            ->assertSessionHas('success');

        $subscription->refresh();

        $this->assertSame('active', $subscription->status);
        $this->assertNotNull($subscription->cancel_at);
        $this->assertEquals(
            $subscription->ends_at,
            $subscription->cancel_at
        );
    }

    public function test_cancellation_does_not_change_respondents(): void
    {
        $user = User::factory()->create();
        $subscription = $this->createSubscription($user);

        $originalEnd = $subscription->ends_at;

        $this->actingAs($user)
            ->post(route('subscriptions.cancellation-request'), [
                'subscription_id' => $subscription->id,
                'type' => 'period_end',
            ])
            ->assertSessionHas('success');

        $subscription->refresh();

        $this->assertSame(500, $subscription->respondents_granted);
        $this->assertSame(0, $subscription->respondents_used);
        $this->assertTrue(
            $subscription->ends_at->equalTo($originalEnd)
        );
    }

    public function test_user_can_resume_demo_subscription(): void
    {
        $user = User::factory()->create();
        $subscription = $this->createSubscription($user);

        $subscription->update([
            'cancel_at' => $subscription->ends_at,
        ]);

        $this->actingAs($user)
            ->post(route('subscriptions.resume'), [
                'subscription_id' => $subscription->id,
            ])
            ->assertSessionHas('success');

        $subscription->refresh();

        $this->assertNull($subscription->cancel_at);
        $this->assertSame(500, $subscription->respondents_granted);
    }

    public function test_other_user_cannot_cancel_subscription(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $subscription = $this->createSubscription($owner);

        $this->actingAs($other)
            ->post(route('subscriptions.cancellation-request'), [
                'subscription_id' => $subscription->id,
                'type' => 'period_end',
            ])
            ->assertNotFound();

        $this->assertNull(
            $subscription->refresh()->cancel_at
        );
    }

    public function test_other_user_cannot_resume_subscription(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $subscription = $this->createSubscription($owner);

        $subscription->update([
            'cancel_at' => $subscription->ends_at,
        ]);

        $this->actingAs($other)
            ->post(route('subscriptions.resume'), [
                'subscription_id' => $subscription->id,
            ])
            ->assertNotFound();

        $this->assertNotNull(
            $subscription->refresh()->cancel_at
        );
    }

    public function test_subscription_without_stripe_id_cannot_cancel(): void
    {
        $user = User::factory()->create();
        $subscription = $this->createSubscription($user, false);

        $this->actingAs($user)
            ->post(route('subscriptions.cancellation-request'), [
                'subscription_id' => $subscription->id,
                'type' => 'period_end',
            ])
            ->assertSessionHas('error');

        $this->assertNull(
            $subscription->refresh()->cancel_at
        );
    }

    public function test_expired_subscription_cannot_resume(): void
    {
        $user = User::factory()->create();
        $subscription = $this->createSubscription($user);

        $subscription->update([
            'ends_at' => now()->subDay(),
            'cancel_at' => now()->subDay(),
        ]);

        $this->actingAs($user)
            ->post(route('subscriptions.resume'), [
                'subscription_id' => $subscription->id,
            ])
            ->assertSessionHas('error');
    }


public function test_cancellation_sends_subscription_notification_only_once(): void
{
    Notification::fake();

    $user = User::factory()->create();
    $subscription = $this->createSubscription($user);

    $this->actingAs($user)
        ->post(route('subscriptions.cancellation-request'), [
            'subscription_id' => $subscription->id,
            'type' => 'period_end',
        ])
        ->assertSessionHas('success');

    $this->actingAs($user)
        ->post(route('subscriptions.cancellation-request'), [
            'subscription_id' => $subscription->id,
            'type' => 'period_end',
        ])
        ->assertSessionHas('success');

    Notification::assertSentTo(
        $user,
        SubscriptionStatusChanged::class,
        fn ($notification) =>
            $notification->event === 'cancellation_scheduled'
    );

    Notification::assertSentToTimes(
        $user,
        SubscriptionStatusChanged::class,
        1
    );

    $this->assertSame(
        'active',
        $subscription->fresh()->status
    );
}

public function test_resume_sends_subscription_notification_only_once(): void
{
    Notification::fake();

    $user = User::factory()->create();
    $subscription = $this->createSubscription($user);

    $subscription->update([
        'cancel_at' => $subscription->ends_at,
    ]);

    $this->actingAs($user)
        ->post(route('subscriptions.resume'), [
            'subscription_id' => $subscription->id,
        ])
        ->assertSessionHas('success');

    $this->actingAs($user)
        ->post(route('subscriptions.resume'), [
            'subscription_id' => $subscription->id,
        ])
        ->assertSessionHas('success');

    Notification::assertSentTo(
        $user,
        SubscriptionStatusChanged::class,
        fn ($notification) =>
            $notification->event === 'resumed'
    );

    Notification::assertSentToTimes(
        $user,
        SubscriptionStatusChanged::class,
        1
    );

    $this->assertNull(
        $subscription->fresh()->cancel_at
    );
}

/*
|--------------------------------------------------------------------------
| Stripe Cancel / Resume Idempotency Tests
|--------------------------------------------------------------------------
*/

private function createStripeCancellationSubscription(
    User $user
): Subscription {
    $subscription = $this->createSubscription($user, false);

    $subscription->update([
        'stripe_customer_id' => 'cus_cancel_test',
        'stripe_subscription_id' => 'sub_cancel_test',
    ]);

    return $subscription->fresh();
}

private function mockStripeRenewalState(
    bool $initialCancel,
    ?bool $updatedCancel,
    int $expectedUpdates
): void {
    $remote = function (bool $cancel): object {
        return (object) [
            'id' => 'sub_cancel_test',
            'customer' => 'cus_cancel_test',
            'status' => 'active',
            'cancel_at_period_end' => $cancel,
            'metadata' => (object) [
                'user_id' => null,
                'plan_id' => null,
            ],
        ];
    };

    $gateway = Mockery::mock(StripeSubscriptionGateway::class);

    $gateway->shouldReceive('retrieve')
        ->times(1)
        ->with('sub_cancel_test')
        ->andReturn($remote($initialCancel));

    $gateway->shouldReceive('changeAutoRenewal')
        ->times($expectedUpdates)
        ->with('sub_cancel_test', !$initialCancel)
        ->andReturn(
            $remote($updatedCancel ?? $initialCancel)
        );

    $this->app->instance(
        StripeSubscriptionGateway::class,
        $gateway
    );

    // Identity is tested independently in the Stripe security suite.
    $identity = Mockery::mock(StripeSubscriptionIdentity::class);

    $identity->shouldReceive('assertMatches')
        ->times(1 + $expectedUpdates);

    $this->app->instance(
        StripeSubscriptionIdentity::class,
        $identity
    );
}

public function test_stripe_cancel_updates_remote_and_notifies(): void
{
    Notification::fake();

    $user = User::factory()->create();
    $subscription = $this->createStripeCancellationSubscription($user);

    $this->mockStripeRenewalState(false, true, 1);

    $this->actingAs($user)
        ->post(route('subscriptions.cancellation-request'), [
            'subscription_id' => $subscription->id,
            'type' => 'period_end',
        ])
        ->assertSessionHas('success');

    $this->assertNotNull($subscription->fresh()->cancel_at);

    Notification::assertSentToTimes(
        $user,
        SubscriptionStatusChanged::class,
        1
    );
}

public function test_stripe_cancel_already_scheduled_does_not_update_or_notify(): void
{
    Notification::fake();

    $user = User::factory()->create();
    $subscription = $this->createStripeCancellationSubscription($user);

    $subscription->update([
        'cancel_at' => $subscription->ends_at,
    ]);

    $this->mockStripeRenewalState(true, null, 0);

    $this->actingAs($user)
        ->post(route('subscriptions.cancellation-request'), [
            'subscription_id' => $subscription->id,
            'type' => 'period_end',
        ])
        ->assertSessionHas('success');

    Notification::assertNothingSent();
}

public function test_stripe_resume_updates_remote_and_notifies(): void
{
    Notification::fake();

    $user = User::factory()->create();
    $subscription = $this->createStripeCancellationSubscription($user);

    $subscription->update([
        'cancel_at' => $subscription->ends_at,
    ]);

    $this->mockStripeRenewalState(true, false, 1);

    $this->actingAs($user)
        ->post(route('subscriptions.resume'), [
            'subscription_id' => $subscription->id,
        ])
        ->assertSessionHas('success');

    $this->assertNull($subscription->fresh()->cancel_at);

    Notification::assertSentToTimes(
        $user,
        SubscriptionStatusChanged::class,
        1
    );
}

public function test_stripe_resume_already_active_does_not_update_or_notify(): void
{
    Notification::fake();

    $user = User::factory()->create();
    $subscription = $this->createStripeCancellationSubscription($user);

    $this->mockStripeRenewalState(false, null, 0);

    $this->actingAs($user)
        ->post(route('subscriptions.resume'), [
            'subscription_id' => $subscription->id,
        ])
        ->assertSessionHas('success');

    Notification::assertNothingSent();
}

public function test_stripe_cancel_repairs_local_state_without_duplicate_notification(): void
{
    Notification::fake();

    $user = User::factory()->create();
    $subscription = $this->createStripeCancellationSubscription($user);

    // Stripe has cancellation scheduled, but local DB does not.
    $this->mockStripeRenewalState(true, null, 0);

    $this->actingAs($user)
        ->post(route('subscriptions.cancellation-request'), [
            'subscription_id' => $subscription->id,
            'type' => 'period_end',
        ])
        ->assertSessionHas('success');

    $this->assertNotNull($subscription->fresh()->cancel_at);

    Notification::assertNothingSent();
}

public function test_stripe_resume_repairs_local_state_without_duplicate_notification(): void
{
    Notification::fake();

    $user = User::factory()->create();
    $subscription = $this->createStripeCancellationSubscription($user);

    // Local DB says cancelled, but Stripe says auto-renewal is enabled.
    $subscription->update([
        'cancel_at' => $subscription->ends_at,
    ]);

    $this->mockStripeRenewalState(false, null, 0);

    $this->actingAs($user)
        ->post(route('subscriptions.resume'), [
            'subscription_id' => $subscription->id,
        ])
        ->assertSessionHas('success');

    $this->assertNull($subscription->fresh()->cancel_at);

    Notification::assertNothingSent();
}


}
