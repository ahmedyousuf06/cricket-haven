<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cart_id' => $this->cart_id,
            'cart' => $this->whenLoaded('cart', fn () => [
                'id' => $this->cart->id,
                'user_id' => $this->cart->user_id,
                'session_id' => $this->cart->session_id,
            ]),
            'product_variant_id' => $this->product_variant_id,
            'product_variant' => new ProductVariantResource($this->whenLoaded('productVariant')),
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
            'qty' => $this->qty,
            'unit_price' => $this->unit_price,
        ];
    }
}
