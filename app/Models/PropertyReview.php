<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyReview extends Model
{
    /** @use HasFactory<\Database\Factories\PropertyReviewFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'property_id',
        'rating',
        'review',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }

    /**
     * Get the user who wrote the review.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reviewed property.
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Scope to filter by minimum rating.
     */
    public function scopeMinRating($query, int $rating): mixed
    {
        return $query->where('rating', '>=', $rating);
    }
}
