<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResearchRequest extends Model
{
    protected $fillable = [
        'user_id',
        'user_request_number',
        'public_request_id',
        'subscription_id',
        'respondents_reserved',
        'goal',
        'methods',
        'country',
        'audience',
        'sample_size',
        'timeline',
        'full_name',
        'email',
        'phone',
        'company',
        'notes',
        'status',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'methods' => 'array',
            'sample_size' => 'integer',
            'respondents_reserved' => 'integer',
            'user_request_number' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    /*
    |--------------------------------------------------------------------------
    | User Study Number
    |--------------------------------------------------------------------------
    |
    | Each user has independent sequential numbering.
    |
    | Example:
    | User A: #001, #002, #003
    | User B: #001, #002
    |
    */

    public function getStudyNumberAttribute(): string
    {
        $number = $this->user_request_number ?? $this->id;

        return '#' . str_pad(
            (string) $number,
            3,
            '0',
            STR_PAD_LEFT
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Public Request ID
    |--------------------------------------------------------------------------
    |
    | Shared between user and administrator.
    | Example: REQ-K7M4P2
    |
    */

    public function getPublicRequestCodeAttribute(): string
    {
        return $this->public_request_id
            ?? 'REQ-' . str_pad(
                (string) $this->id,
                4,
                '0',
                STR_PAD_LEFT
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Legacy User Request Code
    |--------------------------------------------------------------------------
    |
    | Kept for compatibility with existing Blade views.
    |
    */

    public function getUserRequestCodeAttribute(): string
    {
        $number = $this->user_request_number ?? $this->id;

        return 'REQ-' . str_pad(
            (string) $number,
            4,
            '0',
            STR_PAD_LEFT
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Legacy Admin Request Code
    |--------------------------------------------------------------------------
    |
    | Kept for compatibility with the existing admin interface.
    |
    */

    public function getAdminRequestCodeAttribute(): string
    {
        return 'REQ-' . str_pad(
            (string) $this->id,
            4,
            '0',
            STR_PAD_LEFT
        );
    }
}
