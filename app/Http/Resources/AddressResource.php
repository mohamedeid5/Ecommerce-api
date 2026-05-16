<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'street' => $this->street,
            'building' => $this->building,
            'apartment' => $this->apartment,
            'city' => $this->city,
            'governorate' => $this->governorate,
            'postal_code' => $this->postal_code,
            'notes' => $this->notes,
            'is_default' => $this->is_default,
            'formatted_address' => $this->formatAddress(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }

    /**
     * Build a human-readable address string
     */
    private function formatAddress(): string
    {
        $parts = array_filter([
            $this->apartment ? "Apt {$this->apartment}" : null,
            $this->building ? "Bldg {$this->building}" : null,
            $this->street,
            $this->city,
            $this->governorate,
            $this->postal_code,
        ]);

        return implode(', ', $parts);
    }
}
