<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'carrier',
        'tracking_number',
        'status',
        'shipped_at',
    ];

    protected $casts = [
        'status' => 'string',
        'shipped_at' => 'datetime',
    ];

    /**
     * @return BelongsTo<Order, Shipment>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
