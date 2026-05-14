# Ecommerce-api

## API Usage

### Guest Cart Usage

This API uses a stateless token-based approach for guest carts.

#### How it works

1. The first request to any cart endpoint creates a guest cart
   and returns an `X-Cart-Token` header.

2. Clients must store this token (e.g., in `localStorage` for web
   or `SharedPreferences` for mobile) and include it in the
   `X-Cart-Token` header of all subsequent cart requests.

3. When a user logs in, sending the `X-Cart-Token` header in the
   login request will automatically merge the guest cart with
   the user's cart.

#### Example (JavaScript)

```javascript
// First request
const response = await fetch('/api/v1/cart/items', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({ product_id: 1, quantity: 2 }),
});

const token = response.headers.get('X-Cart-Token');
localStorage.setItem('cart_token', token);

// Subsequent requests
await fetch('/api/v1/cart', {
  headers: { 'X-Cart-Token': localStorage.getItem('cart_token') },
});
```
