<?php

namespace App\Models\States;

enum UserRole: string
{
    case LANDLORD = 'landlord';
    case ADMIN = 'admin';
    case SUPER_ADMIN = 'super_admin';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::LANDLORD => 'Landlord',
            self::ADMIN => 'Admin',
            self::SUPER_ADMIN => 'Super Admin',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::LANDLORD => 'info',
            self::ADMIN => 'warning',
            self::SUPER_ADMIN => 'danger',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::LANDLORD => 'Property owner/landlord with limited access',
            self::ADMIN => 'Admin user with management capabilities',
            self::SUPER_ADMIN => 'Super admin with full system access',
        };
    }

    public function canChangeTo(UserRole $newRole, UserRole $currentUserRole): bool
    {
        // Only super admin can change roles
        if ($currentUserRole !== self::SUPER_ADMIN) {
            return false;
        }

        // Super admin can change to any role
        return true;
    }
}
