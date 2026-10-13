<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripePaidInvoice extends Model
{
    protected $fillable = [
        'stripe_invoice_id',
        'stripe_subscription_id',
        'subscription_id',
        'amount_paid_cents',
        'period_start',
        'period_end',
    ];

    protected function casts(): array
    {
        return [
            'amount_paid_cents' => 'integer',
            'period_start' => 'datetime',
            'period_end' => 'datetime',
        ];
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
}
