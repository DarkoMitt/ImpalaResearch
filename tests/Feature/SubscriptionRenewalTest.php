<?php

namespace Tests\Feature;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionRenewalTest extends TestCase
{
    use RefreshDatabase;

    private function subscription(
        User $user,
        bool $demo = true
    ): Subscription {
        $plan = SubscriptionPlan::query()->create([
            'slug' => 'test-plan',
            'name' => 'Test Plan',
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

    public function test_demo_subscription_can_be_cancelled(): void
    {
        $user = User::factory()->create();
        $subscription = $this->subscription($user);

        $this->actingAs($user)
            ->post(route('subscriptions.cancellation-request'), [
                'subscription_id' => $subscription->id,
                'type' => 'period_end',
            ])
            ->assertSessionHas('success');

        $subscription->refresh();

        $this->assertNotNull($subscription->cancel_at);
        $this->assertSame('active', $subscription->status);
        $this->assertTrue(
            $subscription->cancel_at->equalTo(
                $subscription->ends_at
            )
        );
    }

    public function test_demo_subscription_can_be_resumed(): void
    {
        $user = User::factory()->create();
        $subscription = $this->subscription($user);

        $subscription->update([
            'cancel_at' => $subscription->ends_at,
        ]);

        $this->actingAs($user)
            ->post(route('subscriptions.resume'), [
                'subscription_id' => $subscription->id,
            ])
            ->assertSessionHas('success');

        $this->assertNull(
            $subscription->refresh()->cancel_at
        );
    }

    public function test_repeated_cancellation_is_safe(): void
    {
        $user = User::factory()->create();
        $subscription = $this->subscription($user);

        $payload = [
            'subscription_id' => $subscription->id,
            'type' => 'period_end',
        ];

        $this->actingAs($user)
            ->post(route('subscriptions.cancellation-request'), $payload)
            ->assertSessionHas('success');

        $this->actingAs($user)
            ->post(route('subscriptions.cancellation-request'), $payload)
            ->assertSessionHas('success');

        $this->assertDatabaseCount(
            'subscription_cancellation_requests',
            0
        );
    }

    public function test_other_user_cannot_cancel_subscription(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $subscription = $this->subscription($owner);

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

        $subscription = $this->subscription($owner);

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

    public function test_non_demo_without_stripe_id_cannot_be_cancelled(): void
    {
        $user = User::factory()->create();
        $subscription = $this->subscription($user, false);

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

    public function test_expired_demo_subscription_cannot_be_resumed(): void
    {
        $user = User::factory()->create();
        $subscription = $this->subscription($user);

        $subscription->update([
            'ends_at' => now()->subHour(),
            'cancel_at' => now()->subHour(),
        ]);

        $this->actingAs($user)
            ->post(route('subscriptions.resume'), [
                'subscription_id' => $subscription->id,
            ])
            ->assertSessionHas('error');
    }
}
