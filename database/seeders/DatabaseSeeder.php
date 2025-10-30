<?php

namespace Database\Seeders;

use App\Models\AvailabilityBlock;
use App\Models\Contract;
use App\Models\Landlord;
use App\Models\Property;
use App\Models\States\AvailabilityBlockSource;
use App\Models\States\AvailabilityBlockStatus;
use App\Models\States\ContractStatus;
use App\Models\States\PricingType;
use App\Models\States\LandlordKycStatus;
use App\Models\States\LandlordStatus;
use App\Models\States\PropertyStatus;
use App\Models\States\UserRole;
use App\Models\States\ViewingRequestStatus;
use App\Models\User;
use App\Models\ViewingRequest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');

        // 1. Create Super Admin User
        $this->command->info('👤 Creating super admin user...');
        $superAdmin = User::factory()->withoutTwoFactor()->create([
            'name' => 'Mahmoud El-Mokhtar',
            'email' => 'mahmoud@birdsol.com',
            'password' => Hash::make('password'),
            'role' => UserRole::SUPER_ADMIN,
            'is_active' => true,
            'email_verified_at' => now(),
            'phone' => '+966501234567',
        ]);
        $this->command->info("✅ Super Admin created: {$superAdmin->email}");

        // 2. Create Additional Admin Users
        $this->command->info('👥 Creating admin users...');
        $admin1 = User::factory()->withoutTwoFactor()->create([
            'name' => 'Admin User',
            'email' => 'admin@sahoom.sa',
            'password' => Hash::make('password'),
            'role' => UserRole::ADMIN,
            'is_active' => true,
            'phone' => '+966502345678',
        ]);
        $this->command->info("✅ Admin created: {$admin1->email}");

        // 3. Create Landlords with Different KYC Statuses
        $this->command->info('🏢 Creating landlords...');

        // Approved Landlords (5)
        $approvedLandlords = [];
        for ($i = 1; $i <= 5; $i++) {
            $user = User::factory()->withoutTwoFactor()->create([
                'role' => UserRole::LANDLORD,
                'is_active' => true,
                'password' => Hash::make('password'),
            ]);

            $landlord = Landlord::factory()->create([
                'user_id' => $user->id,
                'status' => LandlordStatus::ACTIVE,
                'kyc_status' => LandlordKycStatus::APPROVED,
                'company_name' => "Approved Landlord Company {$i}",
            ]);

            $approvedLandlords[] = $landlord;
        }
        $this->command->info("✅ Created 5 approved landlords");

        // Pending KYC Landlords (3)
        for ($i = 1; $i <= 3; $i++) {
            $user = User::factory()->withoutTwoFactor()->create([
                'role' => UserRole::LANDLORD,
                'is_active' => true,
                'password' => Hash::make('password'),
            ]);

            Landlord::factory()->create([
                'user_id' => $user->id,
                'status' => LandlordStatus::ACTIVE,
                'kyc_status' => LandlordKycStatus::PENDING,
                'company_name' => "Pending KYC Company {$i}",
            ]);
        }
        $this->command->info("✅ Created 3 pending KYC landlords");

        // Rejected KYC Landlords (2)
        for ($i = 1; $i <= 2; $i++) {
            $user = User::factory()->withoutTwoFactor()->create([
                'role' => UserRole::LANDLORD,
                'is_active' => false,
                'password' => Hash::make('password'),
            ]);

            Landlord::factory()->create([
                'user_id' => $user->id,
                'status' => LandlordStatus::SUSPENDED,
                'kyc_status' => LandlordKycStatus::REJECTED,
                'company_name' => "Rejected Company {$i}",
                'verification_notes' => 'Insufficient documentation provided',
            ]);
        }
        $this->command->info("✅ Created 2 rejected landlords");

        // 4. Create Properties with Different Statuses
        $this->command->info('🏠 Creating properties...');

        $publishedProperties = [];

        // Published Properties (10)
        foreach ($approvedLandlords as $index => $landlord) {
            $property = Property::factory()->create([
                'landlord_id' => $landlord->id,
                'status' => PropertyStatus::APPROVED,
                'title' => "Luxury Apartment " . ($index + 1),
                'city' => fake()->randomElement(['Riyadh', 'Jeddah', 'Dammam', 'Khobar', 'Mecca']),
                'country' => 'Saudi Arabia',
                'size_sqm' => fake()->numberBetween(80, 300),
                'is_featured' => $index < 2, // First 2 are featured
            ]);
            $publishedProperties[] = $property;

            // Create a second property for some landlords
            if ($index < 3) {
                $property2 = Property::factory()->create([
                    'landlord_id' => $landlord->id,
                    'status' => PropertyStatus::APPROVED,
                    'title' => "Modern Villa " . ($index + 1),
                    'city' => fake()->randomElement(['Riyadh', 'Jeddah', 'Dammam']),
                    'country' => 'Saudi Arabia',
                    'size_sqm' => fake()->numberBetween(200, 500),
                ]);
                $publishedProperties[] = $property2;
            }
        }
        $this->command->info("✅ Created " . count($publishedProperties) . " published properties");

        // In Review Properties (5)
        foreach (array_slice($approvedLandlords, 0, 3) as $index => $landlord) {
            Property::factory()->create([
                'landlord_id' => $landlord->id,
                'status' => PropertyStatus::IN_REVIEW,
                'title' => "Pending Review Property " . ($index + 1),
            ]);
        }
        $this->command->info("✅ Created 5 in-review properties");

        // Draft Properties (3)
        foreach (array_slice($approvedLandlords, 0, 2) as $index => $landlord) {
            Property::factory()->create([
                'landlord_id' => $landlord->id,
                'status' => PropertyStatus::DRAFT,
                'title' => "Draft Property " . ($index + 1),
            ]);
        }
        $this->command->info("✅ Created 3 draft properties");

        // 5. Create Availability Blocks
        $this->command->info('📅 Creating availability blocks...');

        foreach ($publishedProperties as $property) {
            // Create some occupied blocks (past and current)
            AvailabilityBlock::factory()->create([
                'property_id' => $property->id,
                'start_date' => now()->subDays(30),
                'end_date' => now()->subDays(20),
                'status' => AvailabilityBlockStatus::OCCUPIED,
                'source' => AvailabilityBlockSource::PLATFORM,
            ]);

            // Create some upcoming reserved blocks
            AvailabilityBlock::factory()->create([
                'property_id' => $property->id,
                'start_date' => now()->addDays(5),
                'end_date' => now()->addDays(15),
                'status' => AvailabilityBlockStatus::RESERVED,
                'source' => AvailabilityBlockSource::PLATFORM,
            ]);

            // Create maintenance block for some properties
            if (fake()->boolean(30)) {
                AvailabilityBlock::factory()->create([
                    'property_id' => $property->id,
                    'start_date' => now()->addDays(20),
                    'end_date' => now()->addDays(25),
                    'status' => AvailabilityBlockStatus::MAINTENANCE,
                    'source' => AvailabilityBlockSource::ADMIN,
                    'notes' => 'Scheduled maintenance',
                ]);
            }
        }
        $this->command->info("✅ Created availability blocks for properties");

        // 6. Create Contracts
        $this->command->info('📋 Creating contracts...');

        $totalPublishedProperties = count($publishedProperties);
        $this->command->info("   Using {$totalPublishedProperties} published properties for contracts");

        // Calculate how many contracts we can create
        $maxContracts = min($totalPublishedProperties, 10);
        $activeContracts = min(5, $maxContracts);
        $upcomingContracts = min(3, $maxContracts - $activeContracts);
        $pendingContracts = min(2, $maxContracts - $activeContracts - $upcomingContracts);

        // Active Contracts
        for ($i = 0; $i < $activeContracts; $i++) {
            $property = $publishedProperties[$i];
            $monthlyRent = fake()->numberBetween(3000, 10000);
            $startDate = now()->subMonths(2);
            $endDate = now()->addMonths(10);
            $durationMonths = 12;

            Contract::factory()->create([
                'property_id' => $property->id,
                'landlord_id' => $property->landlord_id,
                'contract_status' => ContractStatus::ACTIVE,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'pricing_type' => PricingType::MONTHLY,
                'monthly_rent' => $monthlyRent,
                'weekly_rent' => round($monthlyRent / 4.33, 2),
                'yearly_rent' => $monthlyRent * 12,
                'daily_rent' => round($monthlyRent / 30, 2),
                'security_deposit' => $monthlyRent * 2,
                'service_fee' => fake()->numberBetween(100, 500),
                'cleaning_fee' => fake()->numberBetween(50, 300),
                'total_value' => $monthlyRent * $durationMonths,
                'currency' => 'SAR',
            ]);
        }
        $this->command->info("✅ Created {$activeContracts} active contracts");

        // Upcoming Contracts - starting in next 14 days
        for ($i = 0; $i < $upcomingContracts; $i++) {
            $propertyIndex = $activeContracts + $i;
            if ($propertyIndex >= $totalPublishedProperties) break;

            $property = $publishedProperties[$propertyIndex];
            $monthlyRent = fake()->numberBetween(3000, 10000);
            $startDate = now()->addDays(fake()->numberBetween(1, 14));
            $endDate = now()->addYear();
            $durationMonths = 12;

            Contract::factory()->create([
                'property_id' => $property->id,
                'landlord_id' => $property->landlord_id,
                'contract_status' => ContractStatus::ACTIVE,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'pricing_type' => PricingType::MONTHLY,
                'monthly_rent' => $monthlyRent,
                'weekly_rent' => round($monthlyRent / 4.33, 2),
                'yearly_rent' => $monthlyRent * 12,
                'daily_rent' => round($monthlyRent / 30, 2),
                'security_deposit' => $monthlyRent * 2,
                'service_fee' => fake()->numberBetween(100, 500),
                'cleaning_fee' => fake()->numberBetween(50, 300),
                'total_value' => $monthlyRent * $durationMonths,
                'currency' => 'SAR',
            ]);
        }
        $this->command->info("✅ Created {$upcomingContracts} upcoming contracts (next 14 days)");

        // Pending Contracts
        for ($i = 0; $i < $pendingContracts; $i++) {
            $propertyIndex = $activeContracts + $upcomingContracts + $i;
            if ($propertyIndex >= $totalPublishedProperties) break;

            $property = $publishedProperties[$propertyIndex];
            $monthlyRent = fake()->numberBetween(3000, 10000);
            $startDate = now()->addDays(30);
            $endDate = now()->addDays(395);
            $durationMonths = 12;

            Contract::factory()->create([
                'property_id' => $property->id,
                'landlord_id' => $property->landlord_id,
                'contract_status' => ContractStatus::PENDING,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'pricing_type' => PricingType::MONTHLY,
                'monthly_rent' => $monthlyRent,
                'weekly_rent' => round($monthlyRent / 4.33, 2),
                'yearly_rent' => $monthlyRent * 12,
                'daily_rent' => round($monthlyRent / 30, 2),
                'security_deposit' => $monthlyRent * 2,
                'service_fee' => fake()->numberBetween(100, 500),
                'cleaning_fee' => fake()->numberBetween(50, 300),
                'total_value' => $monthlyRent * $durationMonths,
                'currency' => 'SAR',
            ]);
        }
        $this->command->info("✅ Created {$pendingContracts} pending contracts");

        // 7. Create Viewing Requests
        $this->command->info('👁️ Creating viewing requests...');

        $availablePropertiesForViewing = min(8, count($publishedProperties));
        foreach (array_slice($publishedProperties, 0, $availablePropertiesForViewing) as $property) {
            // New viewing requests
            ViewingRequest::factory()->create([
                'property_id' => $property->id,
                'status' => ViewingRequestStatus::NEW,
                'preferred_date' => now()->addDays(fake()->numberBetween(1, 7))->format('Y-m-d'),
                'renter_name' => fake()->name(),
                'renter_email' => fake()->safeEmail(),
                'renter_phone' => fake()->phoneNumber(),
                'message' => fake()->paragraph(),
            ]);

            // Some contacted requests
            if (fake()->boolean(50)) {
                ViewingRequest::factory()->create([
                    'property_id' => $property->id,
                    'status' => ViewingRequestStatus::CONTACTED,
                    'preferred_date' => now()->addDays(fake()->numberBetween(3, 10))->format('Y-m-d'),
                    'renter_name' => fake()->name(),
                    'renter_email' => fake()->safeEmail(),
                    'renter_phone' => fake()->phoneNumber(),
                    'message' => fake()->paragraph(),
                ]);
            }
        }
        $this->command->info("✅ Created viewing requests");

        // Call additional seeders
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('🎉 Database seeding completed successfully!');
        $this->command->info('');
        $this->command->info('📊 Summary:');
        $this->command->info("   • Users: " . User::count());
        $this->command->info("   • Landlords: " . Landlord::count());
        $this->command->info("   • Properties: " . Property::count());
        $this->command->info("   • Contracts: " . Contract::count());
        $this->command->info("   • Availability Blocks: " . AvailabilityBlock::count());
        $this->command->info("   • Viewing Requests: " . ViewingRequest::count());
        $this->command->info('');
        $this->command->info('🔐 Super Admin Credentials:');
        $this->command->info("   Email: mahmoud@birdsol.com");
        $this->command->info("   Password: password");
        $this->command->info('');
    }
}
