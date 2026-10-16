<?php

namespace App\Services;

use App\Models\ResearchRequest;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class RespondentAllocationService
{
    public function submit(User $user, array $data): ResearchRequest
    {
        // Public IDs are random. In the extremely unlikely event of
        // a collision, retry the entire transaction with a new ID.
        for ($attempt = 0; $attempt < 5; $attempt++) {
            try {
                return $this->createStudy($user, $data);
            } catch (QueryException $exception) {
                if (!$this->isPublicIdCollision($exception)) {
                    throw $exception;
                }
            }
        }

        throw new RuntimeException(
            'Unable to generate a unique request ID. Please try again.'
        );
    }

    private function createStudy(User $user, array $data): ResearchRequest
    {
        return DB::transaction(function () use ($user, $data) {

            /*
            |--------------------------------------------------------------------------
            | Lock User Row
            |--------------------------------------------------------------------------
            |
            | Serializes study submissions for this user.
            | Protects respondent balance and sequential study numbering.
            |
            */

            User::query()
                ->whereKey($user->id)
                ->lockForUpdate()
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Find Active Subscription
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | Validate Sample Size
            |--------------------------------------------------------------------------
            */

            $requested = (int) ($data['sample_size'] ?? 0);

            if ($requested < 1 || $requested > 1000) {
                throw ValidationException::withMessages([
                    'sample_size' => 'Invalid sample size.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Check Remaining Respondents
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | Generate User Study Number
            |--------------------------------------------------------------------------
            |
            | User A: #001, #002, #003
            | User B: #001, #002
            |
            */

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

            /*
            |--------------------------------------------------------------------------
            | Generate Public Request ID
            |--------------------------------------------------------------------------
            |
            | Example: REQ-K7M4P2
            |
            | The database UNIQUE constraint is the final guarantee
            | that two studies cannot share the same public ID.
            |
            */

            $publicRequestId = $this->generatePublicRequestId();

            /*
            |--------------------------------------------------------------------------
            | Remove Server-Controlled Fields
            |--------------------------------------------------------------------------
            */

            unset(
                $data['id'],
                $data['user_id'],
                $data['user_request_number'],
                $data['public_request_id'],
                $data['subscription_id'],
                $data['respondents_reserved'],
                $data['status'],
                $data['rejection_reason']
            );

            /*
            |--------------------------------------------------------------------------
            | Create Research Request
            |--------------------------------------------------------------------------
            */

            $study = ResearchRequest::query()->create([
                ...$data,
                'user_id' => $user->id,
                'user_request_number' => $nextNumber,
                'public_request_id' => $publicRequestId,
                'subscription_id' => $subscription->id,
                'respondents_reserved' => $requested,
                'status' => 'submitted',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Reserve Respondents
            |--------------------------------------------------------------------------
            |
            | The study creation and respondent reservation are
            | performed in the same database transaction.
            |
            */

            $subscription->increment(
                'respondents_used',
                $requested
            );

            return $study;

        }, 3);
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Secure Public Request ID
    |--------------------------------------------------------------------------
    */

    private function generatePublicRequestId(): string
    {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        $maxIndex = strlen($characters) - 1;

        $code = '';

        for ($i = 0; $i < 6; $i++) {
            $code .= $characters[random_int(0, $maxIndex)];
        }

        return 'REQ-' . $code;
    }

    /*
    |--------------------------------------------------------------------------
    | Detect Public Request ID Collision
    |--------------------------------------------------------------------------
    |
    | Retry only when the database reports a duplicate value
    | for the public_request_id unique index.
    |
    */

    private function isPublicIdCollision(QueryException $exception): bool
    {
        $sqlState = (string) $exception->getCode();

        $errorMessage = strtolower($exception->getMessage());

        return $sqlState === '23000'
            && str_contains(
                $errorMessage,
                'research_requests_public_request_id_unique'
            );
    }
}
