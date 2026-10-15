<?php

namespace App\Http\Middleware;

use App\Services\RespondentAllocationService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class SubmitPendingResearchRequest
{
    public function __construct(
        private RespondentAllocationService $allocation
    ) {
    }

    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $user = $request->user();

        if (
            !$user ||
            !$request->hasSession() ||
            !$request->session()->has('pending_research_request')
        ) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent automatic retries on every request
        |--------------------------------------------------------------------------
        */

        if (
            $request->session()->get(
                'pending_research_request_attempted',
                false
            )
        ) {
            return $next($request);
        }

        $data = $request->session()->get(
            'pending_research_request'
        );

        if (!is_array($data)) {
            $request->session()->forget([
                'pending_research_request',
                'pending_research_request_attempted',
            ]);

            return $next($request);
        }

        $request->session()->put(
            'pending_research_request_attempted',
            true
        );

        /*
        |--------------------------------------------------------------------------
        | Always use authenticated user's email
        |--------------------------------------------------------------------------
        */

        $data['email'] = $user->email;

        try {
            $this->allocation->submit($user, $data);

            $request->session()->forget([
                'pending_research_request',
                'pending_research_request_attempted',
            ]);

            $request->session()->flash(
                'success',
                'Your research request has been submitted successfully!'
            );

        } catch (ValidationException $exception) {
            $request->session()->flash(
                'error',
                'Your study was saved, but could not be submitted. Please check your subscription and respondent balance.'
            );

        } catch (Throwable $exception) {
            report($exception);

            Log::error(
                'Pending research request submission failed.',
                [
                    'user_id' => $user->id,
                    'exception' => get_class($exception),
                ]
            );

            $request->session()->flash(
                'error',
                'Your saved study could not be submitted. Please try again later.'
            );
        }

        return $next($request);
    }
}
