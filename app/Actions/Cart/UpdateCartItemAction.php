<?php

namespace App\Actions\Cart;

use App\Exceptions\Cart\InsufficientStockException;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class UpdateCartItemAction
{
    public function execute(CartItem $cartItem, int $quantity): CartItem
    {
        return DB::transaction(function () use ($cartItem, $quantity) {
            $product = Product::lockForUpdate()->findOrFail($cartItem->product_id);

            if ($product->stock < $quantity) {
                throw new InsufficientStockException(
                    available: $product->stock,
                    requested: $quantity,
                );
            }

            $cartItem->update([
                'quantity' => $quantity,
                'unit_price' => $product->sale_price ?? $product->price,
            ]);

            return $cartItem->fresh();
        });
    }
}
