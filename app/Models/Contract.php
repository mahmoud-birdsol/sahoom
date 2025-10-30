<?php

namespace App\Models;

use App\Models\States\AvailabilityBlockSource;
use App\Models\States\AvailabilityBlockStatus;
use App\Models\States\ContractStatus;
use App\Models\States\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class Contract extends Model
{
    /** @use HasFactory<\Database\Factories\ContractFactory> */
    use HasFactory;

    protected $fillable = [
        'property_id',
        'landlord_id',
        'renter_name',
        'renter_company',
        'start_date',
        'end_date',
        'total_value',
        'currency',
        'payment_status',
        'contract_status',
        'notes_internal',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_value' => 'decimal:2',
        'payment_status' => PaymentStatus::class,
        'contract_status' => ContractStatus::class,
    ];

    /**
     * Get the property for this contract.
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the landlord for this contract.
     */
    public function landlord(): BelongsTo
    {
        return $this->belongsTo(Landlord::class);
    }

    /**
     * Get the availability blocks created for this contract.
     */
    public function availabilityBlocks(): HasMany
    {
        return $this->hasMany(AvailabilityBlock::class, 'contract_reference', 'id');
    }

    /**
     * Get contract reference string.
     */
    public function getContractReferenceAttribute(): string
    {
        return "CONTRACT-{$this->id}";
    }

    /**
     * Create availability block for this contract.
     */
    public function createAvailabilityBlock(): AvailabilityBlock
    {
        return AvailabilityBlock::create([
            'property_id' => $this->property_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => AvailabilityBlockStatus::OCCUPIED->value,
            'source' => AvailabilityBlockSource::OFFLINE->value,
            'contract_reference' => "CONTRACT-{$this->id}",
            'notes' => "Offline contract for {$this->renter_name}",
        ]);
    }

    /**
     * Remove or update availability blocks when contract is canceled.
     */
    public function freeAvailability(): void
    {
        AvailabilityBlock::where('property_id', $this->property_id)
            ->where('contract_reference', "CONTRACT-{$this->id}")
            ->delete();
    }

    /**
     * Scope for active contracts.
     */
    public function scopeActive($query)
    {
        return $query->where('contract_status', ContractStatus::ACTIVE->value);
    }

    /**
     * Scope for upcoming contracts (starting in next 14 days).
     */
    public function scopeUpcoming($query, int $days = 14)
    {
        return $query->where('contract_status', ContractStatus::ACTIVE->value)
            ->whereBetween('start_date', [now(), now()->addDays($days)]);
    }

    /**
     * Scope for contracts by status.
     */
    public function scopeWithStatus($query, ContractStatus $status)
    {
        return $query->where('contract_status', $status->value);
    }

    /**
     * Log audit information for this contract.
     */
    public function auditLog(string $action, array $additionalData = []): void
    {
        Log::info("Contract {$action}", array_merge([
            'action' => $action,
            'contract_id' => $this->id,
            'property_id' => $this->property_id,
            'property_title' => $this->property?->title,
            'landlord_id' => $this->landlord_id,
            'landlord_name' => $this->landlord?->company_name ?? $this->landlord?->contact_name,
            'renter_name' => $this->renter_name,
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),
            'total_value' => $this->total_value,
            'currency' => $this->currency,
            'contract_status' => $this->contract_status?->value,
            'payment_status' => $this->payment_status?->value,
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
        static::created(function (Contract $contract) {
            $contract->auditLog('created');
            
            // Automatically create availability block
            $contract->createAvailabilityBlock();
            
            Log::info('Availability block auto-created for contract', [
                'contract_id' => $contract->id,
                'property_id' => $contract->property_id,
            ]);
        });

        static::updated(function (Contract $contract) {
            $contract->auditLog('updated', [
                'changes' => $contract->getChanges(),
                'original' => $contract->getOriginal(),
            ]);
        });

        static::deleted(function (Contract $contract) {
            $contract->auditLog('deleted');
        });
    }
}
