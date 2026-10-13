<?php

namespace Tests\Feature;

use App\Models\StripePaidInvoice;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Services\SubscriptionService;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

class SubscriptionBillingSafetyTest extends TestCase
{
    use RefreshDatabase;

    private SubscriptionService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(SubscriptionService::class);

        $this->travelTo(
            CarbonImmutable::parse('2026-01-15 12:00:00')
        );
    }

    private function plan(): SubscriptionPlan
    {
        return SubscriptionPlan::query()->create([
            'slug' => 'monthly',
            'name' => 'Monthly',
            'price_cents' => 39000,
            'currency' => 'USD',
            'duration_months' => 1,
            'initial_respondents' => 500,
            'monthly_respondents' => 0,
            'max_active_studies' => null,
            'features' => [],
            'is_active' => true,
        ]);
    }

    public function test_late_invoice_cannot_reset_newer_period(): void
    {
        $user = User::factory()->create();
        $plan = $this->plan();

        $january = CarbonImmutable::parse('2026-01-01');
        $february = CarbonImmutable::parse('2026-02-01');
        $march = CarbonImmutable::parse('2026-03-01');

        $first = $this->service->activatePaidPeriod(
            'in_january',
            'sub_safety',
            $user->id,
            $plan->id,
            39000,
            $january,
            $february
        );

        $this->travelTo(
            CarbonImmutable::parse('2026-02-02 12:00:00')
        );

        $renewed = $this->service->activatePaidPeriod(
            'in_february',
            'sub_safety',
            $user->id,
            $plan->id,
            39000,
            $february,
            $march
        );

        $renewed->update([
            'respondents_used' => 150,
        ]);

        try {
            $this->service->activatePaidPeriod(
                'in_late_january',
                'sub_safety',
                $user->id,
                $plan->id,
                39000,
                $january,
                $february
            );

            $this->fail(
                'Overlapping invoice should have been rejected.'
            );
        } catch (RuntimeException $exception) {
            $this->assertStringContainsString(
                'overlaps',
                $exception->getMessage()
            );
        }

        $this->assertSame(
            $first->id,
            $renewed->id
        );

        $this->assertSame(
            150,
            $renewed->refresh()->respondents_used
        );

        $this->assertSame(
            350,
            $renewed->remaining_respondents
        );

        $this->assertSame(
            2,
            StripePaidInvoice::query()->count()
        );
    }

    public function test_replayed_old_invoice_preserves_new_period(): void
    {
        $user = User::factory()->create();
        $plan = $this->plan();

        $january = CarbonImmutable::parse('2026-01-01');
        $february = CarbonImmutable::parse('2026-02-01');
        $march = CarbonImmutable::parse('2026-03-01');

        $this->service->activatePaidPeriod(
            'in_original_january',
            'sub_replay',
            $user->id,
            $plan->id,
            39000,
            $january,
            $february
        );

        $this->travelTo(
            CarbonImmutable::parse('2026-02-02 12:00:00')
        );

        $current = $this->service->activatePaidPeriod(
            'in_original_february',
            'sub_replay',
            $user->id,
            $plan->id,
            39000,
            $february,
            $march
        );

        $current->update([
            'respondents_used' => 100,
        ]);

        $replayed = $this->service->activatePaidPeriod(
            'in_original_january',
            'sub_replay',
            $user->id,
            $plan->id,
            39000,
            $january,
            $february
        );

        $this->assertSame(
            $current->id,
            $replayed->id
        );

        $this->assertSame(
            100,
            $replayed->refresh()->respondents_used
        );

        $this->assertSame(
            2,
            StripePaidInvoice::query()->count()
        );
    }

    public function test_replayed_invoice_with_changed_amount_is_rejected(): void
    {
        $user = User::factory()->create();
        $plan = $this->plan();

        $start = CarbonImmutable::parse('2026-01-01');

        $this->service->activatePaidPeriod(
            'in_immutable',
            'sub_immutable',
            $user->id,
            $plan->id,
            39000,
            $start,
            $start->addMonth()
        );

        $this->expectException(RuntimeException::class);

        $this->service->activatePaidPeriod(
            'in_immutable',
            'sub_immutable',
            $user->id,
            $plan->id,
            100,
            $start,
            $start->addMonth()
        );
    }
}
