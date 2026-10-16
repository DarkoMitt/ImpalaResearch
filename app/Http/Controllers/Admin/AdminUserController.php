<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResearchRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Admin Users List
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $filter = (string) $request->query('filter', 'all');

        $allowedFilters = [
            'all',
            'individual',
            'company',
            'admin',
            'user',
        ];

        if (!in_array($filter, $allowedFilters, true)) {
            $filter = 'all';
        }

        $query = User::query()
            ->withCount([
                'researchRequests as studies_count',
            ])
            ->with([
                'subscriptions' => function ($subscriptionQuery) {
                    $subscriptionQuery
                        ->with('plan')
                        ->orderByDesc('starts_at')
                        ->orderByDesc('id');
                },
            ]);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('company_name', 'like', '%' . $search . '%');
            });
        }

        if (in_array($filter, ['individual', 'company'], true)) {
            $query->where('account_type', $filter);
        }

        if (in_array($filter, ['admin', 'user'], true)) {
            $query->where('role', $filter);
        }

        $users = $query
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $now = now();

        $users->getCollection()->each(function ($user) use ($now) {

            $activeSubscription = $user->subscriptions
                ->first(function ($subscription) use ($now) {
                    return $subscription->status === 'active'
                        && $subscription->starts_at !== null
                        && $subscription->starts_at->lte($now)
                        && $subscription->ends_at !== null
                        && $subscription->ends_at->gt($now);
                });

            $latestSubscription = $user->subscriptions->first();

            $displaySubscription = $activeSubscription
                ?? $latestSubscription;

            $subscriptionStatus = 'none';

            if ($activeSubscription) {
                $subscriptionStatus = 'active';
            } elseif ($latestSubscription) {
                if (
                    $latestSubscription->ends_at !== null
                    && $latestSubscription->ends_at->lte($now)
                ) {
                    $subscriptionStatus = 'expired';
                } elseif (
                    in_array(
                        $latestSubscription->status,
                        ['cancelled', 'canceled'],
                        true
                    )
                ) {
                    $subscriptionStatus = 'cancelled';
                } else {
                    $subscriptionStatus = $latestSubscription->status;
                }
            }

            $user->admin_subscription = $displaySubscription;
            $user->admin_subscription_status = $subscriptionStatus;

            $user->admin_respondents_granted = $activeSubscription
                ? (int) $activeSubscription->respondents_granted
                : 0;

            $user->admin_respondents_used = $activeSubscription
                ? (int) $activeSubscription->respondents_used
                : 0;

            $user->admin_respondents_remaining = $activeSubscription
                ? (int) $activeSubscription->remaining_respondents
                : 0;
        });

        return view('pages.admin-users', [
            'users' => $users,
            'totalUsers' => User::count(),
            'search' => $search,
            'filter' => $filter,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Admin User Details
    |--------------------------------------------------------------------------
    |
    | Read-only overview of:
    | - User account
    | - Current subscription
    | - Respondent allocation
    | - Subscription history
    | - Research studies
    |
    */

    public function show(User $user): View
    {
        $now = now();

        /*
        |--------------------------------------------------------------------------
        | Load Subscriptions
        |--------------------------------------------------------------------------
        */

        $user->load([
            'subscriptions' => function ($query) {
                $query
                    ->with('plan')
                    ->orderByDesc('starts_at')
                    ->orderByDesc('id');
            },
        ]);

        /*
        |--------------------------------------------------------------------------
        | Active Subscription
        |--------------------------------------------------------------------------
        */

        $activeSubscription = $user->subscriptions
            ->first(function ($subscription) use ($now) {
                return $subscription->status === 'active'
                    && $subscription->starts_at !== null
                    && $subscription->starts_at->lte($now)
                    && $subscription->ends_at !== null
                    && $subscription->ends_at->gt($now);
            });

        /*
        |--------------------------------------------------------------------------
        | Latest Subscription
        |--------------------------------------------------------------------------
        */

        $latestSubscription = $user->subscriptions->first();

        $displaySubscription = $activeSubscription
            ?? $latestSubscription;

        /*
        |--------------------------------------------------------------------------
        | Subscription Status
        |--------------------------------------------------------------------------
        */

        $subscriptionStatus = 'none';

        if ($activeSubscription) {
            $subscriptionStatus = 'active';
        } elseif ($latestSubscription) {
            if (
                $latestSubscription->ends_at !== null
                && $latestSubscription->ends_at->lte($now)
            ) {
                $subscriptionStatus = 'expired';
            } elseif (
                in_array(
                    $latestSubscription->status,
                    ['cancelled', 'canceled'],
                    true
                )
            ) {
                $subscriptionStatus = 'cancelled';
            } else {
                $subscriptionStatus = $latestSubscription->status;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Respondent Balance
        |--------------------------------------------------------------------------
        |
        | Only respondents belonging to a currently active
        | subscription are available.
        |
        | Historical subscription values remain unchanged.
        |
        */

        $respondentsGranted = $activeSubscription
            ? (int) $activeSubscription->respondents_granted
            : 0;

        $respondentsUsed = $activeSubscription
            ? (int) $activeSubscription->respondents_used
            : 0;

        $respondentsRemaining = $activeSubscription
            ? (int) $activeSubscription->remaining_respondents
            : 0;

        $respondentUsagePercentage = $respondentsGranted > 0
            ? min(
                100,
                round(
                    ($respondentsUsed / $respondentsGranted) * 100
                )
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Research Study Statistics
        |--------------------------------------------------------------------------
        */

        $studyStats = ResearchRequest::query()
            ->where('user_id', $user->id)
            ->selectRaw('COUNT(*) as total')
            ->selectRaw(
                "SUM(CASE WHEN status = 'submitted' THEN 1 ELSE 0 END) as submitted"
            )
            ->selectRaw(
                "SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress"
            )
            ->selectRaw(
                "SUM(CASE WHEN status = 'review' THEN 1 ELSE 0 END) as review_count"
            )
            ->selectRaw(
                "SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed"
            )
            ->selectRaw(
                "SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected"
            )
            ->first();

        /*
        |--------------------------------------------------------------------------
        | User Research Studies
        |--------------------------------------------------------------------------
        |
        | Paginated separately from subscription history.
        | Most recent studies appear first.
        |
        */

        $researchRequests = ResearchRequest::query()
            ->where('user_id', $user->id)
            ->with('subscription.plan')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(10, ['*'], 'studies_page')
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Subscription History
        |--------------------------------------------------------------------------
        */

        $subscriptionHistory = $user->subscriptions;

        /*
        |--------------------------------------------------------------------------
        | Return User Details View
        |--------------------------------------------------------------------------
        */

        return view('pages.admin-user-show', [
            'user' => $user,

            'activeSubscription' => $activeSubscription,
            'displaySubscription' => $displaySubscription,
            'subscriptionStatus' => $subscriptionStatus,

            'respondentsGranted' => $respondentsGranted,
            'respondentsUsed' => $respondentsUsed,
            'respondentsRemaining' => $respondentsRemaining,
            'respondentUsagePercentage' => $respondentUsagePercentage,

            'subscriptionHistory' => $subscriptionHistory,

            'researchRequests' => $researchRequests,
            'studyStats' => $studyStats,
        ]);
    }
}
