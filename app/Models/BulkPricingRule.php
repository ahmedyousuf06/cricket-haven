<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BulkPricingRule extends Model
{
    protected $table = 'bulk_pricing_rules';

    public $timestamps = false;

    protected $fillable = [
        'product_variant_id',
        'min_qty',
        'price_per_unit',
    ];

    protected $casts = [
        'min_qty' => 'integer',
        'price_per_unit' => 'decimal:2',
    ];

    /**
     * @return BelongsTo<ProductVariant, BulkPricingRule>
     */
    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
