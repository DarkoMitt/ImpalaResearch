<?php

namespace Tests\Feature;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Notifications\RespondentCreditsChanged;
use App\Services\RespondentAllocationService;
use App\Services\SubscriptionService;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use App\Models\ResearchRequest;
use App\Notifications\ResearchStatusChanged;

class RespondentCreditsNotificationTest extends TestCase
{
    use RefreshDatabase;

    private function createPlan(
        int $duration = 1,
        int $monthlyCredits = 0
    ): SubscriptionPlan {
        return SubscriptionPlan::query()->create([
            'slug' => 'notification-plan-' . uniqid(),
            'name' => 'Notification Test Plan',
            'price_cents' => 39000,
            'currency' => 'USD',
            'duration_months' => $duration,
            'initial_respondents' => 500,
            'monthly_respondents' => $monthlyCredits,
            'is_active' => true,
        ]);
    }

    private function createSubscription(
        User $user,
        int $granted = 500,
        int $used = 0
    ): Subscription {
        return Subscription::query()->create([
            'user_id' => $user->id,
            'subscription_plan_id' => $this->createPlan()->id,
            'status' => 'active',
            'is_demo' => true,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addMonth(),
            'respondents_granted' => $granted,
            'respondents_used' => $used,
            'months_granted' => 1,
        ]);
    }

    private function studyData(int $size): array
    {
        return [
            'goal' => 'Product Research',
            'methods' => ['Surveys'],
            'country' => 'North Macedonia',
            'audience' => 'Adults aged 18-45',
            'sample_size' => $size,
            'timeline' => '2-4 weeks',
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+38970123456',
            'company' => '',
            'notes' => null,
        ];
    }

    public function test_low_credits_notification_is_sent_when_threshold_is_crossed(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $this->createSubscription($user);

        app(RespondentAllocationService::class)
            ->submit($user, $this->studyData(410));

        Notification::assertSentTo(
            $user,
            RespondentCreditsChanged::class,
            fn ($notification) =>
                $notification->event === 'low'
                && $notification->remaining === 90
        );
    }

    public function test_low_credits_notification_is_not_repeated(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $this->createSubscription($user);

        $service = app(RespondentAllocationService::class);

        $service->submit($user, $this->studyData(410));
        $service->submit($user, $this->studyData(20));

        Notification::assertSentToTimes(
            $user,
            RespondentCreditsChanged::class,
            1
        );
    }

    public function test_exhausted_notification_is_sent_at_zero_balance(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $this->createSubscription($user);

        app(RespondentAllocationService::class)
            ->submit($user, $this->studyData(500));

        Notification::assertSentTo(
            $user,
            RespondentCreditsChanged::class,
            fn ($notification) =>
                $notification->event === 'exhausted'
                && $notification->remaining === 0
        );

        Notification::assertNotSentTo(
            $user,
            RespondentCreditsChanged::class,
            fn ($notification) =>
                $notification->event === 'low'
        );
    }

    public function test_no_credit_warning_when_balance_is_above_threshold(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $this->createSubscription($user);

        app(RespondentAllocationService::class)
            ->submit($user, $this->studyData(200));

        Notification::assertNotSentTo(
            $user,
            RespondentCreditsChanged::class
        );
    }

    public function test_paid_subscription_sends_credits_added_notification(): void
    {
        Notification::fake();

        $this->travelTo(
            CarbonImmutable::parse('2026-01-01 12:00:00')
        );

        try {
            $user = User::factory()->create();
            $plan = $this->createPlan();

            app(SubscriptionService::class)->activatePaidPeriod(
                invoiceId: 'in_notification_001',
                stripeSubscriptionId: 'sub_notification_001',
                userId: $user->id,
                planId: $plan->id,
                amountPaidCents: $plan->price_cents,
                periodStart: CarbonImmutable::parse('2026-01-01'),
                periodEnd: CarbonImmutable::parse('2026-02-01'),
                stripeCustomerId: 'cus_notification_001'
            );

            Notification::assertSentTo(
                $user,
                RespondentCreditsChanged::class,
                fn ($notification) =>
                    $notification->event === 'added'
                    && $notification->amount === 500
                    && $notification->remaining === 500
            );
        } finally {
            $this->travelBack();
        }
    }

    public function test_duplicate_paid_invoice_does_not_repeat_notification(): void
    {
        Notification::fake();

        $this->travelTo(
            CarbonImmutable::parse('2026-01-01 12:00:00')
        );

        try {
            $user = User::factory()->create();
            $plan = $this->createPlan();
            $service = app(SubscriptionService::class);

            $arguments = [
                'invoiceId' => 'in_notification_duplicate',
                'stripeSubscriptionId' => 'sub_notification_duplicate',
                'userId' => $user->id,
                'planId' => $plan->id,
                'amountPaidCents' => $plan->price_cents,
                'periodStart' => CarbonImmutable::parse('2026-01-01'),
                'periodEnd' => CarbonImmutable::parse('2026-02-01'),
                'stripeCustomerId' => 'cus_notification_duplicate',
            ];

            $service->activatePaidPeriod(...$arguments);
            $service->activatePaidPeriod(...$arguments);

            Notification::assertSentToTimes(
                $user,
                RespondentCreditsChanged::class,
                1
            );
        } finally {
            $this->travelBack();
        }
    }

    public function test_monthly_allowance_sends_correct_credits_added_notification(): void
    {
        Notification::fake();

        $this->travelTo(
            CarbonImmutable::parse('2026-01-01 12:00:00')
        );

        try {
            $user = User::factory()->create();
            $plan = $this->createPlan(6, 300);
            $service = app(SubscriptionService::class);

            $subscription = $service->activatePaidPeriod(
                invoiceId: 'in_notification_monthly',
                stripeSubscriptionId: 'sub_notification_monthly',
                userId: $user->id,
                planId: $plan->id,
                amountPaidCents: $plan->price_cents,
                periodStart: CarbonImmutable::parse('2026-01-01'),
                periodEnd: CarbonImmutable::parse('2026-07-01'),
                stripeCustomerId: 'cus_notification_monthly'
            );

            $this->travelTo(
                CarbonImmutable::parse('2026-03-02 12:00:00')
            );

            $granted = $service
                ->grantDueMonthlyAllowances($subscription);

            $this->assertSame(2, $granted);

            Notification::assertSentTo(
                $user,
                RespondentCreditsChanged::class,
                fn ($notification) =>
                    $notification->event === 'added'
                    && $notification->amount === 600
                    && $notification->remaining === 1100
            );

            $service->grantDueMonthlyAllowances($subscription);

            Notification::assertSentToTimes(
                $user,
                RespondentCreditsChanged::class,
                2
            );
        } finally {
            $this->travelBack();
        }
    }


public function test_rejected_study_refunds_credits_and_notifies_user(): void
{
    Notification::fake();

    $user = User::factory()->create();
    $admin = User::factory()->create(['role' => 'admin']);

    $subscription = $this->createSubscription($user);

    $study = app(RespondentAllocationService::class)
        ->submit($user, $this->studyData(200));

    $this->assertSame(
        200,
        $subscription->fresh()->respondents_used
    );

    $this->actingAs($admin)
        ->patch(
            route('admin.research-requests.update-status', $study),
            [
                'status' => 'rejected',
                'rejection_reason' => 'Incomplete research requirements.',
            ]
        )
        ->assertRedirect();

    $this->assertSame('rejected', $study->fresh()->status);

    $this->assertSame(
        0,
        $subscription->fresh()->respondents_used
    );

    Notification::assertSentTo(
        $user,
        RespondentCreditsChanged::class,
        fn ($notification) =>
            $notification->event === 'refunded'
            && $notification->amount === 200
            && $notification->remaining === 500
    );

    Notification::assertSentTo(
        $user,
        ResearchStatusChanged::class,
        fn ($notification) =>
            $notification->newStatus === 'rejected'
    );
}

public function test_failed_rejection_does_not_refund_or_notify(): void
{
    Notification::fake();

    $user = User::factory()->create();
    $admin = User::factory()->create(['role' => 'admin']);

    $subscription = $this->createSubscription($user);

    $study = app(RespondentAllocationService::class)
        ->submit($user, $this->studyData(200));

    $this->actingAs($admin)
        ->patch(
            route('admin.research-requests.update-status', $study),
            [
                'status' => 'rejected',
                'rejection_reason' => '',
            ]
        )
        ->assertSessionHasErrors('rejection_reason');

    $this->assertSame('submitted', $study->fresh()->status);

    $this->assertSame(
        200,
        $subscription->fresh()->respondents_used
    );

    Notification::assertNotSentTo(
        $user,
        RespondentCreditsChanged::class
    );

    Notification::assertNotSentTo(
        $user,
        ResearchStatusChanged::class
    );
}

public function test_repeated_rejection_does_not_refund_twice(): void
{
    Notification::fake();

    $user = User::factory()->create();
    $admin = User::factory()->create(['role' => 'admin']);

    $subscription = $this->createSubscription($user);

    $study = app(RespondentAllocationService::class)
        ->submit($user, $this->studyData(200));

    $this->actingAs($admin)
        ->patch(
            route('admin.research-requests.update-status', $study),
            [
                'status' => 'rejected',
                'rejection_reason' => 'Invalid research requirements.',
            ]
        )
        ->assertRedirect();

    $this->assertSame(
        0,
        $subscription->fresh()->respondents_used
    );

    $this->actingAs($admin)
        ->patch(
            route('admin.research-requests.update-status', $study),
            [
                'status' => 'rejected',
                'rejection_reason' => 'Invalid research requirements.',
            ]
        )
        ->assertSessionHasErrors('status');

    $this->assertSame(
        0,
        $subscription->fresh()->respondents_used
    );

    Notification::assertSentToTimes(
        $user,
        RespondentCreditsChanged::class,
        1
    );

    Notification::assertSentToTimes(
        $user,
        ResearchStatusChanged::class,
        1
    );
}


public function test_credits_exhausted_notification_is_saved_in_database(): void
{
    $user = User::factory()->create();

    $subscription = $this->createSubscription($user);

    app(RespondentAllocationService::class)
        ->submit($user, $this->studyData(500));

    $this->assertSame(
        0,
        $subscription->fresh()->remaining_respondents
    );

    $notification = $user->notifications()
        ->where('type', RespondentCreditsChanged::class)
        ->first();

    $this->assertNotNull($notification);

    $this->assertSame(
        'respondent_credits',
        $notification->data['type']
    );

    $this->assertSame(
        'exhausted',
        $notification->data['event']
    );

    $this->assertSame(
        0,
        $notification->data['remaining']
    );

    $this->assertNull($notification->read_at);
}


}
