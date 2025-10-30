<?php

namespace App\Models\States;

enum AvailabilityBlockSource: string
{
    case PLATFORM = 'platform';
    case OFFLINE = 'offline';
    case LANDLORD = 'landlord';
    case ADMIN = 'admin';
    case SYSTEM = 'system';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::PLATFORM => 'Platform Contract',
            self::OFFLINE => 'Offline Contract',
            self::LANDLORD => 'Landlord Contract',
            self::ADMIN => 'Admin',
            self::SYSTEM => 'System',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PLATFORM => 'success',
            self::OFFLINE => 'warning',
            self::LANDLORD => 'info',
            self::ADMIN => 'purple',
            self::SYSTEM => 'gray',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::PLATFORM => 'Contract created through the platform',
            self::OFFLINE => 'Contract created offline/externally',
            self::LANDLORD => 'Contract from landlord\'s other source',
            self::ADMIN => 'Manually created by admin',
            self::SYSTEM => 'Automatically created by system',
        };
    }
}
