# Cricket Haven (Laravel + React Frontend)

This project now runs a React frontend inside the Laravel app using Vite.

## Tech Stack

- Laravel API backend
- React + React Router v6 frontend
- Vite bundler
- Axios API client
- Tailwind CSS for styling

## Development Setup

1. Install PHP dependencies:

```bash
composer install
```

2. Install Node dependencies:

```bash
npm install
```

3. Set environment variables:

```bash
cp .env.example .env
php artisan key:generate
```

4. Start backend server:

```bash
php artisan serve
```

5. Start Vite dev server:

```bash
npm run dev
```

## Frontend Entry + Routing

- React entry: `resources/js/app.jsx`
- React routes: `resources/js/routes/AppRoutes.jsx`
- Laravel SPA view: `resources/views/app.blade.php`
- Catch-all route: `routes/web.php`

Laravel serves the React app for `/*` so React Router controls page navigation.

## API Integration

- API client: `resources/js/services/api.js`
- Base URL: `/api/v1`
- Auth header: Bearer token via `setAuthToken()`

Example usage:

- Products: `fetchProducts()`, `fetchProductById(id)`
- Cart: `fetchCart()`, `addCartItem()`, `updateCartItem()`, `removeCartItem()`, `clearCart()`
- Orders: `createOrder()`, `fetchOrders()`, `fetchOrderById(id)`

## Global State

- Auth context: `resources/js/contexts/AuthContext.jsx`
- Cart context: `resources/js/contexts/CartContext.jsx`

## API Keys / Secrets

Do not hardcode keys in React files.

Put keys/secrets only in `.env` and expose frontend-safe values with `VITE_` prefix when needed, e.g.:

```env
VITE_APP_NAME="Cricket Haven"
```

Access in frontend:

```js
const appName = import.meta.env.VITE_APP_NAME;
```

## Building for Production

```bash
npm run build
```

Laravel will load compiled assets from `public/build`.

## UI Design Workflow

Upload PNG designs and implement each screen under:

- `resources/js/pages/*`
- `resources/js/components/*`

Current scaffold includes:

- Home
- Category
- Product Detail
- Cart
- Checkout
- Order Confirmation
- Not Found
