<?php

namespace Database\Seeders;

use App\Models\States\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding production data...');

        // 1. Super Admin
        $superAdmin = User::updateOrCreate(
            ['email' => 'mahmoud@birdsol.com'],
            [
                'name'              => 'Mahmoud El-Mokhtar',
                'password'          => Hash::make('password'),
                'role'              => UserRole::SUPER_ADMIN,
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );
        $this->command->info("✅ Super Admin: {$superAdmin->email}");

        // 2. Roles, Permissions & Site Settings
        $this->call([
            RolesAndPermissionsSeeder::class,
            SiteSettingSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('🎉 Production seeding complete!');
        $this->command->info('🔐 Admin credentials:');
        $this->command->info('   Email:    mahmoud@birdsol.com');
        $this->command->info('   Password: password');
        $this->command->info('');
    }
}
