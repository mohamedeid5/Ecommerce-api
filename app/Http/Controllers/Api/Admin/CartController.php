<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddItemToCartRequest;
use App\Http\Requests\Cart\UpdateCartItemRequest;
use App\Http\Resources\CartItemResource;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Services\Cart\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        private CartService $cartService
    ) {}

    public function show(Request $request): CartResource
    {
        $cart = $request->attributes->get('cart');
        $cart = $this->cartService->getCart($cart);

        return new CartResource($cart);
    }

    public function addItem(AddItemToCartRequest $request): JsonResponse
    {
        $cart = $request->attributes->get('cart');

        $item = $this->cartService->addItem(
            $cart,
            $request->integer('product_id'),
            $request->integer('quantity'),
        );

        return response()->json([
            'message' => 'Item added to cart',
            'data' => new CartItemResource($item),
        ], 201);
    }

    public function updateItem(UpdateCartItemRequest $request, CartItem $item): JsonResponse
    {
        $this->authorizeItem($request, $item);

        $updated = $this->cartService->updateItem($item, $request->integer('quantity'));

        return response()->json([
            'message' => 'Cart item updated',
            'data' => new CartItemResource($updated),
        ]);
    }

    public function removeItem(Request $request, CartItem $item): JsonResponse
    {
        $this->authorizeItem($request, $item);

        $this->cartService->removeItem($item);

        return response()->json([
            'message' => 'Item removed from cart',
        ]);
    }

    public function clear(Request $request): JsonResponse
    {
        $cart = $request->attributes->get('cart');
        $this->cartService->clearCart($cart);

        return response()->json([
            'message' => 'Cart cleared',
        ]);
    }

    private function authorizeItem(Request $request, CartItem $item): void
    {
        $cart = $request->attributes->get('cart');

        abort_if(
            $item->cart_id !== $cart->id,
            403,
            'This item does not belong to your cart.'
        );
    }
}
