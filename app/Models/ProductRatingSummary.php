<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductRatingSummary extends Model
{
    protected $table = 'product_rating_summary';
    protected $primaryKey = 'product_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'avg_rating',
        'review_count',
    ];

    protected $casts = [
        'avg_rating' => 'decimal:2',
        'review_count' => 'integer',
    ];

    /**
     * @return BelongsTo<Product, ProductRatingSummary>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
