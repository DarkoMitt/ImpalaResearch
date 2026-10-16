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

    /*
    |--------------------------------------------------------------------------
    | Research Requests List
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => [
                'nullable',
                'string',
                'max:255',
            ],
            'status' => [
                'nullable',
                Rule::in(self::STATUSES),
            ],
        ]);

        $search = trim($filters['search'] ?? '');
        $status = $filters['status'] ?? '';

        /*
        |--------------------------------------------------------------------------
        | All Research Requests
        |--------------------------------------------------------------------------
        |
        | Admin can see requests from every user.
        |
        */

        $query = ResearchRequest::query()
            ->with('user');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        |
        | Supported:
        |
        | REQ-K7M4P2  => Globally unique public request ID
        | K7M4P2      => Public request ID without prefix
        |
        | Study #001  => Personal study number across all users
        | Study 001   => Personal study number across all users
        | #001        => Personal study number across all users
        | 001         => Personal study number across all users
        |
        | Also searches:
        | - Research goal
        | - Submitted full name
        | - Submitted email
        | - Account name
        | - Account email
        |
        | Study numbers are not globally unique.
        | Public request IDs are globally unique.
        |
        */

        if ($search !== '') {
            $query->where(function ($query) use ($search) {

                /*
                |--------------------------------------------------------------------------
                | Search by Research Goal and Contact Information
                |--------------------------------------------------------------------------
                */

                $query
                    ->where('goal', 'like', "%{$search}%")
                    ->orWhere('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });

                /*
                |--------------------------------------------------------------------------
                | Search by Public Request ID
                |--------------------------------------------------------------------------
                */

                $normalizedPublicId = strtoupper($search);

                if (preg_match('/^[A-Z0-9]{6}$/', $normalizedPublicId)) {
                    $normalizedPublicId = 'REQ-' . $normalizedPublicId;
                }

                if (preg_match(
                    '/^REQ-[A-Z0-9]{6}$/',
                    $normalizedPublicId
                )) {
                    $query->orWhere(
                        'public_request_id',
                        $normalizedPublicId
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Search by Personal Study Number
                |--------------------------------------------------------------------------
                |
                | Multiple users may have Study #001.
                | Admin search intentionally returns all matching studies.
                |
                */

                if (preg_match(
                    '/^(?:study\s*)?#?0*([0-9]+)$/i',
                    $search,
                    $matches
                )) {

                    $studyNumber = (int) $matches[1];

                    if (
                        $studyNumber > 0 &&
                        $studyNumber <= 4294967295
                    ) {
                        $query->orWhere(
                            'user_request_number',
                            $studyNumber
                        );
                    }
                }
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($status !== '') {
            $query->where('status', $status);
        }

        /*
        |--------------------------------------------------------------------------
        | Paginated Results and Statistics
        |--------------------------------------------------------------------------
        */

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

    /*
    |--------------------------------------------------------------------------
    | Show Single Research Request
    |--------------------------------------------------------------------------
    */

    public function show(ResearchRequest $researchRequest): View
    {
        $researchRequest->load('user');

        return view('pages.admin-research-request-details', [
            'researchRequest' => $researchRequest,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Research Request Status
    |--------------------------------------------------------------------------
    */

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

        /*
        |--------------------------------------------------------------------------
        | Rejection Reason Validation
        |--------------------------------------------------------------------------
        */

        if (
            $nextStatus !== 'rejected' &&
            filled($validated['rejection_reason'] ?? null)
        ) {
            throw ValidationException::withMessages([
                'rejection_reason' =>
                    'A rejection reason can only be provided when rejecting a study.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Transactional Status Update
        |--------------------------------------------------------------------------
        |
        | Existing status transition and respondent refund logic
        | is preserved.
        |
        */

        DB::transaction(function () use (
            $researchRequest,
            $nextStatus,
            $validated
        ) {
            $study = ResearchRequest::query()
                ->whereKey($researchRequest->id)
                ->lockForUpdate()
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Validate Allowed Transition
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | Refund Reserved Respondents on Rejection
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | Save Updated Status
            |--------------------------------------------------------------------------
            */

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

        /*
        |--------------------------------------------------------------------------
        | Redirect with Success Message
        |--------------------------------------------------------------------------
        */

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
