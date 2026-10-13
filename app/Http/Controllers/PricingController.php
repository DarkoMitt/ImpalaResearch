<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\View\View;

class PricingController extends Controller
{
    public function index(): View
    {
        $plans = SubscriptionPlan::query()
            ->where('is_active', true)
            ->get()
            ->sortBy(function ($plan) {
                return match ($plan->slug) {
                    'monthly' => 1,
                    'semi-yearly' => 2,
                    'yearly' => 3,
                    default => 4,
                };
            })
            ->values();

        return view('pages.pricing', [
            'plans' => $plans,
        ]);
    }
}
