<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentResource extends JsonResource
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
            'carrier' => $this->carrier,
            'tracking_number' => $this->tracking_number,
            'status' => $this->status,
            'shipped_at' => $this->shipped_at,
        ];
    }
}
