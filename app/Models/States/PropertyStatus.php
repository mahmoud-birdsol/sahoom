<?php

namespace App\Models\States;

enum PropertyStatus: string
{
    case DRAFT = 'draft';
    case IN_REVIEW = 'in_review';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case SUSPENDED = 'suspended';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'info',
            self::IN_REVIEW => 'info',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
            self::SUSPENDED => 'danger',
        };
    }

    public function icon(): string
    {
        return match ($this->color()) {
            'success' => 'check-circle',
            'warning' => 'exclamation-circle',
            'info' => 'exclamation-triangle',
            'danger' => 'x-circle',
        };
    }
}
