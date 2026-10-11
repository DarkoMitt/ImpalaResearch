<?php

namespace App\Http\Controllers;

use App\Models\ResearchRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class UserResearchRequestController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'status' => [
                'nullable',
                Rule::in([
                    'submitted',
                    'in_progress',
                    'review',
                    'completed',
                ]),
            ],
        ]);

        $search = trim($filters['search'] ?? '');
        $status = $filters['status'] ?? '';

        $query = ResearchRequest::query()
            ->where('user_id', $request->user()->id);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('goal', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%");

                if (preg_match('/^(?:REQ-0*)?([0-9]+)$/i', $search, $matches)) {
                    $q->orWhere('id', (int) $matches[1]);
                }
            });
        }

        if ($status !== '') {
            $query->where('status', $status);
        }

        return view('pages.user-research-requests', [
            'studies' => $query
                ->latest()
                ->paginate(10)
                ->withQueryString(),
            'search' => $search,
            'selectedStatus' => $status,
            'totalStudies' => ResearchRequest::where(
                'user_id',
                $request->user()->id
            )->count(),
        ]);
    }

    public function show(
        Request $request,
        ResearchRequest $researchRequest
    ): View {
        abort_unless(
            $researchRequest->user_id === $request->user()->id,
            404
        );

        return view('pages.user-study-details', [
            'study' => $researchRequest,
        ]);
    }
}
