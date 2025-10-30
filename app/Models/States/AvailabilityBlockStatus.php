<?php

namespace App\Models\States;

enum AvailabilityBlockStatus: string
{
    case OCCUPIED = 'occupied';
    case RESERVED = 'reserved';
    case MAINTENANCE = 'maintenance';
    case AVAILABLE_OVERRIDE = 'available_override';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::OCCUPIED => 'Occupied',
            self::RESERVED => 'Reserved',
            self::MAINTENANCE => 'Maintenance',
            self::AVAILABLE_OVERRIDE => 'Available (Override)',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::OCCUPIED => 'danger',
            self::RESERVED => 'warning',
            self::MAINTENANCE => 'info',
            self::AVAILABLE_OVERRIDE => 'success',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::OCCUPIED => 'Property is occupied and not available for booking',
            self::RESERVED => 'Property is reserved/pending confirmation',
            self::MAINTENANCE => 'Property is under maintenance',
            self::AVAILABLE_OVERRIDE => 'Override block to mark as available',
        };
    }
}
