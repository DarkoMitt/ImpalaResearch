<?php

namespace App\Http\Controllers;

use App\Models\ContactRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $email = strtolower(trim((string) $request->input('email', '')));
        $phone = trim((string) $request->input('phone', ''));

        $request->merge([
            'email' => $email,
            'phone' => $phone,
        ]);

        $countries = config('contact_countries', []);

        $validated = $request->validate(
            [
                'request_type' => [
                    'required',
                    Rule::in(['demo', 'general']),
                ],

                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'email' => [
                    'required',
                    'string',
                    'email:rfc,dns',
                    'max:255',
                    'regex:/^[A-Za-z0-9._%+\-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/',
                ],

                'phone' => [
                    'required',
                    'string',
                    'regex:/^\+?[0-9]{7,15}$/',
                ],

                'country' => [
                    'required',
                    'string',
                    Rule::in($countries),
                ],

                'company' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'message' => [
                    'required',
                    'string',
                    'max:5000',
                ],
            ],
            [
                'email.email' => 'Please enter a valid email address with an existing email domain.',
                'email.regex' => 'Please enter a valid email address, for example name@example.com.',
                'phone.regex' => 'Phone number must contain 7 to 15 digits, with an optional + at the beginning.',
                'country.in' => 'Please select a valid country from the list.',
            ]
        );

        ContactRequest::create([
            'request_type' => $validated['request_type'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'country' => $validated['country'],
            'company' => $validated['company'] ?? null,
            'message' => $validated['message'],
            'status' => 'new',
        ]);

        return redirect()
            ->route('contact')
            ->with(
                'success',
                'Your request has been submitted successfully. Our team will contact you soon.'
            );
    }
}
