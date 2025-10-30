<?php

namespace App\Models\States;

enum PaymentStatus: string
{
    case NOT_COLLECTED = 'not_collected';
    case PARTIALLY_COLLECTED = 'partially_collected';
    case PAID = 'paid';
    case REFUNDED = 'refunded';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::NOT_COLLECTED => 'Not Collected',
            self::PARTIALLY_COLLECTED => 'Partially Collected',
            self::PAID => 'Paid',
            self::REFUNDED => 'Refunded',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::NOT_COLLECTED => 'danger',
            self::PARTIALLY_COLLECTED => 'warning',
            self::PAID => 'success',
            self::REFUNDED => 'info',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::NOT_COLLECTED => 'Payment has not been collected',
            self::PARTIALLY_COLLECTED => 'Payment has been partially collected',
            self::PAID => 'Payment has been fully collected',
            self::REFUNDED => 'Payment has been refunded',
        };
    }
}
