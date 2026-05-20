<?php

namespace Tests\Feature;

use App\Livewire\PropertyShow;
use App\Models\Landlord;
use App\Models\Property;
use App\Models\SiteSetting;
use App\Models\States\PropertyStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PropertyPricingTest extends TestCase
{
    use RefreshDatabase;

    // ── SiteSetting model ─────────────────────────────────────────────────────

    public function test_site_setting_can_be_set_and_retrieved(): void
    {
        SiteSetting::set('contact_email', 'test@example.com', 'contact');

        $this->assertSame('test@example.com', SiteSetting::get('contact_email'));
    }

    public function test_site_setting_get_returns_default_when_missing(): void
    {
        $this->assertSame('fallback', SiteSetting::get('nonexistent_key', 'fallback'));
    }

    public function test_site_setting_set_upserts_existing_key(): void
    {
        SiteSetting::set('site_name', 'FIRST');
        SiteSetting::set('site_name', 'SECOND');

        $this->assertSame('SECOND', SiteSetting::get('site_name'));
        $this->assertDatabaseCount('site_settings', 1);
    }

    // ── Property model new fields ──────────────────────────────────────────────

    public function test_property_stores_and_retrieves_pricing_fields(): void
    {
        $landlord = Landlord::factory()->create();

        $property = Property::factory()->create([
            'landlord_id'      => $landlord->id,
            'currency'         => 'SAR',
            'security_deposit' => 5000.00,
            'application_fee'  => 150.00,
            'min_lease_months' => 3,
            'max_lease_months' => 12,
            'nearby_places'    => ['Metro Station' => '2 min walk', 'Mall' => '5 min drive'],
        ]);

        $fresh = $property->fresh();

        $this->assertSame('SAR', $fresh->currency);
        $this->assertEqualsWithDelta(5000.00, $fresh->security_deposit, 0.01);
        $this->assertEqualsWithDelta(150.00, $fresh->application_fee, 0.01);
        $this->assertSame(3, $fresh->min_lease_months);
        $this->assertSame(12, $fresh->max_lease_months);
        $this->assertEquals(['Metro Station' => '2 min walk', 'Mall' => '5 min drive'], $fresh->nearby_places);
    }

    // ── PropertyShow computed properties ──────────────────────────────────────

    public function test_property_show_uses_db_security_deposit(): void
    {
        $property = Property::factory()->approved()->create([
            'monthly_rent'     => 3000,
            'security_deposit' => 6000,
        ]);

        Livewire::test(PropertyShow::class, ['slug' => $property->slug])
            ->assertSet('property.security_deposit', 6000.0);
    }

    public function test_property_show_defaults_security_deposit_to_monthly_rent_when_null(): void
    {
        $property = Property::factory()->approved()->create([
            'monthly_rent'     => 4000,
            'security_deposit' => null,
        ]);

        $component = Livewire::test(PropertyShow::class, ['slug' => $property->slug]);

        $this->assertEqualsWithDelta(4000.0, $component->get('securityDeposit'), 0.01);
    }

    public function test_property_show_uses_db_application_fee(): void
    {
        $property = Property::factory()->approved()->create([
            'monthly_rent'    => 3000,
            'application_fee' => 250,
        ]);

        $component = Livewire::test(PropertyShow::class, ['slug' => $property->slug]);

        $this->assertEqualsWithDelta(250.0, $component->get('applicationFee'), 0.01);
    }

    public function test_property_show_application_fee_defaults_to_zero_when_null(): void
    {
        $property = Property::factory()->approved()->create([
            'monthly_rent'    => 3000,
            'application_fee' => null,
        ]);

        $component = Livewire::test(PropertyShow::class, ['slug' => $property->slug]);

        $this->assertEqualsWithDelta(0.0, $component->get('applicationFee'), 0.01);
    }

    public function test_property_show_currency_symbol_usd(): void
    {
        $property = Property::factory()->approved()->create(['currency' => 'USD']);

        $component = Livewire::test(PropertyShow::class, ['slug' => $property->slug]);

        $this->assertSame('$', $component->get('currencySymbol'));
    }

    public function test_property_show_currency_symbol_sar(): void
    {
        $property = Property::factory()->approved()->create(['currency' => 'SAR']);

        $component = Livewire::test(PropertyShow::class, ['slug' => $property->slug]);

        $this->assertSame('SAR ', $component->get('currencySymbol'));
    }

    public function test_total_due_at_signing_uses_display_price_when_no_dates_selected_monthly(): void
    {
        $property = Property::factory()->approved()->create([
            'pricing_type'     => 'monthly',
            'monthly_rent'     => 3000,
            'security_deposit' => 100,
            'application_fee'  => 10,
        ]);

        $component = Livewire::test(PropertyShow::class, ['slug' => $property->slug]);

        // displayPrice = monthly_rent = 3000; total = 3000 + 100 + 10 = 3110
        $this->assertEqualsWithDelta(3110.0, $component->get('totalDueAtSigning'), 0.01);
    }

    public function test_total_due_at_signing_uses_daily_rent_not_derived_monthly_when_no_dates_selected(): void
    {
        $property = Property::factory()->approved()->create([
            'pricing_type'     => 'daily',
            'monthly_rent'     => null,
            'daily_rent'       => 100,
            'security_deposit' => 200,
            'application_fee'  => 50,
        ]);

        $component = Livewire::test(PropertyShow::class, ['slug' => $property->slug]);

        // displayPrice = daily_rent = 100, NOT derived 100*30=3000
        $this->assertEqualsWithDelta(350.0, $component->get('totalDueAtSigning'), 0.01);
    }

    public function test_tiered_calculation_days_only(): void
    {
        $property = Property::factory()->approved()->create([
            'pricing_type'     => 'daily',
            'daily_rent'       => 100,
            'weekly_rent'      => null,
            'monthly_rent'     => null,
            'security_deposit' => 0,
            'application_fee'  => 0,
        ]);

        $start = now()->addDay()->toDateString();
        $end   = now()->addDays(6)->toDateString(); // 5 days

        $component = Livewire::test(PropertyShow::class, ['slug' => $property->slug])
            ->set('moveInDate', $start)
            ->set('bookingEndDate', $end);

        // 5 days × $100 = $500
        $this->assertEqualsWithDelta(500.0, $component->get('totalDueAtSigning'), 0.01);
    }

    public function test_tiered_calculation_weeks_plus_days(): void
    {
        $property = Property::factory()->approved()->create([
            'pricing_type'     => 'daily',
            'daily_rent'       => 50,
            'weekly_rent'      => 300,
            'monthly_rent'     => null,
            'security_deposit' => 0,
            'application_fee'  => 0,
        ]);

        $start = now()->addDay()->toDateString();
        $end   = now()->addDays(19)->toDateString(); // 18 days → 2 weeks + 4 days

        $component = Livewire::test(PropertyShow::class, ['slug' => $property->slug])
            ->set('moveInDate', $start)
            ->set('bookingEndDate', $end);

        // 2 weeks × $300 + 4 days × $50 = $600 + $200 = $800
        $this->assertEqualsWithDelta(800.0, $component->get('totalDueAtSigning'), 0.01);
    }

    public function test_tiered_calculation_months_plus_days(): void
    {
        $property = Property::factory()->approved()->create([
            'pricing_type'     => 'monthly',
            'daily_rent'       => 50,
            'weekly_rent'      => null,
            'monthly_rent'     => 1500,
            'security_deposit' => 0,
            'application_fee'  => 0,
        ]);

        $start = now()->addDay()->toDateString();
        $end   = now()->addDays(34)->toDateString(); // 33 days → 1 month + 3 days

        $component = Livewire::test(PropertyShow::class, ['slug' => $property->slug])
            ->set('moveInDate', $start)
            ->set('bookingEndDate', $end);

        // 1 month × $1500 + 3 days × $50 = $1500 + $150 = $1650
        $this->assertEqualsWithDelta(1650.0, $component->get('totalDueAtSigning'), 0.01);
    }

    public function test_tiered_calculation_months_weeks_days(): void
    {
        $property = Property::factory()->approved()->create([
            'pricing_type'     => 'monthly',
            'daily_rent'       => 50,
            'weekly_rent'      => 300,
            'monthly_rent'     => 1500,
            'security_deposit' => 0,
            'application_fee'  => 0,
        ]);

        $start = now()->addDay()->toDateString();
        $end   = now()->addDays(46)->toDateString(); // 45 days → 1 month + 2 weeks + 1 day

        $component = Livewire::test(PropertyShow::class, ['slug' => $property->slug])
            ->set('moveInDate', $start)
            ->set('bookingEndDate', $end);

        // 1 month × $1500 + 2 weeks × $300 + 1 day × $50 = $1500 + $600 + $50 = $2150
        $this->assertEqualsWithDelta(2150.0, $component->get('totalDueAtSigning'), 0.01);
    }

    // ── Landlord form saves new fields ────────────────────────────────────────

    public function test_landlord_can_create_property_with_pricing_fields(): void
    {
        $landlord = Landlord::factory()->create();
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\PropertiesIndex::class)
            ->set('formTitle', 'Test Property')
            ->set('formAddress', '123 Main St')
            ->set('formPricingType', 'monthly')
            ->set('formMonthlyRent', 3500)
            ->set('formCurrency', 'SAR')
            ->set('formSecurityDeposit', 7000)
            ->set('formApplicationFee', 200)
            ->call('saveProperty');

        $property = Property::where('title', 'Test Property')->firstOrFail();

        $this->assertSame('SAR', $property->currency);
        $this->assertEqualsWithDelta(3500.0, $property->monthly_rent, 0.01);
        $this->assertEqualsWithDelta(7000.0, $property->security_deposit, 0.01);
        $this->assertEqualsWithDelta(200.0, $property->application_fee, 0.01);
    }

    public function test_landlord_currency_validation_rejects_unknown_currency(): void
    {
        $landlord = Landlord::factory()->create();
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\PropertiesIndex::class)
            ->set('formTitle', 'Test Property')
            ->set('formAddress', '123 Main St')
            ->set('formPricingType', 'monthly')
            ->set('formMonthlyRent', 3500)
            ->set('formCurrency', 'INVALID_CURRENCY_VALUE_THAT_EXCEEDS_MAX_LENGTH_OF_10_CHARS')
            ->call('saveProperty')
            ->assertHasErrors(['formCurrency']);
    }
}
