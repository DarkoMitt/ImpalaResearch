<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResearchRequest extends Model
{
    protected $fillable = [
        'user_id',
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
    ];

    protected function casts(): array
    {
        return [
            'methods' => 'array',
            'sample_size' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
