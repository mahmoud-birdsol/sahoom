# Contract Pricing Model Documentation

## Overview

The contract pricing model provides a flexible, multi-tier pricing system that supports daily, weekly, monthly, and yearly rental periods. This allows landlords and admins to offer various pricing options to accommodate different renter needs.

## Pricing Types

### Available Pricing Types

| Type | Value | Description |
|------|-------|-------------|
| **Monthly** | `monthly` | Rent charged per month (default) |
| **Weekly** | `weekly` | Rent charged per week |
| **Yearly** | `yearly` | Rent charged per year |
| **Daily** | `daily` | Rent charged per day |

## Database Schema

### Contract Pricing Fields

```sql
pricing_type       ENUM('monthly', 'weekly', 'yearly', 'daily') DEFAULT 'monthly'
monthly_rent       DECIMAL(10,2) NULL
weekly_rent        DECIMAL(10,2) NULL
yearly_rent        DECIMAL(10,2) NULL
daily_rent         DECIMAL(10,2) NULL
security_deposit   DECIMAL(10,2) NULL
service_fee        DECIMAL(10,2) NULL
cleaning_fee       DECIMAL(10,2) NULL
total_value        DECIMAL(10,2) NOT NULL
currency           VARCHAR(3) DEFAULT 'USD'
```

## Pricing Calculations

### Standard Calculation Formula

The system uses the following formulas to calculate pricing across different time periods:

```php
// Base: Monthly Rent
$monthlyRent = 5000; // SAR

// Derived Calculations
$weeklyRent = $monthlyRent / 4.33;  // ≈ 1,155 SAR (average weeks per month)
$yearlyRent = $monthlyRent * 12;    // 60,000 SAR
$dailyRent  = $monthlyRent / 30;    // ≈ 167 SAR (average days per month)
```

### Total Contract Value

The total contract value is calculated based on the contract duration:

```php
$durationMonths = $startDate->diffInMonths($endDate);
$totalValue = $monthlyRent * $durationMonths;
```

## Additional Fees

### Security Deposit
- **Purpose**: Refundable deposit to cover damages
- **Typical Amount**: 1-2x monthly rent
- **Example**: 10,000 SAR (2x 5,000 SAR monthly rent)

### Service Fee
- **Purpose**: One-time platform/service fee
- **Typical Amount**: 100-500 SAR
- **Refundable**: No

### Cleaning Fee
- **Purpose**: One-time cleaning charge
- **Typical Amount**: 50-300 SAR
- **Refundable**: No

## Usage Examples

### Example 1: Standard Monthly Contract

```php
Contract::create([
    'property_id' => 1,
    'landlord_id' => 1,
    'renter_name' => 'Ahmed Mohammed',
    'start_date' => '2025-11-01',
    'end_date' => '2026-10-31',
    
    // Pricing
    'pricing_type' => PricingType::MONTHLY,
    'monthly_rent' => 5500,
    'weekly_rent' => 1270.67,
    'yearly_rent' => 66000,
    'daily_rent' => 183.33,
    
    // Additional Fees
    'security_deposit' => 11000,
    'service_fee' => 250,
    'cleaning_fee' => 150,
    
    // Total
    'total_value' => 66000, // 5500 * 12 months
    'currency' => 'SAR',
]);
```

### Example 2: Short-term Weekly Contract

```php
Contract::create([
    'property_id' => 2,
    'landlord_id' => 1,
    'renter_name' => 'Sara Ali',
    'start_date' => '2025-12-01',
    'end_date' => '2025-12-31',
    
    // Pricing
    'pricing_type' => PricingType::WEEKLY,
    'weekly_rent' => 1500,
    'monthly_rent' => 6495, // 1500 * 4.33
    'yearly_rent' => 77940,
    'daily_rent' => 214.29,
    
    // Additional Fees
    'security_deposit' => 3000,
    'service_fee' => 100,
    'cleaning_fee' => 200,
    
    // Total (4 weeks)
    'total_value' => 6000, // 1500 * 4 weeks
    'currency' => 'SAR',
]);
```

### Example 3: Long-term Yearly Contract

```php
Contract::create([
    'property_id' => 3,
    'landlord_id' => 2,
    'renter_name' => 'Business Corp LLC',
    'renter_company' => 'Business Corp LLC',
    'start_date' => '2025-01-01',
    'end_date' => '2027-12-31',
    
    // Pricing
    'pricing_type' => PricingType::YEARLY,
    'yearly_rent' => 60000,
    'monthly_rent' => 5000,
    'weekly_rent' => 1154.73,
    'daily_rent' => 164.38,
    
    // Additional Fees
    'security_deposit' => 10000,
    'service_fee' => 500,
    'cleaning_fee' => 0,
    
    // Total (3 years)
    'total_value' => 180000, // 60000 * 3 years
    'currency' => 'SAR',
]);
```

## Model Methods

### Getting Active Rent

```php
$contract = Contract::find(1);
$activeRent = $contract->active_rent; // Returns rent based on pricing_type
```

### Duration Calculations

```php
$contract = Contract::find(1);

// Get duration in different units
$days = $contract->duration_in_days;     // e.g., 365
$months = $contract->duration_in_months; // e.g., 12
```

### Status Checks

```php
$contract = Contract::find(1);

// Check contract status
if ($contract->isCurrentlyActive()) {
    echo "Contract is active and within date range";
}

if ($contract->isUpcoming()) {
    echo "Contract starts in the future";
}

if ($contract->isExpired()) {
    echo "Contract has ended";
}
```

## Nova Resource Fields

### Form Layout

The Contract Nova resource organizes fields into logical sections:

1. **Basic Information**
   - Property
   - Landlord
   - Renter details
   - Contract dates

2. **Pricing Information**
   - Pricing type selector
   - Monthly rent
   - Weekly rent
   - Yearly rent
   - Daily rent

3. **Additional Fees**
   - Security deposit
   - Service fee
   - Cleaning fee

4. **Contract Summary**
   - Duration (computed)
   - Active rent (computed)
   - Total value
   - Currency

### Filters

The following filters are available:

- **Pricing Type**: Filter by monthly/weekly/yearly/daily
- **Contract Status**: Active/Completed/Canceled
- **Payment Status**: Not Collected/Partially Collected/Paid/Refunded
- **Landlord**: Filter by landlord
- **Property**: Filter by property
- **Date Ranges**: Start/End date filters
- **Upcoming Bookings**: Contracts starting soon

## Currency Support

### Supported Currencies

- **SAR** - Saudi Riyal (default)
- **USD** - US Dollar
- **EUR** - Euro
- **AED** - UAE Dirham
- **GBP** - British Pound

### Currency in Display

All monetary values are displayed with proper currency formatting in Nova:

```php
Currency::make('Monthly Rent')
    ->currency('USD')
    ->displayUsing(fn ($value) => number_format($value, 2))
```

## Audit Logging

All contract pricing changes are automatically logged:

```php
// Logged fields include:
- pricing_type
- monthly_rent, weekly_rent, yearly_rent, daily_rent
- security_deposit, service_fee, cleaning_fee
- total_value
- currency
```

## Best Practices

### 1. Always Provide All Pricing Options

Even if using monthly pricing, calculate and store all pricing tiers:

```php
$monthlyRent = 5000;

$contract->monthly_rent = $monthlyRent;
$contract->weekly_rent = round($monthlyRent / 4.33, 2);
$contract->yearly_rent = $monthlyRent * 12;
$contract->daily_rent = round($monthlyRent / 30, 2);
```

### 2. Validate Total Value

Ensure total value matches the contract duration:

```php
$months = $startDate->diffInMonths($endDate);
$expectedTotal = $monthlyRent * $months;

if ($contract->total_value != $expectedTotal) {
    throw new \Exception('Total value mismatch');
}
```

### 3. Handle Currency Consistently

Use the same currency throughout the contract:

```php
$contract->currency = 'SAR'; // Use property's default currency
```

### 4. Set Appropriate Security Deposits

Typical security deposit ranges:

- **Monthly contracts**: 1-2x monthly rent
- **Weekly contracts**: 2-4x weekly rent
- **Yearly contracts**: 1x monthly rent or fixed amount

### 5. Document Fees Clearly

Always use the internal notes field to document fee calculations:

```php
$contract->notes_internal = "Security deposit: 2x monthly rent (10,000 SAR). Service fee: Standard platform fee. Cleaning fee: Deep cleaning after checkout.";
```

## Migration Guide

### Running the Migration

```bash
php artisan migrate
```

### Seeding Sample Data

```bash
php artisan db:seed --class=DatabaseSeeder
```

This will create:
- 5 active contracts
- 3 upcoming contracts
- 2 pending contracts

All with complete pricing information.

## Troubleshooting

### Issue: Missing Pricing Fields

**Solution**: Run the migration to add pricing fields:
```bash
php artisan migrate
```

### Issue: Incorrect Total Value

**Solution**: Recalculate based on duration and active rent:
```php
$contract->total_value = $contract->monthly_rent * $contract->duration_in_months;
$contract->save();
```

### Issue: Currency Mismatch

**Solution**: Ensure all related fees use the same currency:
```php
$contract->currency = 'SAR';
```

## Future Enhancements

Potential features for future development:

1. **Dynamic Pricing**: Seasonal pricing adjustments
2. **Discount Codes**: Apply promotional discounts
3. **Payment Plans**: Split payments over contract duration
4. **Price History**: Track pricing changes over time
5. **Automatic Calculations**: Auto-fill derived pricing fields
6. **Currency Conversion**: Real-time currency conversion
7. **Tax Management**: VAT/tax calculation integration

## Support

For questions or issues related to the pricing model:

- Review this documentation
- Check audit logs for pricing history
- Contact the development team

---

**Last Updated**: October 30, 2025  
**Version**: 1.0.0
