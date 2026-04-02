<?php

namespace Tests\Feature\Landlord;

use App\Models\Landlord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LandlordDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/landlord/dashboard')->assertRedirect('/login');
    }

    public function test_user_without_landlord_profile_is_redirected(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(\App\Livewire\Landlord\Dashboard::class)
            ->assertRedirect(route('dashboard'));
    }

    public function test_landlord_user_can_visit_landlord_dashboard(): void
    {
        $landlord = Landlord::factory()->create();

        $this->actingAs($landlord->user);

        $this->get('/landlord/dashboard')->assertStatus(200);
    }

    public function test_landlord_dashboard_metrics_renders_after_lazy_load(): void
    {
        $landlord = Landlord::factory()->create();

        $this->actingAs($landlord->user);

        $component = Livewire::test(\App\Livewire\Landlord\DashboardMetrics::class);

        preg_match('/\$wire\.__lazyLoad\(&#039;([^\']+)&#039;\)/', $component->html(), $matches);
        $snapshot = html_entity_decode($matches[1] ?? '');

        $component->call('__lazyLoad', $snapshot)
            ->assertSee(__('Total Properties'))
            ->assertSee(__('Occupied Units'));
    }

    public function test_landlord_dashboard_metrics_counts_properties_correctly(): void
    {
        $landlord = Landlord::factory()->create();
        \App\Models\Property::factory()->count(3)->create(['landlord_id' => $landlord->id]);

        $this->actingAs($landlord->user);

        $component = new \App\Livewire\Landlord\DashboardMetrics();
        $component->mount();

        $this->assertEquals(3, $component->totalProperties);
        $this->assertEquals(0, $component->occupiedUnits);
    }
}
