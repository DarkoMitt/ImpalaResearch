<?php

namespace Tests\Feature;

use App\Models\ResearchRequest;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Services\RespondentAllocationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class RespondentAllocationSecurityTest extends TestCase
{
    use RefreshDatabase;

    private function createPlan(): SubscriptionPlan
    {
        return SubscriptionPlan::query()->create([
            'slug' => 'respondent-test-monthly',
            'name' => 'Respondent Test Monthly',
            'price_cents' => 39000,
            'currency' => 'USD',
            'duration_months' => 1,
            'initial_respondents' => 500,
            'monthly_respondents' => 0,
            'is_active' => true,
        ]);
    }

    private function createSubscription(
        User $user,
        int $granted = 500,
        int $used = 0,
        bool $expired = false
    ): Subscription {
        return Subscription::query()->create([
            'user_id' => $user->id,
            'subscription_plan_id' => $this->createPlan()->id,
            'status' => 'active',
            'is_demo' => true,
            'starts_at' => $expired
                ? now()->subMonths(2)
                : now()->subDay(),
            'ends_at' => $expired
                ? now()->subDay()
                : now()->addMonth(),
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

    public function test_study_reserves_exact_number_of_respondents(): void
    {
        $user = User::factory()->create();
        $subscription = $this->createSubscription($user);

        $study = app(RespondentAllocationService::class)
            ->submit($user, $this->studyData(200));

        $this->assertSame(200, $study->respondents_reserved);
        $this->assertSame($subscription->id, $study->subscription_id);
        $this->assertSame($user->id, $study->user_id);
        $this->assertSame(200, $subscription->fresh()->respondents_used);
        $this->assertSame(300, $subscription->fresh()->remaining_respondents);
    }

    public function test_study_cannot_exceed_remaining_balance(): void
    {
        $user = User::factory()->create();
        $subscription = $this->createSubscription($user, 500, 400);

        try {
            app(RespondentAllocationService::class)
                ->submit($user, $this->studyData(200));

            $this->fail('Expected validation failure.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey(
                'sample_size',
                $exception->errors()
            );
        }

        $this->assertSame(400, $subscription->fresh()->respondents_used);
        $this->assertDatabaseCount('research_requests', 0);
    }

    public function test_multiple_studies_cannot_spend_more_than_balance(): void
    {
        $user = User::factory()->create();
        $subscription = $this->createSubscription($user);
        $service = app(RespondentAllocationService::class);

        $service->submit($user, $this->studyData(300));

        try {
            $service->submit($user, $this->studyData(250));
            $this->fail('Second study should be rejected.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey(
                'sample_size',
                $exception->errors()
            );
        }

        $this->assertSame(300, $subscription->fresh()->respondents_used);
        $this->assertDatabaseCount('research_requests', 1);
    }

    public function test_user_without_subscription_cannot_submit(): void
    {
        $user = User::factory()->create();

        $this->expectException(ValidationException::class);

        app(RespondentAllocationService::class)
            ->submit($user, $this->studyData(100));
    }

    public function test_expired_subscription_cannot_be_used(): void
    {
        $user = User::factory()->create();
        $this->createSubscription($user, 500, 0, true);

        $this->expectException(ValidationException::class);

        app(RespondentAllocationService::class)
            ->submit($user, $this->studyData(100));
    }

    public function test_user_cannot_use_another_users_balance(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $subscription = $this->createSubscription($owner);

        try {
            app(RespondentAllocationService::class)
                ->submit($other, $this->studyData(100));

            $this->fail('Other user should be rejected.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey(
                'subscription',
                $exception->errors()
            );
        }

        $this->assertSame(0, $subscription->fresh()->respondents_used);
        $this->assertDatabaseCount('research_requests', 0);
    }

    public function test_study_creation_failure_rolls_back_balance(): void
    {
        $user = User::factory()->create();
        $subscription = $this->createSubscription($user);

        $data = $this->studyData(200);
        $data['goal'] = null;

        try {
            app(RespondentAllocationService::class)
                ->submit($user, $data);

            $this->fail('Invalid study should not be created.');
        } catch (\Throwable $exception) {
            $this->assertSame(
                0,
                $subscription->fresh()->respondents_used
            );
            $this->assertDatabaseCount('research_requests', 0);
        }
    }
}
