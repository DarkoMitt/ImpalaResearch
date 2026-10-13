<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $slug = $request->query('plan', 'semi-yearly');

        $plan = SubscriptionPlan::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $totalRespondents = $plan->initial_respondents
            + max(0, $plan->duration_months - 1)
                * $plan->monthly_respondents;

        return view('pages.payment', [
            'plan' => $plan,
            'totalRespondents' => $totalRespondents,
        ]);
    }
}
