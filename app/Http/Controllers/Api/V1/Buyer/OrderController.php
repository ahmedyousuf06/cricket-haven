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
        $query = Order::query()
            ->where('user_id', request()->user()->id)
            ->with(['user', 'items.productVariant', 'items.bundle', 'payment', 'shipment']);

        if ($status = request()->query('status')) {
            $query->where('status', $status);
        }

        if ($from = request()->query('date_from')) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to = request()->query('date_to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $sort = request()->query('sort', 'newest');
        $sortMap = [
            'newest' => ['created_at', 'desc'],
            'oldest' => ['created_at', 'asc'],
            'total_asc' => ['total', 'asc'],
            'total_desc' => ['total', 'desc'],
        ];
        [$sortField, $sortDir] = $sortMap[$sort] ?? $sortMap['newest'];
        $query->orderBy($sortField, $sortDir);

        $orders = $query->paginate(15);

        return OrderResource::collection($orders);
    }

    public function show(Order $order)
    {
        $this->authorizeOrder($order);
        $order->load(['user', 'items.productVariant', 'items.bundle', 'payment', 'shipment']);

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

        $order->load(['user', 'items.productVariant', 'items.bundle', 'payment', 'shipment']);

        return new OrderResource($order);
    }

    private function authorizeOrder(Order $order): void
    {
        if ((int) $order->user_id !== (int) request()->user()->id) {
            abort(403, 'Forbidden');
        }
    }
}
