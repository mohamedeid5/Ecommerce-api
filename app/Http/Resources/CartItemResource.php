<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'unit_price' => (float) $this->unit_price,
            'total' => (float) $this->total,
            'product' => $this->whenLoaded('product', fn() => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'sku' => $this->product->sku,
                'current_price' => (float) ($this->product->sale_price ?? $this->product->price),
                'stock' => $this->product->stock,
                'image' => $this->product->primaryImage?->path,
            ]),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
