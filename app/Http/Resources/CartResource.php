<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'items_count' => $this->items_count,
            'subtotal' => (float) $this->subtotal,
            'items' => CartItemResource::collection($this->whenLoaded('items')),
            'expires_at' => $this->expires_at?->toIso8601String(),
            'is_guest' => $this->belongsToGuest(),
        ];
    }
}
