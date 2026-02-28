<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Membership extends Model
{
    protected $fillable = [
        'user_id',
        'tier',
        'billing_cycle',
        'price_paid',
        'price_currency',
        'status',
        'start_date',
        'end_date',
        'payment_method',
        'payment_proof_path',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'price_paid' => 'decimal:2',
    ];

    const STATUS_PENDING   = 'pending';
    const STATUS_ACTIVE    = 'active';
    const STATUS_CANCELLED = 'cancelled';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
