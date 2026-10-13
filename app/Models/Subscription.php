<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'status',
        'is_demo',
        'stripe_customer_id',
        'stripe_subscription_id',
        'price_paid_cents',
        'starts_at',
        'ends_at',
        'cancel_at',
        'cancelled_at',
        'respondents_granted',
        'respondents_used',
        'months_granted',
        'next_allowance_at',
    ];

    protected function casts(): array
    {
        return [
            'is_demo' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'cancel_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'next_allowance_at' => 'datetime',
            'price_paid_cents' => 'integer',
            'respondents_granted' => 'integer',
            'respondents_used' => 'integer',
            'months_granted' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(
            SubscriptionPlan::class,
            'subscription_plan_id'
        );
    }

    public function getRemainingRespondentsAttribute(): int
    {
        return max(
            0,
            $this->respondents_granted - $this->respondents_used
        );
    }
}
