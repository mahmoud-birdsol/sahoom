<?php

namespace App\Models;

use App\Models\States\AvailabilityBlockSource;
use App\Models\States\AvailabilityBlockStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class AvailabilityBlock extends Model
{
    /** @use HasFactory<\Database\Factories\AvailabilityBlockFactory> */
    use HasFactory;

    protected $fillable = [
        'property_id',
        'start_date',
        'end_date',
        'status',
        'source',
        'contract_reference',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => AvailabilityBlockStatus::class,
        'source' => AvailabilityBlockSource::class,
    ];

    /**
     * Get the property that owns this availability block.
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Check if dates overlap with existing blocks for the same property.
     * Only checks against occupied/reserved blocks to prevent double-booking.
     */
    public static function hasOverlap(int $propertyId, string $startDate, string $endDate, ?int $excludeId = null): bool
    {
        $query = static::where('property_id', $propertyId)
            ->where(function ($q) use ($startDate, $endDate) {
                // Check for any overlap:
                // 1. New block starts within existing block
                // 2. New block ends within existing block  
                // 3. New block completely encompasses existing block
                $q->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q2) use ($startDate, $endDate) {
                        $q2->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->whereIn('status', [
                AvailabilityBlockStatus::OCCUPIED->value,
                AvailabilityBlockStatus::RESERVED->value,
            ]);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Get overlapping blocks for this availability block.
     */
    public function getOverlappingBlocks()
    {
        return static::where('property_id', $this->property_id)
            ->where('id', '!=', $this->id)
            ->where(function ($q) {
                $q->whereBetween('start_date', [$this->start_date, $this->end_date])
                    ->orWhereBetween('end_date', [$this->start_date, $this->end_date])
                    ->orWhere(function ($q2) {
                        $q2->where('start_date', '<=', $this->start_date)
                            ->where('end_date', '>=', $this->end_date);
                    });
            })
            ->whereIn('status', [
                AvailabilityBlockStatus::OCCUPIED->value,
                AvailabilityBlockStatus::RESERVED->value,
            ])
            ->get();
    }

    /**
     * Log audit information for this block.
     */
    public function auditLog(string $action, array $additionalData = []): void
    {
        Log::info("AvailabilityBlock {$action}", array_merge([
            'action' => $action,
            'availability_block_id' => $this->id,
            'property_id' => $this->property_id,
            'property_title' => $this->property?->title,
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),
            'status' => $this->status?->value,
            'source' => $this->source?->value,
            'contract_reference' => $this->contract_reference,
            'user_id' => auth()->id(),
            'user_name' => auth()->user()?->name,
            'timestamp' => now()->toDateTimeString(),
        ], $additionalData));
    }

    /**
     * Boot method to register model events for automatic audit logging.
     */
    protected static function booted(): void
    {
        static::created(function (AvailabilityBlock $block) {
            $block->auditLog('created');
        });

        static::updated(function (AvailabilityBlock $block) {
            $block->auditLog('updated', [
                'changes' => $block->getChanges(),
                'original' => $block->getOriginal(),
            ]);
        });

        static::deleted(function (AvailabilityBlock $block) {
            $block->auditLog('deleted');
        });
    }
}
