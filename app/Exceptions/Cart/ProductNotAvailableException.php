<?php

namespace App\Exceptions\Cart;

use Exception;

class ProductNotAvailableException extends Exception
{
    public function __construct(string $message = 'Product is not available for purchase')
    {
        parent::__construct($message);
    }

    public function render()
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], 422);
    }
}
