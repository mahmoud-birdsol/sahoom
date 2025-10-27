<?php

namespace App\Models\States;

enum LandlordKycStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
        };
    }

    public function icon(): string
    {
        return match ($this->color()) {
            'warning' => 'exclamation-circle',
            'success' => 'check-circle',
            'danger' => 'x-circle',
        };
    }
}
