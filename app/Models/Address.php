<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'street',
        'building',
        'apartment',
        'city',
        'governorate',
        'postal_code',
        'notes',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the address as a snapshot array for orders
     */
    public function toSnapshot(): array
    {
        return [
            'shipping_full_name' => $this->full_name,
            'shipping_phone' => $this->phone,
            'shipping_street' => $this->street,
            'shipping_building' => $this->building,
            'shipping_apartment' => $this->apartment,
            'shipping_city' => $this->city,
            'shipping_governorate' => $this->governorate,
            'shipping_postal_code' => $this->postal_code,
            'shipping_notes' => $this->notes,
        ];
    }
}
