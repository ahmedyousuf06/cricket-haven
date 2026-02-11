<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'compare_at_price',
        'stock',
        'weight_grams',
        'status',
        'carts_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'stock' => 'integer',
        'weight_grams' => 'integer',
        'status' => 'string',
        'carts_count' => 'integer',
    ];

    /**
     * @return BelongsTo<Product, ProductVariant>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return BelongsToMany<Attribute>
     */
    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'product_variant_attributes')
            ->withPivot('attribute_value_id');
    }

    /**
     * @return BelongsToMany<AttributeValue>
     */
    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(AttributeValue::class, 'product_variant_attributes', 'product_variant_id', 'attribute_value_id')
            ->withPivot('attribute_id');
    }

    /**
     * @return HasMany<ProductVariantAttribute>
     */
    public function variantAttributes(): HasMany
    {
        return $this->hasMany(ProductVariantAttribute::class);
    }

    /**
     * @return HasMany<CartItem>
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * @return HasMany<BulkPricingRule>
     */
    public function bulkPricingRules(): HasMany
    {
        return $this->hasMany(BulkPricingRule::class);
    }

    /**
     * @return HasMany<OrderItem>
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * @return HasMany<ProductBundleItem>
     */
    public function bundleItems(): HasMany
    {
        return $this->hasMany(ProductBundleItem::class);
    }

    /**
     * @return BelongsToMany<ProductBundle>
     */
    public function bundles(): BelongsToMany
    {
        return $this->belongsToMany(ProductBundle::class, 'product_bundle_items', 'product_variant_id', 'bundle_id')
            ->withPivot('qty');
    }

    /**
     * @return HasMany<Review>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock', '>', 0)->where('status', 'active');
    }
}
