<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'price_cents',
        'currency',
        'duration_months',
        'initial_respondents',
        'monthly_respondents',
        'max_active_studies',
        'features',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price_cents' => 'integer',
            'duration_months' => 'integer',
            'initial_respondents' => 'integer',
            'monthly_respondents' => 'integer',
            'max_active_studies' => 'integer',
            'features' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
