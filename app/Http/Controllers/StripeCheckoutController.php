<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use Throwable;

class StripeCheckoutController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'plan' => ['required', 'string', 'max:100'],
        ]);

        $plan = SubscriptionPlan::query()
            ->where('slug', $validated['plan'])
            ->where('is_active', true)
            ->firstOrFail();

        $user = $request->user();

        if ($user->activeSubscription()) {
            return redirect()
                ->route('dashboard')
                ->with(
                    'error',
                    'You already have an active subscription.'
                );
        }

        $secret = config('services.stripe.secret');

        if (
            !is_string($secret) ||
            !str_starts_with($secret, 'sk_test_')
        ) {
            return redirect()
                ->route('payment', ['plan' => $plan->slug])
                ->with(
                    'error',
                    'Stripe test checkout is not configured yet.'
                );
        }

        $intervalCount = (int) $plan->duration_months;

        if (!in_array($intervalCount, [1, 6, 12], true)) {
            return redirect()
                ->route('pricing')
                ->with(
                    'error',
                    'The selected billing interval is not supported.'
                );
        }

        if (
            (int) $plan->price_cents <= 0 ||
            strtoupper($plan->currency) !== 'USD'
        ) {
            return redirect()
                ->route('pricing')
                ->with(
                    'error',
                    'The selected plan has an invalid billing configuration.'
                );
        }

        try {
            $stripe = new StripeClient($secret);

            $session = $stripe->checkout->sessions->create([
                'mode' => 'subscription',

                'payment_method_types' => ['card'],

                'customer_email' => $user->email,

                'client_reference_id' => (string) $user->id,

                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => strtolower($plan->currency),

                            'unit_amount' => (int) $plan->price_cents,

                            'recurring' => [
                                'interval' => 'month',
                                'interval_count' => $intervalCount,
                            ],

                            'product_data' => [
                                'name' => $plan->name . ' Plan',

                                'description' =>
                                    'Impala Research subscription billed every ' .
                                    $intervalCount .
                                    ($intervalCount === 1
                                        ? ' month'
                                        : ' months'),
                            ],
                        ],

                        'quantity' => 1,
                    ],
                ],

                'metadata' => [
                    'user_id' => (string) $user->id,
                    'plan_id' => (string) $plan->id,
                    'plan_slug' => $plan->slug,
                ],

                'subscription_data' => [
                    'metadata' => [
                        'user_id' => (string) $user->id,
                        'plan_id' => (string) $plan->id,
                        'plan_slug' => $plan->slug,
                    ],
                ],

                'success_url' => route('dashboard') .
                    '?checkout=processing&session_id={CHECKOUT_SESSION_ID}',

                'cancel_url' => route('payment', [
                    'plan' => $plan->slug,
                    'checkout' => 'cancelled',
                ]),
            ]);

            if (empty($session->url)) {
                return redirect()
                    ->route('payment', ['plan' => $plan->slug])
                    ->with(
                        'error',
                        'Stripe did not return a checkout URL.'
                    );
            }

            return redirect()->away($session->url);

        } catch (ApiErrorException $exception) {
            report($exception);

            return redirect()
                ->route('payment', ['plan' => $plan->slug])
                ->with(
                    'error',
                    'Unable to start secure checkout. Please try again later.'
                );

        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('payment', ['plan' => $plan->slug])
                ->with(
                    'error',
                    'Checkout is temporarily unavailable.'
                );
        }
    }
}
