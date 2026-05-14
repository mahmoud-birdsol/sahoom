<?php

namespace App\Models\States;

enum PropertyType: string
{
    case RESIDENTIAL = 'residential';
    case COMMERCIAL   = 'commercial';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::RESIDENTIAL => 'Residential',
            self::COMMERCIAL  => 'Commercial',
        };
    }
}
