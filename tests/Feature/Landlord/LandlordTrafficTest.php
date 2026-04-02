<?php

namespace Tests\Feature\Landlord;

use App\Models\Landlord;
use App\Models\Property;
use App\Models\PropertyVisit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LandlordTrafficTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/landlord/traffic')->assertRedirect('/login');
    }

    public function test_user_without_landlord_profile_is_redirected(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(\App\Livewire\Landlord\TrafficIndex::class)
            ->assertRedirect(route('dashboard'));
    }

    public function test_landlord_can_visit_traffic_page(): void
    {
        $landlord = Landlord::factory()->create();
        $this->actingAs($landlord->user);

        $this->get(route('landlord.traffic'))->assertOk();
    }

    public function test_traffic_page_shows_empty_state_when_no_properties(): void
    {
        $landlord = Landlord::factory()->create();
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\TrafficIndex::class)
            ->assertSee(__('No properties yet'));
    }

    public function test_traffic_page_lists_landlord_properties(): void
    {
        $landlord = Landlord::factory()->create();
        $property = Property::factory()->create(['landlord_id' => $landlord->id]);
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\TrafficIndex::class)
            ->assertSee($property->title);
    }

    public function test_traffic_only_shows_current_landlord_properties(): void
    {
        $landlord      = Landlord::factory()->create();
        $otherLandlord = Landlord::factory()->create();
        $otherProperty = Property::factory()->create(['landlord_id' => $otherLandlord->id]);
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\TrafficIndex::class)
            ->assertDontSee($otherProperty->title);
    }

    public function test_visit_count_reflects_recorded_visits(): void
    {
        $landlord = Landlord::factory()->create();
        $property = Property::factory()->create(['landlord_id' => $landlord->id]);

        PropertyVisit::insert([
            ['property_id' => $property->id, 'source' => 'direct',  'ip_address' => '1.1.1.1', 'visited_at' => now()],
            ['property_id' => $property->id, 'source' => 'search',  'ip_address' => '1.1.1.2', 'visited_at' => now()],
            ['property_id' => $property->id, 'source' => 'referral','ip_address' => '1.1.1.3', 'visited_at' => now()],
        ]);

        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\TrafficIndex::class)
            ->assertSee('3');
    }

    public function test_period_filter_defaults_to_30_days(): void
    {
        $landlord = Landlord::factory()->create();
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\TrafficIndex::class)
            ->assertSet('period', '30');
    }

    public function test_period_filter_can_be_changed(): void
    {
        $landlord = Landlord::factory()->create();
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\TrafficIndex::class)
            ->set('period', '7')
            ->assertSet('period', '7');
    }

    public function test_old_visits_excluded_by_period_filter(): void
    {
        $landlord = Landlord::factory()->create();
        $property = Property::factory()->create(['landlord_id' => $landlord->id]);

        PropertyVisit::insert([
            ['property_id' => $property->id, 'source' => 'direct', 'ip_address' => '1.1.1.1', 'visited_at' => now()->subDays(60)],
        ]);

        $this->actingAs($landlord->user);

        $component = Livewire::test(\App\Livewire\Landlord\TrafficIndex::class)
            ->set('period', '30');

        $this->assertEquals(0, $component->get('totals')['views']);
    }
}
