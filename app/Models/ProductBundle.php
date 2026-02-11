<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductBundle extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'status' => 'string',
    ];

    /**
     * @return HasMany<ProductBundleItem>
     */
    public function items(): HasMany
    {
        return $this->hasMany(ProductBundleItem::class, 'bundle_id');
    }

    /**
     * @return BelongsToMany<ProductVariant>
     */
    public function productVariants(): BelongsToMany
    {
        return $this->belongsToMany(ProductVariant::class, 'product_bundle_items', 'bundle_id', 'product_variant_id')
            ->withPivot('qty');
    }

    /**
     * @return HasMany<BundleImage>
     */
    public function images(): HasMany
    {
        return $this->hasMany(BundleImage::class, 'bundle_id');
    }

    /**
     * @return HasMany<CartItem>
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class, 'bundle_id');
    }

    /**
     * @return HasMany<OrderItem>
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'bundle_id');
    }
}
