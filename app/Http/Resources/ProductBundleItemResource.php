<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductBundleItemResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bundle_id' => $this->bundle_id,
            'bundle' => $this->whenLoaded('bundle', fn () => [
                'id' => $this->bundle->id,
                'name' => $this->bundle->name,
                'slug' => $this->bundle->slug,
            ]),
            'product_variant_id' => $this->product_variant_id,
            'product_variant' => new ProductVariantResource($this->whenLoaded('productVariant')),
            'qty' => $this->qty,
        ];
    }
}
