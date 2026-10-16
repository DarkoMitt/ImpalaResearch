<?php

namespace App\Http\Controllers;

use App\Models\ContactRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminContactRequestController extends Controller
{
    /**
     * Display contact and demo requests.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $type = (string) $request->query('type', 'all');
        $status = (string) $request->query('status', 'all');

        $allowedTypes = ['all', 'demo', 'general'];
        $allowedStatuses = ['all', 'new', 'contacted', 'closed'];

        if (!in_array($type, $allowedTypes, true)) {
            $type = 'all';
        }

        if (!in_array($status, $allowedStatuses, true)) {
            $status = 'all';
        }

        $query = ContactRequest::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('company', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        if ($type !== 'all') {
            $query->where('request_type', $type);
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $contactRequests = $query
            ->orderByRaw("CASE WHEN status = 'new' THEN 0 ELSE 1 END")
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totalRequests = ContactRequest::count();

        $newRequests = ContactRequest::where('status', 'new')->count();

        $contactedRequests = ContactRequest::where('status', 'contacted')->count();

        $closedRequests = ContactRequest::where('status', 'closed')->count();

        $demoRequests = ContactRequest::where('request_type', 'demo')->count();

        $generalRequests = ContactRequest::where('request_type', 'general')->count();

        return view('pages.admin-contact-requests', compact(
            'contactRequests',
            'totalRequests',
            'newRequests',
            'contactedRequests',
            'closedRequests',
            'demoRequests',
            'generalRequests',
            'search',
            'type',
            'status'
        ));
    }

    /**
     * Display a single contact request.
     */
    public function show(ContactRequest $contactRequest): View
    {
        return view('pages.admin-contact-request-show', compact(
            'contactRequest'
        ));
    }

    /**
     * Update request status and admin notes.
     */
    public function update(
        Request $request,
        ContactRequest $contactRequest
    ): RedirectResponse {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in(['new', 'contacted', 'closed']),
            ],

            'admin_notes' => [
                'nullable',
                'string',
                'max:10000',
            ],
        ]);

        $newStatus = $validated['status'];
        $previousStatus = $contactRequest->status;

        $contactRequest->status = $newStatus;

        $contactRequest->admin_notes =
            $validated['admin_notes'] ?? null;

        if ($newStatus === 'contacted') {
            if ($contactRequest->contacted_at === null) {
                $contactRequest->contacted_at = now();
            }

            $contactRequest->closed_at = null;
        }

        if ($newStatus === 'closed') {
            if ($contactRequest->closed_at === null) {
                $contactRequest->closed_at = now();
            }
        }

        if ($newStatus === 'new') {
            $contactRequest->contacted_at = null;
            $contactRequest->closed_at = null;
        }

        if (
            $previousStatus === 'closed' &&
            $newStatus !== 'closed'
        ) {
            $contactRequest->closed_at = null;
        }

        $contactRequest->save();

        return redirect()
            ->back()
            ->with(
                'success',
                'Contact request updated successfully.'
            );
    }
}
