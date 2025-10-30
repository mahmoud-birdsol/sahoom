<?php

namespace App\Models\States;

enum ContractStatus: string
{
    case ACTIVE = 'active';
    case COMPLETED = 'completed';
    case CANCELED = 'canceled';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::COMPLETED => 'Completed',
            self::CANCELED => 'Canceled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::COMPLETED => 'info',
            self::CANCELED => 'danger',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::ACTIVE => 'Contract is currently active',
            self::COMPLETED => 'Contract has been completed',
            self::CANCELED => 'Contract has been canceled',
        };
    }
}
