<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResearchRequest;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        /*
        |--------------------------------------------------------------------------
        | User Statistics
        |--------------------------------------------------------------------------
        */

        $totalUsers = User::count();

        $individualAccounts = User::where(
            'account_type',
            'individual'
        )->count();

        $companyAccounts = User::where(
            'account_type',
            'company'
        )->count();

        $adminAccounts = User::where(
            'role',
            'admin'
        )->count();

        $newUsersThisMonth = User::where(
            'created_at',
            '>=',
            now()->startOfMonth()
        )->count();

        $recentUsers = User::query()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        $individualPercentage = $totalUsers > 0
            ? round(($individualAccounts / $totalUsers) * 100)
            : 0;

        $companyPercentage = $totalUsers > 0
            ? round(($companyAccounts / $totalUsers) * 100)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Research Request Statistics
        |--------------------------------------------------------------------------
        */

        $researchStats = ResearchRequest::query()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw(
                "SUM(CASE WHEN status = 'submitted' THEN 1 ELSE 0 END) as submitted"
            )
            ->selectRaw(
                "SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress"
            )
            ->selectRaw(
                "SUM(CASE WHEN status = 'review' THEN 1 ELSE 0 END) as review"
            )
            ->selectRaw(
                "SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed"
            )
            ->selectRaw(
                "SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected"
            )
            ->first();

        $totalResearchRequests = (int) ($researchStats->total ?? 0);

        $submittedResearchRequests = (int) ($researchStats->submitted ?? 0);

        $inProgressResearchRequests = (int) ($researchStats->in_progress ?? 0);

        $reviewResearchRequests = (int) ($researchStats->review ?? 0);

        $completedResearchRequests = (int) ($researchStats->completed ?? 0);

        $rejectedResearchRequests = (int) ($researchStats->rejected ?? 0);

        /*
        |--------------------------------------------------------------------------
        | Recent Research Requests
        |--------------------------------------------------------------------------
        |
        | Each request retains:
        | - Internal database ID for routing
        | - Personal study number for its user
        | - Globally unique public request ID
        |
        */

        $recentResearchRequests = ResearchRequest::query()
            ->with('user')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Return Dashboard View
        |--------------------------------------------------------------------------
        */

        return view('pages.admin-dashboard', [
            'totalUsers' => $totalUsers,
            'individualAccounts' => $individualAccounts,
            'companyAccounts' => $companyAccounts,
            'adminAccounts' => $adminAccounts,
            'newUsersThisMonth' => $newUsersThisMonth,
            'recentUsers' => $recentUsers,
            'individualPercentage' => $individualPercentage,
            'companyPercentage' => $companyPercentage,

            'totalResearchRequests' => $totalResearchRequests,
            'submittedResearchRequests' => $submittedResearchRequests,
            'inProgressResearchRequests' => $inProgressResearchRequests,
            'reviewResearchRequests' => $reviewResearchRequests,
            'completedResearchRequests' => $completedResearchRequests,
            'rejectedResearchRequests' => $rejectedResearchRequests,
            'recentResearchRequests' => $recentResearchRequests,
        ]);
    }
}
