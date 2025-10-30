# Database Seeder and Factory Fixes

## ✅ Issues Fixed

### 1. ViewingRequest Factory - Column Name Mismatch
**Issue**: Factory used `requested_date` but migration has `preferred_date`

**Fixed in**: `database/factories/ViewingRequestFactory.php`
```php
// ❌ Before
'requested_date' => fake()->optional(0.6)->dateTimeBetween('now', '+30 days'),

// ✅ After
'preferred_date' => fake()->optional(0.6)->date('+30 days'),
```

### 2. ViewingRequest Seeder - Column Name Mismatch
**Issue**: Seeder used `requested_date` but should use `preferred_date`

**Fixed in**: `database/seeders/DatabaseSeeder.php`
```php
// ❌ Before
'requested_date' => now()->addDays(fake()->numberBetween(1, 7)),

// ✅ After
'preferred_date' => now()->addDays(fake()->numberBetween(1, 7))->format('Y-m-d'),
```

### 3. ViewingRequest Status - Wrong Enum Value
**Issue**: Used `PENDING` and `CONFIRMED` but enum has `NEW` and `CONTACTED`

**Fixed in**: `database/seeders/DatabaseSeeder.php`
```php
// ❌ Before
'status' => ViewingRequestStatus::PENDING,
'status' => ViewingRequestStatus::CONFIRMED,

// ✅ After
'status' => ViewingRequestStatus::NEW,
'status' => ViewingRequestStatus::CONTACTED,
```

### 4. PropertyFactory - Rejection Reason Always Set
**Issue**: Factory always set `rejection_reason` even for non-rejected properties

**Fixed in**: `database/factories/PropertyFactory.php`
```php
// ❌ Before
'rejection_reason' => fake()->text(100),

// ✅ After
'rejection_reason' => null, // Only set when status is REJECTED
```

### 5. PropertyFactory - Improved Slug Generation
**Issue**: Slug wasn't unique, could cause duplicate errors

**Fixed in**: `database/factories/PropertyFactory.php`
```php
// ❌ Before
'slug' => fake()->slug(3),

// ✅ After
'slug' => fake()->unique()->slug(3),
```

### 6. Contract Seeder - Array Index Out of Bounds
**Issue**: Tried to access more properties than available (index 8, 9 when only 8 exist)

**Fixed in**: `database/seeders/DatabaseSeeder.php`
```php
// ✅ Added smart counting
$totalPublishedProperties = count($publishedProperties);
$maxContracts = min($totalPublishedProperties, 10);
$activeContracts = min(5, $maxContracts);
// ... with boundary checks
```

### 7. ViewingRequest Factory - Invalid User ID
**Issue**: Used `fake()->randomDigit()` which returns 0-9, but user IDs start at 1

**Fixed in**: `database/factories/ViewingRequestFactory.php`
```php
// ❌ Before
'handled_by_user_id' => fake()->optional(0.4)->randomDigit(),

// ✅ After
'handled_by_user_id' => null, // Set manually in seeder if needed
```

---

## 📊 Correct Enum Values Reference

### PropertyStatus
```php
DRAFT = 'draft'
IN_REVIEW = 'in_review'
APPROVED = 'approved'        // ✅ Use this instead of PUBLISHED
REJECTED = 'rejected'
SUSPENDED = 'suspended'
```

### ViewingRequestStatus
```php
NEW = 'new'                  // ✅ Use this instead of PENDING
CONTACTED = 'contacted'      // ✅ Use this instead of CONFIRMED
NO_SHOW = 'no_show'
CLOSED = 'closed'
```

### ContractStatus
```php
ACTIVE = 'active'
PENDING = 'pending'
COMPLETED = 'completed'
CANCELED = 'canceled'
```

### PricingType (New)
```php
MONTHLY = 'monthly'
WEEKLY = 'weekly'
YEARLY = 'yearly'
DAILY = 'daily'
```

---

## 🧪 Testing Commands (Using Sail)

### Fresh Migration with Seed
```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

### Seed Only
```bash
./vendor/bin/sail artisan db:seed
```

### Refresh Specific Seeder
```bash
./vendor/bin/sail artisan db:seed --class=DatabaseSeeder
```

---

## ✅ Expected Seeder Output

```
🌱 Starting database seeding...
👤 Creating super admin user...
✅ Super Admin created: mahmoud@birdsol.com
👥 Creating admin users...
✅ Admin created: admin@sahoom.sa
🏢 Creating landlords...
✅ Created 5 approved landlords
✅ Created 3 pending KYC landlords
✅ Created 2 rejected landlords
🏠 Creating properties...
✅ Created 8 published properties
✅ Created 5 in-review properties
✅ Created 3 draft properties
📅 Creating availability blocks...
✅ Created availability blocks for properties
📋 Creating contracts...
   Using 8 published properties for contracts
✅ Created 5 active contracts
✅ Created 3 upcoming contracts (next 14 days)
✅ Created 0 pending contracts
👁️ Creating viewing requests...
✅ Created viewing requests

🎉 Database seeding completed successfully!

📊 Summary:
   • Users: 12
   • Landlords: 10
   • Properties: 16
   • Contracts: 8
   • Availability Blocks: ~24
   • Viewing Requests: ~12

🔐 Super Admin Credentials:
   Email: mahmoud@birdsol.com
   Password: password
```

---

## 🔍 Column Mappings

### Properties Table
```
✅ status → 'draft', 'in_review', 'approved', 'rejected', 'suspended'
✅ slug → must be unique
✅ rejection_reason → nullable, only for rejected properties
```

### Contracts Table
```
✅ pricing_type → 'monthly', 'weekly', 'yearly', 'daily'
✅ monthly_rent → decimal(10,2) nullable
✅ weekly_rent → decimal(10,2) nullable
✅ yearly_rent → decimal(10,2) nullable
✅ daily_rent → decimal(10,2) nullable
✅ security_deposit → decimal(10,2) nullable
✅ service_fee → decimal(10,2) nullable
✅ cleaning_fee → decimal(10,2) nullable
✅ total_value → decimal(10,2) required
✅ currency → varchar(3) default 'USD'
✅ contract_status → enum
✅ payment_status → enum
```

### Viewing Requests Table
```
✅ preferred_date → date, nullable (NOT requested_date)
✅ status → 'new', 'contacted', 'no_show', 'closed'
✅ handled_by_user_id → foreign key to users, nullable
```

---

## 🚀 All Fixed!

All column mismatches, enum value errors, and array boundary issues have been resolved. The seeder should now run without errors.

**Run this to test**:
```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

---

**Last Updated**: October 30, 2025 17:30  
**Status**: ✅ All Issues Resolved
