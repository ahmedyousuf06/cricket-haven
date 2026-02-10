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
            'product_variant_id' => $this->product_variant_id,
            'bundle_id' => $this->bundle_id,
            'qty' => $this->qty,
            'unit_price' => $this->unit_price,
            'variant' => new ProductVariantResource($this->whenLoaded('productVariant')),
        ];
    }
}
