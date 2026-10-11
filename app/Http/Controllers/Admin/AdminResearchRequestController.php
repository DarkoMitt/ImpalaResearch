<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResearchRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminResearchRequestController extends Controller
{
    private const STATUSES = [
        'submitted',
        'in_progress',
        'review',
        'completed',
    ];

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'status' => [
                'nullable',
                Rule::in(self::STATUSES),
            ],
        ]);

        $search = trim($filters['search'] ?? '');
        $status = $filters['status'] ?? '';

        $query = ResearchRequest::query()
            ->with('user');

        if ($search !== '') {
            $query->where(function ($query) use ($search) {
                $query
                    ->where('goal', 'like', "%{$search}%")
                    ->orWhere('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });

                if (preg_match('/^(?:REQ-0*)?([0-9]+)$/i', $search, $matches)) {
                    $query->orWhere('id', (int) $matches[1]);
                }
            });
        }

        if ($status !== '') {
            $query->where('status', $status);
        }

        return view('pages.admin-research-requests', [
            'requests' => $query
                ->latest()
                ->paginate(10)
                ->withQueryString(),

            'search' => $search,
            'selectedStatus' => $status,

            'totalRequests' => ResearchRequest::count(),

            'submittedCount' => ResearchRequest::where(
                'status',
                'submitted'
            )->count(),

            'inProgressCount' => ResearchRequest::where(
                'status',
                'in_progress'
            )->count(),

            'reviewCount' => ResearchRequest::where(
                'status',
                'review'
            )->count(),

            'completedCount' => ResearchRequest::where(
                'status',
                'completed'
            )->count(),
        ]);
    }

    public function show(ResearchRequest $researchRequest): View
    {
        $researchRequest->load('user');

        return view('pages.admin-research-request-details', [
            'researchRequest' => $researchRequest,
        ]);
    }

    public function updateStatus(
        Request $request,
        ResearchRequest $researchRequest
    ): RedirectResponse {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in(self::STATUSES),
            ],
        ]);

        $researchRequest->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.research-requests.show', $researchRequest)
            ->with('success', 'Research request status updated successfully.');
    }
}
