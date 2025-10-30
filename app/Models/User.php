<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\States\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'is_active',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Get the landlord profile associated with this user.
     */
    public function landlord(): HasOne
    {
        return $this->hasOne(Landlord::class);
    }

    /**
     * Check if user is a super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === UserRole::SUPER_ADMIN;
    }

    /**
     * Check if user is an admin (includes super admin).
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, [UserRole::ADMIN, UserRole::SUPER_ADMIN]);
    }

    /**
     * Check if user is a landlord.
     */
    public function isLandlord(): bool
    {
        return $this->role === UserRole::LANDLORD;
    }

    /**
     * Activate the user account.
     */
    public function activate(): void
    {
        $this->update(['is_active' => true]);
    }

    /**
     * Deactivate the user account.
     */
    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Update last login timestamp.
     */
    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }

    /**
     * Scope to filter active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter inactive users.
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope to filter by role.
     */
    public function scopeWithRole($query, UserRole $role)
    {
        return $query->where('role', $role->value);
    }

    /**
     * Log audit information for this user.
     */
    public function auditLog(string $action, array $additionalData = []): void
    {
        Log::info("User {$action}", array_merge([
            'action' => $action,
            'user_id' => $this->id,
            'user_name' => $this->name,
            'user_email' => $this->email,
            'role' => $this->role?->value,
            'is_active' => $this->is_active,
            'performed_by_user_id' => auth()->id(),
            'performed_by_user_name' => auth()->user()?->name,
            'timestamp' => now()->toDateTimeString(),
        ], $additionalData));
    }

    /**
     * Boot method to register model events for automatic audit logging.
     */
    protected static function booted(): void
    {
        static::updated(function (User $user) {
            // Only log if role or is_active changed
            $changes = $user->getChanges();
            if (isset($changes['role']) || isset($changes['is_active'])) {
                $user->auditLog('updated', [
                    'changes' => $changes,
                    'original' => $user->getOriginal(),
                ]);
            }
        });
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }
}
