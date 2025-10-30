<?php

namespace App\Models\States;

enum ViewingRequestStatus: string
{
    case NEW = 'new';
    case CONTACTED = 'contacted';
    case NO_SHOW = 'no_show';
    case CLOSED = 'closed';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::NEW => 'New',
            self::CONTACTED => 'Contacted',
            self::NO_SHOW => 'No Show',
            self::CLOSED => 'Closed',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::NEW => 'warning',
            self::CONTACTED => 'info',
            self::NO_SHOW => 'danger',
            self::CLOSED => 'success',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::NEW => 'New viewing request - needs attention',
            self::CONTACTED => 'Renter has been contacted',
            self::NO_SHOW => 'Renter did not show up for viewing',
            self::CLOSED => 'Request completed/closed',
        };
    }
}
