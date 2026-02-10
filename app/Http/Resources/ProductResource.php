<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'brand_id' => $this->brand_id,
            'category_id' => $this->category_id,
            'vendor_id' => $this->vendor_id,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'status' => $this->status,
            'variants' => ProductVariantResource::collection($this->whenLoaded('variants')),
            'images' => ProductImageResource::collection($this->whenLoaded('images')),
            'rating_summary' => new ProductRatingSummaryResource($this->whenLoaded('ratingSummary')),
        ];
    }
}
