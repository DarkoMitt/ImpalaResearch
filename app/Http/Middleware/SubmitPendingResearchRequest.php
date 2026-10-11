<?php

namespace App\Http\Middleware;

use App\Models\ResearchRequest;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubmitPendingResearchRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (
            $user &&
            $request->session()->has('pending_research_request')
        ) {
            $data = $request->session()->get(
                'pending_research_request'
            );

            if (is_array($data)) {

                /*
                |--------------------------------------------------------------------------
                | Use authenticated user's email
                |--------------------------------------------------------------------------
                |
                | Ignore the email entered by the guest before login.
                |
                */

                $data['email'] = $user->email;

                ResearchRequest::create([
                    ...$data,
                    'user_id' => $user->id,
                    'status' => 'submitted',
                ]);

                $request->session()->forget(
                    'pending_research_request'
                );

                $request->session()->flash(
                    'success',
                    'Your research request has been submitted successfully!'
                );
            }
        }

        return $next($request);
    }
}
