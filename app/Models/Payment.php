<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'order_id',
        'provider',
        'provider_ref',
        'status',
        'amount',
        'currency',
        'created_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'status' => 'string',
    ];

    /**
     * @return BelongsTo<Order, Payment>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
