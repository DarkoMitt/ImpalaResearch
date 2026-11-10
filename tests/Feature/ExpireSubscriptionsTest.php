<?php

namespace Tests\Feature;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Notifications\SubscriptionStatusChanged;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ExpireSubscriptionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_expired_demo_subscription_is_expired_and_notified_once(): void
    {
        $user = User::factory()->create();

        $planId = DB::table('subscription_plans')->insertGetId([
            'name' => 'Test Demo Plan',
            'slug' => 'test-demo-plan',
            'price_cents' => 2900,
            'duration_months' => 1,
            'initial_respondents' => 400,
            'monthly_respondents' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $planId,
            'status' => 'active',
            'is_demo' => true,
            'starts_at' => now()->subMonth(),
            'ends_at' => now()->subMinute(),
            'respondents_granted' => 400,
            'respondents_used' => 100,
            'months_granted' => 1,
        ]);

        $this->artisan('subscriptions:expire')
            ->assertExitCode(0);

        $subscription->refresh();

        $this->assertSame('expired', $subscription->status);
        $this->assertNull($subscription->next_allowance_at);
        $this->assertSame(400, $subscription->respondents_granted);
        $this->assertSame(100, $subscription->respondents_used);

        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'type' => SubscriptionStatusChanged::class,
        ]);

        $this->artisan('subscriptions:expire')
            ->assertExitCode(0);

        $this->assertSame(
            1,
            DB::table('notifications')
                ->where('notifiable_id', $user->id)
                ->where('type', SubscriptionStatusChanged::class)
                ->count()
        );
    }


public function test_active_demo_subscription_is_not_expired(): void
{
    $user = User::factory()->create();

    $planId = DB::table('subscription_plans')->insertGetId([
        'name' => 'Active Demo Plan',
        'slug' => 'active-demo-plan',
        'price_cents' => 2900,
        'duration_months' => 1,
        'initial_respondents' => 400,
        'monthly_respondents' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $subscription = Subscription::create([
        'user_id' => $user->id,
        'subscription_plan_id' => $planId,
        'status' => 'active',
        'is_demo' => true,
        'starts_at' => now()->subDays(25),
        'ends_at' => now()->addDays(5),
        'respondents_granted' => 400,
        'respondents_used' => 100,
        'months_granted' => 1,
    ]);

    $this->artisan('subscriptions:expire')
        ->assertExitCode(0);

    $subscription->refresh();

    $this->assertSame('active', $subscription->status);
    $this->assertSame(400, $subscription->respondents_granted);
    $this->assertSame(100, $subscription->respondents_used);

    $this->assertDatabaseMissing('notifications', [
        'notifiable_type' => User::class,
        'notifiable_id' => $user->id,
        'type' => SubscriptionStatusChanged::class,
    ]);
}

public function test_expired_stripe_subscription_is_not_changed_by_demo_scheduler(): void
{
    $user = User::factory()->create();

    $planId = DB::table('subscription_plans')->insertGetId([
        'name' => 'Stripe Protection Plan',
        'slug' => 'stripe-protection-plan',
        'price_cents' => 2900,
        'duration_months' => 1,
        'initial_respondents' => 400,
        'monthly_respondents' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $subscription = Subscription::create([
        'user_id' => $user->id,
        'subscription_plan_id' => $planId,
        'status' => 'active',
        'is_demo' => false,
        'stripe_customer_id' => 'cus_expire_test',
        'stripe_subscription_id' => 'sub_expire_test',
        'starts_at' => now()->subMonth(),
        'ends_at' => now()->subMinute(),
        'respondents_granted' => 400,
        'respondents_used' => 100,
        'months_granted' => 1,
    ]);

    $this->artisan('subscriptions:expire')
        ->assertExitCode(0);

    $subscription->refresh();

    $this->assertSame('active', $subscription->status);
    $this->assertSame(400, $subscription->respondents_granted);
    $this->assertSame(100, $subscription->respondents_used);

    $this->assertDatabaseMissing('notifications', [
        'notifiable_type' => User::class,
        'notifiable_id' => $user->id,
        'type' => SubscriptionStatusChanged::class,
    ]);
}

public function test_cancelled_demo_subscription_is_not_expired_again(): void
{
    $user = User::factory()->create();

    $planId = DB::table('subscription_plans')->insertGetId([
        'name' => 'Cancelled Demo Plan',
        'slug' => 'cancelled-demo-plan',
        'price_cents' => 2900,
        'duration_months' => 1,
        'initial_respondents' => 400,
        'monthly_respondents' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $subscription = Subscription::create([
        'user_id' => $user->id,
        'subscription_plan_id' => $planId,
        'status' => 'cancelled',
        'is_demo' => true,
        'starts_at' => now()->subMonth(),
        'ends_at' => now()->subMinute(),
        'cancelled_at' => now()->subDay(),
        'respondents_granted' => 400,
        'respondents_used' => 100,
        'months_granted' => 1,
    ]);

    $this->artisan('subscriptions:expire')
        ->assertExitCode(0);

    $subscription->refresh();

    $this->assertSame('cancelled', $subscription->status);
    $this->assertSame(400, $subscription->respondents_granted);
    $this->assertSame(100, $subscription->respondents_used);

    $this->assertDatabaseMissing('notifications', [
        'notifiable_type' => User::class,
        'notifiable_id' => $user->id,
        'type' => SubscriptionStatusChanged::class,
    ]);
}

public function test_renewed_demo_subscription_is_not_expired(): void
{
    $user = User::factory()->create();

    $planId = DB::table('subscription_plans')->insertGetId([
        'name' => 'Renewed Demo Plan',
        'slug' => 'renewed-demo-plan',
        'price_cents' => 2900,
        'duration_months' => 1,
        'initial_respondents' => 400,
        'monthly_respondents' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $subscription = Subscription::create([
        'user_id' => $user->id,
        'subscription_plan_id' => $planId,
        'status' => 'active',
        'is_demo' => true,
        'starts_at' => now()->subMonth(),
        'ends_at' => now()->subMinute(),
        'respondents_granted' => 400,
        'respondents_used' => 100,
        'months_granted' => 1,
    ]);

    // Simulate a renewal before the scheduler runs.
    $subscription->update([
        'ends_at' => now()->addMonth(),
    ]);

    $this->artisan('subscriptions:expire')
        ->assertExitCode(0);

    $subscription->refresh();

    $this->assertSame('active', $subscription->status);
    $this->assertTrue($subscription->ends_at->isFuture());
    $this->assertSame(400, $subscription->respondents_granted);
    $this->assertSame(100, $subscription->respondents_used);

    $this->assertDatabaseMissing('notifications', [
        'notifiable_type' => User::class,
        'notifiable_id' => $user->id,
        'type' => SubscriptionStatusChanged::class,
    ]);
}

public function test_demo_subscription_without_end_date_is_not_expired(): void
{
    $user = User::factory()->create();
    $planId = $this->createExpirationTestPlan('no-end-date');

    $subscription = $this->createExpirationTestSubscription(
        $user,
        $planId,
        [
            'ends_at' => null,
        ]
    );

    $this->artisan('subscriptions:expire')->assertExitCode(0);

    $this->assertSame('active', $subscription->fresh()->status);
    $this->assertSame(0, $user->notifications()->count());
}

public function test_pending_demo_subscription_is_not_expired(): void
{
    $user = User::factory()->create();
    $planId = $this->createExpirationTestPlan('pending');

    $subscription = $this->createExpirationTestSubscription(
        $user,
        $planId,
        [
            'status' => 'pending',
        ]
    );

    $this->artisan('subscriptions:expire')->assertExitCode(0);

    $this->assertSame('pending', $subscription->fresh()->status);
    $this->assertSame(0, $user->notifications()->count());
}

public function test_multiple_expired_demo_subscriptions_are_processed(): void
{
    $planId = $this->createExpirationTestPlan('multiple');

    $subscriptions = [];

    for ($i = 0; $i < 3; $i++) {
        $user = User::factory()->create();

        $subscriptions[] = $this->createExpirationTestSubscription(
            $user,
            $planId
        );
    }

    $this->artisan('subscriptions:expire')->assertExitCode(0);

    foreach ($subscriptions as $subscription) {
        $this->assertSame('expired', $subscription->fresh()->status);
        $this->assertSame(
            1,
            $subscription->user->notifications()->count()
        );
    }
}

public function test_expiration_command_does_not_duplicate_notifications(): void
{
    $user = User::factory()->create();
    $planId = $this->createExpirationTestPlan('idempotency');

    $subscription = $this->createExpirationTestSubscription(
        $user,
        $planId
    );

    for ($i = 0; $i < 3; $i++) {
        $this->artisan('subscriptions:expire')->assertExitCode(0);
    }

    $this->assertSame('expired', $subscription->fresh()->status);
    $this->assertSame(1, $user->notifications()->count());
}

public function test_expiration_preserves_respondent_credit_balances(): void
{
    $user = User::factory()->create();
    $planId = $this->createExpirationTestPlan('credits');

    $subscription = $this->createExpirationTestSubscription(
        $user,
        $planId,
        [
            'respondents_granted' => 800,
            'respondents_used' => 275,
            'months_granted' => 2,
            'next_allowance_at' => now()->addMonth(),
        ]
    );

    $this->artisan('subscriptions:expire')->assertExitCode(0);

    $subscription->refresh();

    $this->assertSame('expired', $subscription->status);
    $this->assertSame(800, $subscription->respondents_granted);
    $this->assertSame(275, $subscription->respondents_used);
    $this->assertSame(525, $subscription->remaining_respondents);
    $this->assertSame(2, $subscription->months_granted);
    $this->assertNull($subscription->next_allowance_at);
}

public function test_expiration_notification_contains_correct_data(): void
{
    $user = User::factory()->create();
    $planId = $this->createExpirationTestPlan('notification');

    $subscription = $this->createExpirationTestSubscription(
        $user,
        $planId
    );

    $this->artisan('subscriptions:expire')->assertExitCode(0);

    $notification = $user->notifications()->first();

    $this->assertNotNull($notification);
    $this->assertSame(
        SubscriptionStatusChanged::class,
        $notification->type
    );
    $this->assertSame('expired', $notification->data['event']);
    $this->assertSame(
        $subscription->id,
        $notification->data['subscription_id']
    );
    $this->assertSame(
        'Subscription Expired',
        $notification->data['title']
    );
    $this->assertNotEmpty($notification->data['message']);
}

/*
|--------------------------------------------------------------------------
| Test Helpers
|--------------------------------------------------------------------------
*/

private function createExpirationTestPlan(string $slug): int
{
    return DB::table('subscription_plans')->insertGetId([
        'name' => 'Expiration Test ' . $slug,
        'slug' => 'expiration-test-' . $slug,
        'price_cents' => 2900,
        'duration_months' => 1,
        'initial_respondents' => 400,
        'monthly_respondents' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

private function createExpirationTestSubscription(
    User $user,
    int $planId,
    array $overrides = []
): Subscription {
    return Subscription::create(array_merge([
        'user_id' => $user->id,
        'subscription_plan_id' => $planId,
        'status' => 'active',
        'is_demo' => true,
        'starts_at' => now()->subMonth(),
        'ends_at' => now()->subMinute(),
        'respondents_granted' => 400,
        'respondents_used' => 100,
        'months_granted' => 1,
    ], $overrides));
}

}
