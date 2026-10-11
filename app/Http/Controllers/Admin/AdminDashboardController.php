<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
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

        return view('pages.admin-dashboard', [
            'totalUsers' => $totalUsers,
            'individualAccounts' => $individualAccounts,
            'companyAccounts' => $companyAccounts,
            'adminAccounts' => $adminAccounts,
            'newUsersThisMonth' => $newUsersThisMonth,
            'recentUsers' => $recentUsers,
            'individualPercentage' => $individualPercentage,
            'companyPercentage' => $companyPercentage,
        ]);
    }
}
