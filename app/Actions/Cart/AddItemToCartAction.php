<?php

namespace App\Actions\Cart;

use App\Exceptions\Cart\InsufficientStockException;
use App\Exceptions\Cart\ProductNotAvailableException;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class AddItemToCartAction
{
     public function execute(Cart $cart, int $productId, int $quantity)
     {
        return DB::transaction(function() use ($cart, $productId, $quantity) {
            $product = Product::lockForUpdate()->findOrFail($productId);

            if($product->status->value !== 'active') {
                throw new ProductNotAvailableException();
            }

            $existingItem = $cart->items()->where('product_id', $productId)->first();

            $totalQuantity = ($existingItem?->quantity ?? 0) + $quantity;

            if($product->stock < $totalQuantity) {
                throw new InsufficientStockException(
                    available: $product->stock,
                    requested: $totalQuantity,
                );
            }

            $unitPrice = $product->sale_price ?? $product->price;

            if($existingItem) {
                $existingItem->update([
                    'quantity' => $totalQuantity,
                    'unit_price' => $unitPrice
                ]);

                return $existingItem->fresh();
            }

            return $cart->items()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
            ]);

        });
     }
}
