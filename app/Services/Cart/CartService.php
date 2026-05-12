<?php

namespace App\Services\Cart;

use App\Actions\Cart\AddItemToCartAction;
use App\Actions\Cart\ClearCartAction;
use App\Actions\Cart\RemoveCartItemAction;
use App\Actions\Cart\UpdateCartItemAction;
use App\Models\Cart;
use App\Models\CartItem;

class CartService
{
    public function __construct(
        private AddItemToCartAction $addItemAction,
        private UpdateCartItemAction $updateItemAction,
        private RemoveCartItemAction $removeItemAction,
        private ClearCartAction $clearCartAction,
    ) {}

    public function getCart(Cart $cart): Cart
    {
        return $cart->load(['items.product.primaryImage']);
    }

    public function addItem(Cart $cart, int $productId, int $quantity): CartItem
    {
        $item = $this->addItemAction->execute($cart, $productId, $quantity);

        return $item->load('product.primaryImage');
    }

    public function updateItem(CartItem $cartItem, int $quantity): CartItem
    {
        $item = $this->updateItemAction->execute($cartItem, $quantity);

        return $item->load('product.primaryImage');
    }

    public function removeItem(CartItem $cartItem): bool
    {
        return $this->removeItemAction->execute($cartItem);
    }

    public function clearCart(Cart $cart): void
    {
        $this->clearCartAction->execute($cart);
    }
}
