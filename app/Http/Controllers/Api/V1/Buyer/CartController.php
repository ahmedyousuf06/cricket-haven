<?php

namespace App\Http\Controllers\Api\V1\Buyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Buyer\CartItemStoreRequest;
use App\Http\Requests\Api\V1\Buyer\CartItemUpdateRequest;
use App\Http\Resources\CartItemResource;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function show(Request $request)
    {
        $cart = $this->getOrCreateCart($request);
        $cart->load(['items.productVariant']);

        return new CartResource($cart);
    }

    public function storeItem(CartItemStoreRequest $request)
    {
        $cart = $this->getOrCreateCart($request);
        $variant = ProductVariant::query()->findOrFail($request->input('product_variant_id'));

        $item = CartItem::query()->updateOrCreate(
            [
                'cart_id' => $cart->id,
                'product_variant_id' => $variant->id,
                'bundle_id' => $request->input('bundle_id'),
            ],
            [
                'qty' => $request->input('qty'),
                'unit_price' => $variant->price,
            ]
        );

        $cart->load(['items.productVariant']);

        return new CartResource($cart);
    }

    public function updateItem(CartItemUpdateRequest $request, CartItem $item)
    {
        $this->authorizeItem($request, $item);

        $item->update([
            'qty' => $request->input('qty'),
        ]);

        $item->load('productVariant');

        return response()->json([
            'item' => new CartItemResource($item),
        ]);
    }

    public function destroyItem(Request $request, CartItem $item)
    {
        $this->authorizeItem($request, $item);

        $item->delete();

        return response()->json([
            'message' => 'Item removed.',
        ]);
    }

    public function clear(Request $request)
    {
        $cart = $this->getOrCreateCart($request);
        $cart->items()->delete();

        return response()->json([
            'message' => 'Cart cleared.',
        ]);
    }

    private function getOrCreateCart(Request $request): Cart
    {
        $user = $request->user();

        $cart = Cart::query()->firstOrCreate(
            ['user_id' => $user->id],
            ['session_id' => (string) Str::uuid()]
        );

        return $cart;
    }

    private function authorizeItem(Request $request, CartItem $item): void
    {
        if ((int) $item->cart?->user_id !== (int) $request->user()->id) {
            abort(403, 'Forbidden');
        }
    }
}
