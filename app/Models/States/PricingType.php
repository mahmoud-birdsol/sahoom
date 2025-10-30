<?php

namespace App\Models\States;

enum PricingType: string
{
    case MONTHLY = 'monthly';
    case WEEKLY = 'weekly';
    case YEARLY = 'yearly';
    case DAILY = 'daily';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::MONTHLY => 'Monthly',
            self::WEEKLY => 'Weekly',
            self::YEARLY => 'Yearly',
            self::DAILY => 'Daily',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::MONTHLY => 'Rent charged per month',
            self::WEEKLY => 'Rent charged per week',
            self::YEARLY => 'Rent charged per year',
            self::DAILY => 'Rent charged per day',
        };
    }
}
