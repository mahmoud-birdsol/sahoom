<?php

namespace App\Models;

use App\Models\States\ViewingRequestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class ViewingRequest extends Model
{
    /** @use HasFactory<\Database\Factories\ViewingRequestFactory> */
    use HasFactory;

    protected $fillable = [
        'property_id',
        'renter_name',
        'renter_email',
        'renter_phone',
        'message',
        'preferred_date',
        'status',
        'handled_by_user_id',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'status' => ViewingRequestStatus::class,
    ];

    /**
     * Get the property this viewing request is for.
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the user (account manager) handling this request.
     */
    public function handledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by_user_id');
    }

    /**
     * Scope to get requests that need attention (status = new).
     */
    public function scopeNeedsAttention($query)
    {
        return $query->where('status', ViewingRequestStatus::NEW->value);
    }

    /**
     * Scope to get requests by status.
     */
    public function scopeWithStatus($query, ViewingRequestStatus $status)
    {
        return $query->where('status', $status->value);
    }

    /**
     * Mark this request as contacted.
     */
    public function markAsContacted(?int $handledByUserId = null): void
    {
        $this->update([
            'status' => ViewingRequestStatus::CONTACTED->value,
            'handled_by_user_id' => $handledByUserId ?? $this->handled_by_user_id,
        ]);
    }

    /**
     * Mark this request as no show.
     */
    public function markAsNoShow(): void
    {
        $this->update([
            'status' => ViewingRequestStatus::NO_SHOW->value,
        ]);
    }

    /**
     * Close this request.
     */
    public function close(): void
    {
        $this->update([
            'status' => ViewingRequestStatus::CLOSED->value,
        ]);
    }

    /**
     * Assign a handler to this request.
     */
    public function assignHandler(int $userId): void
    {
        $this->update([
            'handled_by_user_id' => $userId,
        ]);
    }

    /**
     * Log audit information for this viewing request.
     */
    public function auditLog(string $action, array $additionalData = []): void
    {
        Log::info("ViewingRequest {$action}", array_merge([
            'action' => $action,
            'viewing_request_id' => $this->id,
            'property_id' => $this->property_id,
            'property_title' => $this->property?->title,
            'renter_name' => $this->renter_name,
            'renter_email' => $this->renter_email,
            'status' => $this->status?->value,
            'handled_by_user_id' => $this->handled_by_user_id,
            'handled_by_user_name' => $this->handledBy?->name,
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
        static::created(function (ViewingRequest $request) {
            $request->auditLog('created');
        });

        static::updated(function (ViewingRequest $request) {
            $request->auditLog('updated', [
                'changes' => $request->getChanges(),
                'original' => $request->getOriginal(),
            ]);
        });

        static::deleted(function (ViewingRequest $request) {
            $request->auditLog('deleted');
        });
    }
}
