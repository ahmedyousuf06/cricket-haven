<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'product_variant_id',
        'bundle_id',
        'item_type',
        'product_name_snapshot',
        'sku_snapshot',
        'price_snapshot',
        'qty',
    ];

    protected $casts = [
        'price_snapshot' => 'decimal:2',
        'qty' => 'integer',
        'item_type' => 'string',
    ];

    /**
     * @return BelongsTo<Order, OrderItem>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return BelongsTo<ProductVariant, OrderItem>
     */
    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    /**
     * @return BelongsTo<ProductBundle, OrderItem>
     */
    public function bundle(): BelongsTo
    {
        return $this->belongsTo(ProductBundle::class, 'bundle_id');
    }
}
