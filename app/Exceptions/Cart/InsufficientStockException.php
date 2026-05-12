<?php

namespace App\Exceptions\Cart;

use Exception;

class InsufficientStockException extends Exception
{
    public function __construct(
        public int $available,
        public int $requested,
        ?string $message = null
    ) {
        parent::__construct(
            $message ?? "Insufficient stock. Available: {$available}, Requested: {$requested}"
        );
    }

    public function render()
    {
        return response()->json([
            'message' => 'Insufficient stock',
            'errors' => [
                'quantity' => [
                    "Only {$this->available} items available in stock.",
                ],
            ],
            'data' => [
                'available' => $this->available,
                'requested' => $this->requested,
            ],
        ], 422);
    }
}
