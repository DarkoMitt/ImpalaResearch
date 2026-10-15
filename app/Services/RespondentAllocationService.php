<?php

namespace App\Services;

use App\Models\ResearchRequest;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RespondentAllocationService
{
    public function submit(User $user, array $data): ResearchRequest
    {
        return DB::transaction(function () use ($user, $data) {

            // Serialize submissions for this user.
            // This protects both the balance and request numbering.
            User::query()
                ->whereKey($user->id)
                ->lockForUpdate()
                ->firstOrFail();

            $subscription = Subscription::query()
                ->where('user_id', $user->id)
                ->where('status', 'active')
                ->where('starts_at', '<=', now())
                ->where('ends_at', '>', now())
                ->orderByDesc('starts_at')
                ->lockForUpdate()
                ->first();

            if (!$subscription) {
                throw ValidationException::withMessages([
                    'subscription' =>
                        'An active subscription is required to submit a study.',
                ]);
            }

            $requested = (int) ($data['sample_size'] ?? 0);

            if ($requested < 1 || $requested > 1000) {
                throw ValidationException::withMessages([
                    'sample_size' => 'Invalid sample size.',
                ]);
            }

            $remaining = max(
                0,
                (int) $subscription->respondents_granted
                    - (int) $subscription->respondents_used
            );

            if ($requested > $remaining) {
                throw ValidationException::withMessages([
                    'sample_size' =>
                        "Insufficient respondents. You have {$remaining} remaining.",
                ]);
            }

            // This query is safe for submissions through this service
            // because the user's row is locked above.
            $lastNumber = ResearchRequest::query()
                ->where('user_id', $user->id)
                ->max('user_request_number');

            $nextNumber = ((int) $lastNumber) + 1;

            if ($nextNumber > 4294967295) {
                throw ValidationException::withMessages([
                    'subscription' =>
                        'The request numbering limit has been reached.',
                ]);
            }

            // Never accept server-controlled fields from form data.
            unset(
                $data['id'],
                $data['user_id'],
                $data['user_request_number'],
                $data['subscription_id'],
                $data['respondents_reserved'],
                $data['status'],
                $data['rejection_reason']
            );

            $study = ResearchRequest::query()->create([
                ...$data,
                'user_id' => $user->id,
                'user_request_number' => $nextNumber,
                'subscription_id' => $subscription->id,
                'respondents_reserved' => $requested,
                'status' => 'submitted',
            ]);

            $subscription->increment(
                'respondents_used',
                $requested
            );

            return $study;
        }, 3);
    }
}
