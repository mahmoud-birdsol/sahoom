<?php

namespace App\Models\States;

enum LandlordStatus: string
{
    case ACTIVE = 'active';
    case SUSPENDED = 'suspended';
    case BANNED = 'banned';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function color(): string
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::SUSPENDED => 'warning',
            self::BANNED => 'danger',
        };
    }

    public function icon(): string
    {
        return match ($this->color()) {
            'success' => 'check-circle',
            'warning' => 'exclamation-circle',
            'danger' => 'x-circle',
        };
    }
}
