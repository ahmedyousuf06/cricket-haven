<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'order' => $this->whenLoaded('order', fn () => [
                'id' => $this->order->id,
                'status' => $this->order->status,
                'total' => $this->order->total,
            ]),
            'product_variant_id' => $this->product_variant_id,
            'product_variant' => $this->whenLoaded('productVariant', function () {
                if (!$this->productVariant) {
                    return null;
                }

                return [
                    'id' => $this->productVariant->id,
                    'sku' => $this->productVariant->sku,
                    'price' => $this->productVariant->price,
                ];
            }),
            'bundle_id' => $this->bundle_id,
            'bundle' => $this->whenLoaded('bundle', function () {
                if (!$this->bundle) {
                    return null;
                }

                return [
                    'id' => $this->bundle->id,
                    'name' => $this->bundle->name,
                    'slug' => $this->bundle->slug,
                ];
            }),
            'item_type' => $this->item_type,
            'product_name_snapshot' => $this->product_name_snapshot,
            'sku_snapshot' => $this->sku_snapshot,
            'price_snapshot' => $this->price_snapshot,
            'qty' => $this->qty,
        ];
    }
}
