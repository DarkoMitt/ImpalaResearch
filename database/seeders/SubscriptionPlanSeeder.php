<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'slug' => 'monthly',
                'name' => 'Monthly',
                'price_cents' => 39000,
                'currency' => 'USD',
                'duration_months' => 1,
                'initial_respondents' => 500,
                'monthly_respondents' => 0,
                'max_active_studies' => 2,
                'features' => [
                    'All research methodologies',
                    'Standard reporting dashboard',
                    'Email support',
                    '500 respondents included',
                    'Monthly flexibility',
                ],
                'is_active' => true,
            ],
            [
                'slug' => 'semi-yearly',
                'name' => 'Semi-Yearly',
                'price_cents' => 192000,
                'currency' => 'USD',
                'duration_months' => 6,
                'initial_respondents' => 500,
                'monthly_respondents' => 300,
                'max_active_studies' => 5,
                'features' => [
                    'All research methodologies',
                    'Advanced reporting dashboard',
                    'Priority email support',
                    '500 initial respondents',
                    '300 additional respondents monthly',
                    'Unused respondents roll over',
                    'Up to 5 active studies',
                ],
                'is_active' => true,
            ],
            [
                'slug' => 'yearly',
                'name' => 'Yearly',
                'price_cents' => 324000,
                'currency' => 'USD',
                'duration_months' => 12,
                'initial_respondents' => 500,
                'monthly_respondents' => 400,
                'max_active_studies' => null,
                'features' => [
                    'All research methodologies',
                    'Advanced reporting dashboard',
                    'Priority support',
                    '500 initial respondents',
                    '400 additional respondents monthly',
                    'Unused respondents roll over',
                    'Unlimited active studies',
                    'Annual billing discount',
                ],
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
