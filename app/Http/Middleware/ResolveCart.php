<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Cart\CartResolver;

class ResolveCart
{
     public function __construct(
        private CartResolver $resolver
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $cart = $this->resolver->resolve();
        $request->attributes->set('cart', $cart);

        $response = $next($request);

        if($cart->belongsToGuest()) {
            $response->headers->set('X-Cart-Token', $cart->guest_token);
        }

        return $response;
    }
}
