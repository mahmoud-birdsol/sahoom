<?php

namespace Tests\Feature\Landlord;

use App\Models\Contract;
use App\Models\Landlord;
use App\Models\Property;
use App\Models\States\ContractStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LandlordBookingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/landlord/bookings')->assertRedirect('/login');
    }

    public function test_user_without_landlord_profile_is_redirected(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(\App\Livewire\Landlord\BookingsIndex::class)
            ->assertRedirect(route('dashboard'));
    }

    public function test_landlord_can_visit_bookings_page(): void
    {
        $landlord = Landlord::factory()->create();
        $this->actingAs($landlord->user);

        $this->get(route('landlord.bookings'))->assertOk();
    }

    public function test_bookings_page_shows_empty_state_when_no_contracts(): void
    {
        $landlord = Landlord::factory()->create();
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\BookingsIndex::class)
            ->assertSee(__('No bookings yet'));
    }

    public function test_bookings_page_lists_contracts_in_list_view(): void
    {
        $landlord = Landlord::factory()->create();
        $property = Property::factory()->create(['landlord_id' => $landlord->id]);
        $contract = Contract::factory()->create([
            'property_id' => $property->id,
            'landlord_id' => $landlord->id,
            'renter_name' => 'Jane Renter',
        ]);
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\BookingsIndex::class)
            ->assertSee('Jane Renter')
            ->assertSee($property->title);
    }

    public function test_bookings_only_show_current_landlord_contracts(): void
    {
        $landlord      = Landlord::factory()->create();
        $otherLandlord = Landlord::factory()->create();
        $otherProperty = Property::factory()->create(['landlord_id' => $otherLandlord->id]);
        Contract::factory()->create([
            'property_id' => $otherProperty->id,
            'landlord_id' => $otherLandlord->id,
            'renter_name' => 'Other Renter',
        ]);
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\BookingsIndex::class)
            ->assertDontSee('Other Renter');
    }

    public function test_can_switch_to_calendar_view(): void
    {
        $landlord = Landlord::factory()->create();
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\BookingsIndex::class)
            ->assertSet('view', 'list')
            ->call('switchView', 'calendar')
            ->assertSet('view', 'calendar');
    }

    public function test_can_switch_back_to_list_view(): void
    {
        $landlord = Landlord::factory()->create();
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\BookingsIndex::class)
            ->call('switchView', 'calendar')
            ->call('switchView', 'list')
            ->assertSet('view', 'list');
    }

    public function test_calendar_month_navigation_goes_forward(): void
    {
        $landlord = Landlord::factory()->create();
        $this->actingAs($landlord->user);

        $expectedMonth = Carbon::now()->addMonth()->format('Y-m');

        Livewire::test(\App\Livewire\Landlord\BookingsIndex::class)
            ->call('nextMonth')
            ->assertSet('calendarMonth', $expectedMonth);
    }

    public function test_calendar_month_navigation_goes_back(): void
    {
        $landlord = Landlord::factory()->create();
        $this->actingAs($landlord->user);

        $expectedMonth = Carbon::now()->subMonth()->format('Y-m');

        Livewire::test(\App\Livewire\Landlord\BookingsIndex::class)
            ->call('prevMonth')
            ->assertSet('calendarMonth', $expectedMonth);
    }

    public function test_opening_details_sets_contract_id_and_shows_modal(): void
    {
        $landlord = Landlord::factory()->create();
        $property = Property::factory()->create(['landlord_id' => $landlord->id]);
        $contract = Contract::factory()->create([
            'property_id' => $property->id,
            'landlord_id' => $landlord->id,
        ]);
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\BookingsIndex::class)
            ->call('openDetails', $contract->id)
            ->assertSet('showDetails', true)
            ->assertSet('detailsContractId', $contract->id);
    }

    public function test_closing_details_hides_modal(): void
    {
        $landlord = Landlord::factory()->create();
        $property = Property::factory()->create(['landlord_id' => $landlord->id]);
        $contract = Contract::factory()->create([
            'property_id' => $property->id,
            'landlord_id' => $landlord->id,
        ]);
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\BookingsIndex::class)
            ->call('openDetails', $contract->id)
            ->call('closeDetails')
            ->assertSet('showDetails', false)
            ->assertSet('detailsContractId', null);
    }
}
