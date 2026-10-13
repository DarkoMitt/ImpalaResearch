<?php

namespace Tests\Feature;

use App\Models\Subscription;
use App\Models\SubscriptionCancellationRequest;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionCancellationTest extends TestCase
{
    use RefreshDatabase;

    private function createSubscription(
        User $user,
        int $months = 6
    ): Subscription {
        $plan = SubscriptionPlan::query()->create([
            'slug' => 'plan-' . $months . '-' . $user->id,
            'name' => 'Test Plan',
            'price_cents' => $months === 1 ? 39000 : 192000,
            'currency' => 'USD',
            'duration_months' => $months,
            'initial_respondents' => 500,
            'monthly_respondents' => $months === 1 ? 0 : 300,
            'is_active' => true,
        ]);

        return Subscription::query()->create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
            'is_demo' => true,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addMonths($months),
            'respondents_granted' => 500,
            'respondents_used' => 0,
            'months_granted' => 1,
        ]);
    }

    public function test_period_end_cancellation_does_not_need_approval(): void
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

        $this->assertNotNull($subscription->cancel_at);
        $this->assertSame('active', $subscription->status);

        $this->assertDatabaseCount(
            'subscription_cancellation_requests',
            0
        );
    }

    public function test_user_cannot_cancel_another_users_subscription(): void
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
    }

    public function test_duplicate_early_refund_request_is_prevented(): void
    {
        $user = User::factory()->create();
        $subscription = $this->createSubscription($user);

        $payload = [
            'subscription_id' => $subscription->id,
            'type' => 'early_refund',
        ];

        $this->actingAs($user)
            ->post(route('subscriptions.cancellation-request'), $payload)
            ->assertSessionHas('success');

        $this->actingAs($user)
            ->post(route('subscriptions.cancellation-request'), $payload)
            ->assertSessionHas('error');

        $this->assertSame(
            1,
            SubscriptionCancellationRequest::query()->count()
        );
    }

    public function test_early_refund_is_not_available_for_monthly_plan(): void
    {
        $user = User::factory()->create();
        $subscription = $this->createSubscription($user, 1);

        $this->actingAs($user)
            ->post(route('subscriptions.cancellation-request'), [
                'subscription_id' => $subscription->id,
                'type' => 'early_refund',
            ])
            ->assertSessionHas('error');

        $this->assertDatabaseCount(
            'subscription_cancellation_requests',
            0
        );
    }

    public function test_guest_cannot_submit_cancellation_request(): void
    {
        $this->post(route('subscriptions.cancellation-request'), [
            'subscription_id' => 1,
            'type' => 'period_end',
        ])->assertRedirect(route('login'));
    }
}
