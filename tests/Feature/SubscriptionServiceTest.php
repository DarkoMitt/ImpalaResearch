<?php

namespace Tests\Feature;

use App\Models\StripePaidInvoice;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Services\SubscriptionService;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Tests\TestCase;

class SubscriptionServiceTest extends TestCase
{
    use RefreshDatabase;

    private SubscriptionService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(SubscriptionService::class);
    }

    private function createPlan(
        string $slug,
        int $duration,
        int $price,
        int $monthlyRespondents
    ): SubscriptionPlan {
        return SubscriptionPlan::query()->create([
            'slug' => $slug,
            'name' => ucfirst($slug),
            'price_cents' => $price,
            'currency' => 'USD',
            'duration_months' => $duration,
            'initial_respondents' => 500,
            'monthly_respondents' => $monthlyRespondents,
            'max_active_studies' => null,
            'features' => [],
            'is_active' => true,
        ]);
    }

    private function createUser(): User
    {
        return User::factory()->create();
    }

    private function activate(
        User $user,
        SubscriptionPlan $plan,
        string $invoiceId,
        string $stripeSubscriptionId,
        CarbonImmutable $start
    ): Subscription {
        return $this->service->activatePaidPeriod(
            invoiceId: $invoiceId,
            stripeSubscriptionId: $stripeSubscriptionId,
            userId: $user->id,
            planId: $plan->id,
            amountPaidCents: $plan->price_cents,
            periodStart: $start,
            periodEnd: $start->addMonthsNoOverflow(
                $plan->duration_months
            ),
            stripeCustomerId: 'cus_test_123'
        );
    }

    public function test_first_paid_invoice_activates_subscription(): void
    {
        $user = $this->createUser();

        $plan = $this->createPlan(
            'monthly', 1, 39000, 0
        );

        $start = CarbonImmutable::parse('2026-10-01 00:00:00');

        $subscription = $this->activate(
            $user,
            $plan,
            'in_test_001',
            'sub_test_001',
            $start
        );

        $this->assertSame('active', $subscription->status);
        $this->assertSame(500, $subscription->respondents_granted);
        $this->assertSame(0, $subscription->respondents_used);
        $this->assertSame(500, $subscription->remaining_respondents);
        $this->assertSame(1, $subscription->months_granted);

        $this->assertDatabaseHas('stripe_paid_invoices', [
            'stripe_invoice_id' => 'in_test_001',
            'subscription_id' => $subscription->id,
        ]);
    }

    public function test_duplicate_invoice_does_not_grant_twice(): void
    {
        $user = $this->createUser();

        $plan = $this->createPlan(
            'monthly', 1, 39000, 0
        );

        $start = CarbonImmutable::parse('2026-10-01');

        $first = $this->activate(
            $user,
            $plan,
            'in_test_duplicate',
            'sub_test_duplicate',
            $start
        );

        $second = $this->activate(
            $user,
            $plan,
            'in_test_duplicate',
            'sub_test_duplicate',
            $start
        );

        $this->assertSame($first->id, $second->id);

        $this->assertSame(
            1,
            StripePaidInvoice::query()
                ->where('stripe_invoice_id', 'in_test_duplicate')
                ->count()
        );

        $this->assertSame(
            500,
            $second->refresh()->respondents_granted
        );
    }

    public function test_semi_yearly_monthly_allowances_and_rollover(): void
    {
        $this->travelTo(
            CarbonImmutable::parse('2026-01-01 12:00:00')
        );

        $user = $this->createUser();

        $plan = $this->createPlan(
            'semi-yearly', 6, 192000, 300
        );

        $start = CarbonImmutable::parse('2026-01-01 00:00:00');

        $subscription = $this->activate(
            $user,
            $plan,
            'in_semi_001',
            'sub_semi_001',
            $start
        );

        $this->assertSame(500, $subscription->respondents_granted);

        $this->travelTo(
            CarbonImmutable::parse('2026-03-02 12:00:00')
        );

        $granted = $this->service
            ->grantDueMonthlyAllowances($subscription);

        $subscription->refresh();

        $this->assertSame(2, $granted);
        $this->assertSame(1100, $subscription->respondents_granted);
        $this->assertSame(1100, $subscription->remaining_respondents);
        $this->assertSame(3, $subscription->months_granted);

        // Re-running must not grant the same months twice.
        $again = $this->service
            ->grantDueMonthlyAllowances($subscription);

        $this->assertSame(0, $again);

        $this->assertSame(
            1100,
            $subscription->refresh()->respondents_granted
        );

        $this->travelBack();
    }

    public function test_yearly_plan_reaches_4900_respondents(): void
    {
        $this->travelTo(
            CarbonImmutable::parse('2026-01-01 12:00:00')
        );

        $user = $this->createUser();

        $plan = $this->createPlan(
            'yearly', 12, 324000, 400
        );

        $start = CarbonImmutable::parse('2026-01-01 00:00:00');

        $subscription = $this->activate(
            $user,
            $plan,
            'in_yearly_001',
            'sub_yearly_001',
            $start
        );

        $this->travelTo(
            CarbonImmutable::parse('2026-12-15 12:00:00')
        );

        $granted = $this->service
            ->grantDueMonthlyAllowances($subscription);

        $subscription->refresh();

        $this->assertSame(11, $granted);
        $this->assertSame(4900, $subscription->respondents_granted);
        $this->assertSame(12, $subscription->months_granted);
        $this->assertNull($subscription->next_allowance_at);

        $this->travelBack();
    }

    public function test_renewal_starts_new_billing_period(): void
    {
        $this->travelTo(
            CarbonImmutable::parse('2026-01-01 12:00:00')
        );

        $user = $this->createUser();

        $plan = $this->createPlan(
            'monthly', 1, 39000, 0
        );

        $firstStart = CarbonImmutable::parse('2026-01-01');

        $subscription = $this->activate(
            $user,
            $plan,
            'in_renewal_001',
            'sub_renewal_001',
            $firstStart
        );

        $subscription->update([
            'respondents_used' => 200,
        ]);

        $this->travelTo(
            CarbonImmutable::parse('2026-02-01 12:00:00')
        );

        $renewed = $this->activate(
            $user,
            $plan,
            'in_renewal_002',
            'sub_renewal_001',
            CarbonImmutable::parse('2026-02-01')
        );

        $this->assertSame($subscription->id, $renewed->id);
        $this->assertSame(500, $renewed->respondents_granted);
        $this->assertSame(0, $renewed->respondents_used);
        $this->assertSame(500, $renewed->remaining_respondents);

        $this->assertSame(
            2,
            StripePaidInvoice::query()
                ->where('subscription_id', $renewed->id)
                ->count()
        );

        $this->travelBack();
    }

    public function test_second_active_subscription_is_rejected(): void
    {
        $this->travelTo(
            CarbonImmutable::parse('2026-01-01 12:00:00')
        );

        $user = $this->createUser();

        $plan = $this->createPlan(
            'monthly', 1, 39000, 0
        );

        $start = CarbonImmutable::parse('2026-01-01');

        $this->activate(
            $user,
            $plan,
            'in_first',
            'sub_first',
            $start
        );

        $this->expectException(RuntimeException::class);

        $this->activate(
            $user,
            $plan,
            'in_second',
            'sub_second',
            $start
        );
    }

    public function test_allowance_is_not_granted_after_expiration(): void
    {
        $this->travelTo(
            CarbonImmutable::parse('2026-01-01 12:00:00')
        );

        $user = $this->createUser();

        $plan = $this->createPlan(
            'semi-yearly', 6, 192000, 300
        );

        $subscription = $this->activate(
            $user,
            $plan,
            'in_expired',
            'sub_expired',
            CarbonImmutable::parse('2026-01-01')
        );

        $this->travelTo(
            CarbonImmutable::parse('2026-08-01 12:00:00')
        );

        $granted = $this->service
            ->grantDueMonthlyAllowances($subscription);

        $this->assertSame(0, $granted);

        $this->assertSame(
            500,
            $subscription->refresh()->respondents_granted
        );

        $this->travelBack();
    }
}
