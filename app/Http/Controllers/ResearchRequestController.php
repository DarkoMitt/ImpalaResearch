<?php

namespace App\Http\Controllers;

use App\Services\RespondentAllocationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ResearchRequestController extends Controller
{
    public function store(
        Request $request,
        RespondentAllocationService $allocation
    ): RedirectResponse {
        $validated = $request->validate([
            'goal' => [
                'required',
                'string',
                Rule::in([
                    'Product Research',
                    'Pricing Research',
                    'Concept Research',
                    'Brand Research',
                    'UX Research',
                    'Not sure',
                ]),
            ],

            'methods' => ['required', 'array', 'min:1', 'max:5'],

            'methods.*' => [
                'required',
                'string',
                'distinct',
                Rule::in([
                    'Eye Tracking',
                    'Clicks',
                    'Emotion Measurement',
                    'Surveys',
                    'RTM (Reaction Time Measurement)',
                ]),
            ],

            'country' => [
                'required',
                Rule::in([
                    'North Macedonia',
                    'United States',
                    'Germany',
                    'United Kingdom',
                ]),
            ],

            'audience' => ['required', 'string', 'max:1000'],

            'sample_size' => [
                'required',
                'integer',
                'between:1,1000',
            ],

            'timeline' => [
                'nullable',
                Rule::in([
                    '1-2 weeks',
                    '2-4 weeks',
                    '1-2 months',
                ]),
            ],

            'full_name' => ['required', 'string', 'max:255'],

            'email' => ['required', 'email', 'max:255'],

            'phone' => [
                'required',
                'string',
                'max:30',
                'regex:/^\+?[0-9\s().-]{9,30}$/',
            ],

            'company' => ['nullable', 'string', 'max:255'],

            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $data = [
            'goal' => $validated['goal'],
            'methods' => $validated['methods'],
            'country' => $validated['country'],
            'audience' => $validated['audience'],
            'sample_size' => $validated['sample_size'],
            'timeline' => $validated['timeline'] ?? null,
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'company' => $validated['company'] ?? '',
            'notes' => $validated['notes'] ?? null,
        ];

        /*
        |--------------------------------------------------------------------------
        | Guest submission
        |--------------------------------------------------------------------------
        */

        if (!$request->user()) {
            $request->session()->put(
                'pending_research_request',
                $data
            );

            return redirect()
                ->route('login')
                ->with(
                    'status',
                    'Your study is saved temporarily. Log in or register to submit it automatically.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Authenticated submission
        |--------------------------------------------------------------------------
        */

        $user = $request->user();

        $data['email'] = $user->email;

        $allocation->submit($user, $data);

        return redirect()
            ->route(
                $user->role === 'admin'
                    ? 'admin.dashboard'
                    : 'dashboard'
            )
            ->with(
                'success',
                'Your research request has been submitted successfully!'
            );
    }
}
