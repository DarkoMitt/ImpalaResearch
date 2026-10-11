<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
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

        $query = User::query();

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

        $totalUsers = User::count();

        return view('pages.admin-users', [
            'users' => $users,
            'totalUsers' => $totalUsers,
            'search' => $search,
            'filter' => $filter,
        ]);
    }
}
