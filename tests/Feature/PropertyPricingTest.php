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

    public function test_property_show_lease_duration_options_respect_min_max(): void
    {
        $property = Property::factory()->approved()->create([
            'min_lease_months' => 3,
            'max_lease_months' => 12,
        ]);

        $component = Livewire::test(PropertyShow::class, ['slug' => $property->slug]);
        $options   = $component->get('leaseDurationOptions');

        $this->assertContains(3, $options);
        $this->assertContains(6, $options);
        $this->assertContains(12, $options);
        $this->assertNotContains(1, $options);
        $this->assertNotContains(24, $options);
    }

    public function test_property_show_lease_duration_options_default_range(): void
    {
        $property = Property::factory()->approved()->create([
            'min_lease_months' => null,
            'max_lease_months' => null,
        ]);

        $component = Livewire::test(PropertyShow::class, ['slug' => $property->slug]);
        $options   = $component->get('leaseDurationOptions');

        $this->assertNotEmpty($options);
        $this->assertContains(1, $options);
        $this->assertContains(24, $options);
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
            ->set('formPrice', 3500)
            ->set('formCurrency', 'SAR')
            ->set('formSecurityDeposit', 7000)
            ->set('formApplicationFee', 200)
            ->set('formMinLeaseMonths', 3)
            ->set('formMaxLeaseMonths', 12)
            ->call('saveProperty');

        $property = Property::where('title', 'Test Property')->firstOrFail();

        $this->assertSame('SAR', $property->currency);
        $this->assertEqualsWithDelta(7000.0, $property->security_deposit, 0.01);
        $this->assertEqualsWithDelta(200.0, $property->application_fee, 0.01);
        $this->assertSame(3, $property->min_lease_months);
        $this->assertSame(12, $property->max_lease_months);
    }

    public function test_landlord_currency_validation_rejects_unknown_currency(): void
    {
        $landlord = Landlord::factory()->create();
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\PropertiesIndex::class)
            ->set('formTitle', 'Test Property')
            ->set('formAddress', '123 Main St')
            ->set('formPricingType', 'monthly')
            ->set('formPrice', 3500)
            ->set('formCurrency', 'INVALID_CURRENCY_VALUE_THAT_EXCEEDS_MAX_LENGTH_OF_10_CHARS')
            ->call('saveProperty')
            ->assertHasErrors(['formCurrency']);
    }
}
