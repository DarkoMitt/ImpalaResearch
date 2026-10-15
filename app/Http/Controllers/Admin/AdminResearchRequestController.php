<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResearchRequest;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminResearchRequestController extends Controller
{
    private const STATUSES = [
        'submitted',
        'in_progress',
        'review',
        'completed',
        'rejected',
    ];

    private const TRANSITIONS = [
        'submitted' => ['in_progress', 'rejected'],
        'in_progress' => ['review'],
        'review' => ['completed'],
        'completed' => [],
        'rejected' => [],
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
            'rejection_reason' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $nextStatus = $validated['status'];

        if (
            $nextStatus !== 'rejected' &&
            filled($validated['rejection_reason'] ?? null)
        ) {
            throw ValidationException::withMessages([
                'rejection_reason' =>
                    'A rejection reason can only be provided when rejecting a study.',
            ]);
        }

        DB::transaction(function () use (
            $researchRequest,
            $nextStatus,
            $validated
        ) {
            $study = ResearchRequest::query()
                ->whereKey($researchRequest->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!in_array(
                $nextStatus,
                self::TRANSITIONS[$study->status] ?? [],
                true
            )) {
                throw ValidationException::withMessages([
                    'status' =>
                        'This status transition is not allowed. Refresh the page and try again.',
                ]);
            }

            if ($nextStatus === 'rejected') {
                $reserved = (int) $study->respondents_reserved;

                if ($reserved > 0) {
                    if (!$study->subscription_id) {
                        throw ValidationException::withMessages([
                            'status' =>
                                'Cannot refund this study: its original subscription is missing.',
                        ]);
                    }

                    $subscription = Subscription::query()
                        ->whereKey($study->subscription_id)
                        ->lockForUpdate()
                        ->first();

                    if (
                        !$subscription ||
                        (int) $subscription->user_id !== (int) $study->user_id
                    ) {
                        throw ValidationException::withMessages([
                            'status' =>
                                'Cannot refund this study: subscription ownership could not be verified.',
                        ]);
                    }

                    if ((int) $subscription->respondents_used < $reserved) {
                        throw ValidationException::withMessages([
                            'status' =>
                                'Cannot refund this study: respondent balance is inconsistent.',
                        ]);
                    }

                    $subscription->decrement(
                        'respondents_used',
                        $reserved
                    );
                }
            }

            $updates = [
                'status' => $nextStatus,
            ];

            if ($nextStatus === 'rejected') {
                $updates['rejection_reason'] = trim(
                    $validated['rejection_reason'] ?? ''
                ) ?: null;
            }

            $study->update($updates);
        }, 3);

        return redirect()
            ->route('admin.research-requests.show', $researchRequest)
            ->with(
                'success',
                $nextStatus === 'rejected'
                    ? 'Study rejected. Reserved respondents were returned where applicable.'
                    : 'Research request status updated successfully.'
            );
    }
}
