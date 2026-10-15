<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResearchRequest extends Model
{
    protected $fillable = [
        'user_id',
        'user_request_number',
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
