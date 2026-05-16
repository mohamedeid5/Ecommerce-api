<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_reference',
        'order_id',
        'provider',
        'provider_payment_id',
        'amount',
        'currency',
        'status',
        'failure_reason',
        'webhook_event_id',
        'initiated_at',
        'completed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'initiated_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isSuccessful(): bool
    {
        return $this->status === 'succeeded';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
