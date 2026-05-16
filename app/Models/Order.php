<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'subtotal',
        'shipping_fee',
        'tax_amount',
        'tax_rate',
        'total',
        'shipping_full_name',
        'shipping_phone',
        'shipping_street',
        'shipping_building',
        'shipping_apartment',
        'shipping_city',
        'shipping_governorate',
        'shipping_postal_code',
        'shipping_notes',
        'customer_notes',
        'placed_at',
        'paid_at',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'subtotal' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'tax_rate' => 'decimal:4',
        'total' => 'decimal:2',
        'placed_at' => 'datetime',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function latestPayment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function successfulPayment(): HasOne
    {
        return $this->hasOne(Payment::class)->where('status', 'succeeded');
    }

    // helper methods
    public function getItemsCountAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    public function canBeCancelled(): bool
    {
        return $this->status->isCancellable();
    }

    public function isPaid(): bool
    {
        return $this->status === OrderStatus::PAID
            || $this->status->isSuccessful();
    }
}
