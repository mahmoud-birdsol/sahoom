<?php

namespace App\Models;

use App\Models\States\ContractStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Property extends Model
{
    /** @use HasFactory<\Database\Factories\PropertyFactory> */
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'landlord_id',
        'title',
        'slug',
        'description',
        'status',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'size_sqm',
        'traffic_score',
        'is_featured',
        'is_active',
        'rejection_reason',
        'floor',
        'space_number',
        'pricing_type',
        'monthly_rent',
        'weekly_rent',
        'yearly_rent',
        'daily_rent',
        'currency',
        'security_deposit',
        'application_fee',
        'min_lease_months',
        'max_lease_months',
        'nearby_places',
        'property_type',
        'is_short_term',
    ];

    /**
     * Get the property owner.
     */
    public function landlord(): BelongsTo
    {
        return $this->belongsTo(Landlord::class);
    }

    /**
     * Get the amenities for the property.
     */
    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class);
    }

    /**
     * Get the availability blocks for the property.
     */
    public function availabilityBlocks(): HasMany
    {
        return $this->hasMany(AvailabilityBlock::class)->orderBy('start_date', 'asc');
    }

    /**
     * Get the viewing requests for the property.
     */
    public function viewingRequests(): HasMany
    {
        return $this->hasMany(ViewingRequest::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the contracts for the property.
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class)->orderBy('start_date', 'desc');
    }

    /**
     * Get the images for the property.
     */
    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class)->orderBy('order');
    }

    /**
     * Get the visits for the property.
     */
    public function visits(): HasMany
    {
        return $this->hasMany(PropertyVisit::class, 'property_id');
    }

    /**
     * Get the favorites for the property.
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(PropertyFavorite::class);
    }

    /**
     * Get the reviews for the property.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(PropertyReview::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the average rating for the property.
     */
    public function averageRating(): float
    {
        return round((float) $this->reviews()->avg('rating'), 1);
    }

    /**
     * Get the total number of favorites.
     */
    public function favoritesCount(): int
    {
        return $this->favorites()->count();
    }

    /**
     * Check if the property is occupied (has an active contract).
     */
    public function isOccupied(): bool
    {
        return $this->contracts()
            ->where('contract_status', ContractStatus::ACTIVE->value)
            ->exists();
    }

    protected $casts = [
        'is_featured'      => 'boolean',
        'is_active'        => 'boolean',
        'is_short_term'    => 'boolean',
        'security_deposit' => 'float',
        'application_fee'  => 'float',
        'min_lease_months' => 'integer',
        'max_lease_months' => 'integer',
        'nearby_places'    => 'array',
        'property_type'    => \App\Models\States\PropertyType::class,
    ];

    public function getSlugOptions() : \Spatie\Sluggable\SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
}
