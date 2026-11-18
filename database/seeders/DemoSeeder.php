<?php

namespace Database\Seeders;

use App\Models\ResearchRequest;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Services\RespondentAllocationService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment('local', 'testing')) {
            throw new RuntimeException(
                'DemoSeeder is restricted to local and testing environments.'
            );
        }

        $password = env('DEMO_PASSWORD');

        if (!$password || strlen($password) < 12) {
            throw new RuntimeException(
                'Set DEMO_PASSWORD in .env (at least 12 characters) before running DemoSeeder.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Subscription Plan
        |--------------------------------------------------------------------------
        */

        $plan = SubscriptionPlan::where('slug', 'monthly')->first();

        if (!$plan) {
            throw new RuntimeException(
                'Monthly subscription plan not found. Run SubscriptionPlanSeeder first.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Demo Accounts
        |--------------------------------------------------------------------------
        */

        $admin = User::firstOrCreate(
            ['email' => 'admin.demo@impala.test'],
            [
                'name' => 'Impala Demo Admin',
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]
        );

        $customer = User::firstOrCreate(
            ['email' => 'researcher.demo@impala.test'],
            [
                'name' => 'Impala Demo Researcher',
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]
        );

        // Never promote or modify an existing unrelated account.
        if (
            ($admin->wasRecentlyCreated === false &&
                $admin->role !== 'admin') ||
            ($customer->wasRecentlyCreated === false &&
                $customer->role !== 'user')
        ) {
            throw new RuntimeException(
                'A demo email is already used by an incompatible account.'
            );
        }

        if ($admin->wasRecentlyCreated) {
            $admin->forceFill([
                'role' => 'admin',
                'account_type' => 'individual',
            ])->save();
        }

        if ($customer->wasRecentlyCreated) {
            $customer->forceFill([
                'role' => 'user',
                'account_type' => 'individual',
            ])->save();
        }

        /*
        |--------------------------------------------------------------------------
        | Demo Subscription
        |--------------------------------------------------------------------------
        */

        $subscription = Subscription::firstOrCreate(
            [
                'user_id' => $customer->id,
                'subscription_plan_id' => $plan->id,
                'is_demo' => true,
            ],
            [
                'status' => 'active',
                'price_paid_cents' => 0,
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
                'respondents_granted' => 500,
                'respondents_used' => 0,
                'months_granted' => 1,
                'next_allowance_at' => null,
            ]
        );

        if (
            $subscription->status !== 'active' ||
            !$subscription->starts_at ||
            !$subscription->ends_at ||
            $subscription->starts_at->isFuture() ||
            $subscription->ends_at->isPast()
        ) {
            throw new RuntimeException(
                'Existing demo subscription is not active. Review it before reseeding.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Demo Studies
        |--------------------------------------------------------------------------
        */

        $studies = [
            [
                'key' => 'IMPALA-DEMO-001',
                'goal' => 'Product Research',
                'methods' => ['Eye Tracking', 'Clicks'],
                'country' => 'North Macedonia',
                'audience' => 'Adults aged 18-45 interested in online shopping.',
                'sample_size' => 80,
                'timeline' => '1-2 weeks',
                'status' => 'submitted',
            ],
            [
                'key' => 'IMPALA-DEMO-002',
                'goal' => 'Brand Research',
                'methods' => ['Emotion Measurement', 'Surveys'],
                'country' => 'Germany',
                'audience' => 'Consumers aged 25-55 familiar with digital brands.',
                'sample_size' => 120,
                'timeline' => '2-4 weeks',
                'status' => 'in_progress',
            ],
            [
                'key' => 'IMPALA-DEMO-003',
                'goal' => 'UX Research',
                'methods' => [
                    'RTM (Reaction Time Measurement)',
                    'Eye Tracking',
                    'Clicks',
                ],
                'country' => 'United States',
                'audience' => 'Adults who regularly use mobile applications.',
                'sample_size' => 100,
                'timeline' => '1-2 months',
                'status' => 'completed',
            ],
        ];

        $allocation = app(RespondentAllocationService::class);

        foreach ($studies as $studyData) {
            $existing = ResearchRequest::query()
                ->where('user_id', $customer->id)
                ->where('notes', $studyData['key'])
                ->first();

            if ($existing) {
                $this->command?->info(
                    "Study {$studyData['key']} already exists. Skipping."
                );

                continue;
            }

            $study = $allocation->submit($customer, [
                'goal' => $studyData['goal'],
                'methods' => $studyData['methods'],
                'country' => $studyData['country'],
                'audience' => $studyData['audience'],
                'sample_size' => $studyData['sample_size'],
                'timeline' => $studyData['timeline'],
                'full_name' => $customer->name,
                'email' => $customer->email,
                'phone' => '+38970123456',
                'company' => 'Impala Demo',
                'notes' => $studyData['key'],
            ]);

            // Demo fixtures only; normal status changes use Admin Panel.
            $study->update([
                'status' => $studyData['status'],
            ]);

            $this->command?->info(
                "Created {$study->public_request_id} ({$studyData['status']})."
            );
        }

        $this->command?->info('Demo data setup completed.');
    }
}
