<?php

namespace App\Http\Controllers;

use App\Models\ResearchRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserResearchRequestController extends Controller
{
    public function index(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Search & Filters
        |--------------------------------------------------------------------------
        */

        $filters = $request->validate([
            'search' => [
                'nullable',
                'string',
                'max:255',
            ],
            'status' => [
                'nullable',
                Rule::in([
                    'submitted',
                    'in_progress',
                    'review',
                    'completed',
                    'rejected',
                ]),
            ],
        ]);

        $search = trim($filters['search'] ?? '');
        $status = $filters['status'] ?? '';

        /*
        |--------------------------------------------------------------------------
        | Only Current User's Research Requests
        |--------------------------------------------------------------------------
        */

        $userId = $request->user()->id;

        $query = ResearchRequest::query()
            ->where('user_id', $userId);

        /*
        |--------------------------------------------------------------------------
        | Search Studies
        |--------------------------------------------------------------------------
        |
        | Supported searches:
        |
        | Study #001    => user_request_number = 1
        | Study 001     => user_request_number = 1
        | #001          => user_request_number = 1
        | 001           => user_request_number = 1
        | 1             => user_request_number = 1
        |
        | REQ-K7M4P2    => public_request_id
        | K7M4P2        => public_request_id
        |
        | Also supports research goal and country.
        |
        | All searches remain scoped to the logged-in user.
        |
        */

        if ($search !== '') {

            $query->where(function ($q) use ($search) {

                /*
                |--------------------------------------------------------------------------
                | Search by Research Goal or Country
                |--------------------------------------------------------------------------
                */

                $q->where('goal', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%");

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
                    $q->orWhere(
                        'public_request_id',
                        $normalizedPublicId
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Search by Personal Study Number
                |--------------------------------------------------------------------------
                |
                | Examples:
                |
                | Study #001
                | Study 001
                | #001
                | 001
                | 1
                |
                */

                if (preg_match(
                    '/^(?:study\s*)?#?0*([0-9]+)$/i',
                    $search,
                    $matches
                )) {

                    $studyNumber = (int) $matches[1];

                    if (
                        $studyNumber > 0
                        && $studyNumber <= 4294967295
                    ) {
                        $q->orWhere(
                            'user_request_number',
                            $studyNumber
                        );
                    }
                }
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter by Status
        |--------------------------------------------------------------------------
        */

        if ($status !== '') {
            $query->where('status', $status);
        }

        /*
        |--------------------------------------------------------------------------
        | Return Paginated Studies
        |--------------------------------------------------------------------------
        */

        return view('pages.user-research-requests', [
            'studies' => $query
                ->latest()
                ->paginate(10)
                ->withQueryString(),

            'search' => $search,

            'selectedStatus' => $status,

            'totalStudies' => ResearchRequest::query()
                ->where('user_id', $userId)
                ->count(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Show Single Research Request
    |--------------------------------------------------------------------------
    */

    public function show(
        Request $request,
        ResearchRequest $researchRequest
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Ownership Protection
        |--------------------------------------------------------------------------
        |
        | Users can only view their own research requests.
        | Internal database IDs remain unchanged for route binding.
        |
        */

        abort_unless(
            (int) $researchRequest->user_id === (int) $request->user()->id,
            404
        );

        return view('pages.user-study-details', [
            'study' => $researchRequest,
        ]);
    }
}
