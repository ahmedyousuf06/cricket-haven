<?php

namespace App\Http\Controllers\Api\V1\Buyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Buyer\OrderStoreRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::query()
            ->where('user_id', request()->user()->id)
            ->with(['items', 'payment', 'shipment'])
            ->latest()
            ->paginate(15);

        return OrderResource::collection($orders);
    }

    public function show(Order $order)
    {
        $this->authorizeOrder($order);
        $order->load(['items', 'payment', 'shipment']);

        return new OrderResource($order);
    }

    public function store(OrderStoreRequest $request)
    {
        $user = $request->user();
        $items = $request->input('items');

        $order = DB::transaction(function () use ($user, $items) {
            $order = Order::query()->create([
                'user_id' => $user->id,
                'subtotal' => 0,
                'tax' => 0,
                'shipping_cost' => 0,
                'total' => 0,
                'status' => 'pending',
            ]);

            $subtotal = 0;

            foreach ($items as $item) {
                $variant = ProductVariant::query()->findOrFail($item['product_variant_id']);
                $qty = (int) $item['qty'];
                $line = $variant->price * $qty;
                $subtotal += $line;

                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_variant_id' => $variant->id,
                    'bundle_id' => null,
                    'item_type' => 'product',
                    'product_name_snapshot' => $variant->product?->name ?? 'Product',
                    'sku_snapshot' => $variant->sku,
                    'price_snapshot' => $variant->price,
                    'qty' => $qty,
                ]);
            }

            $tax = round($subtotal * 0.08, 2);
            $shipping = $subtotal > 150 ? 0 : 12.00;
            $total = $subtotal + $tax + $shipping;

            $order->update([
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_cost' => $shipping,
                'total' => $total,
            ]);

            return $order;
        });

        $order->load(['items', 'payment', 'shipment']);

        return new OrderResource($order);
    }

    private function authorizeOrder(Order $order): void
    {
        if ((int) $order->user_id !== (int) request()->user()->id) {
            abort(403, 'Forbidden');
        }
    }
}
