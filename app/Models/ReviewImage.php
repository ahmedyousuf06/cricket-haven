<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewImage extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'review_id',
        'path',
        'created_at',
    ];

    /**
     * @return BelongsTo<Review, ReviewImage>
     */
    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class);
    }
}
