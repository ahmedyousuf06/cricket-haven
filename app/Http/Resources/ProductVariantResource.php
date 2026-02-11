<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product' => $this->whenLoaded('product', fn () => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'slug' => $this->product->slug,
            ]),
            'sku' => $this->sku,
            'price' => $this->price,
            'compare_at_price' => $this->compare_at_price,
            'stock' => $this->stock,
            'weight_grams' => $this->weight_grams,
            'status' => $this->status,
            'attributes' => $this->whenLoaded('attributes', function () {
                $valueMap = $this->relationLoaded('attributeValues')
                    ? $this->attributeValues->keyBy('id')
                    : collect();

                return $this->attributes->map(function ($attribute) use ($valueMap) {
                    $valueId = $attribute->pivot->attribute_value_id ?? null;
                    $attributeValue = $valueId ? $valueMap->get($valueId) : null;

                    return [
                        'id' => $attribute->id,
                        'name' => $attribute->name,
                        'value_id' => $valueId,
                        'value' => $attributeValue?->value,
                    ];
                });
            }),
        ];
    }
}
