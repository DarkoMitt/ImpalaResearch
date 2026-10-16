<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactRequest extends Model
{
    protected $fillable = [
        'request_type',
        'name',
        'email',
        'phone',
        'country',
        'company',
        'message',
        'status',
        'admin_notes',
        'contacted_at',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'contacted_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }
}
