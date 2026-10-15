<?php

namespace Tests\Feature;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
}
