<?php

namespace Tests\Feature\Landlord;

use App\Models\Landlord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandlordMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    // ── EnsureUserIsLandlord middleware ───────────────────────────────────────

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $this->get(route('landlord.dashboard'))->assertRedirect(route('login'));
    }

    public function test_authenticated_non_landlord_is_redirected_to_dashboard(): void
    {
        $this->actingAs(User::factory()->create());

        foreach (['landlord.dashboard', 'landlord.properties', 'landlord.bookings', 'landlord.traffic', 'landlord.notifications'] as $route) {
            $this->get(route($route))->assertRedirect(route('dashboard'));
        }
    }

    public function test_authenticated_landlord_can_access_landlord_routes(): void
    {
        $landlord = Landlord::factory()->create();
        $this->actingAs($landlord->user);

        $this->get(route('landlord.dashboard'))->assertOk();
    }

    // ── Fortify login redirect ─────────────────────────────────────────────────

    public function test_landlord_user_is_redirected_to_landlord_dashboard_after_login(): void
    {
        $user     = User::factory()->withoutTwoFactor()->create();
        $landlord = Landlord::factory()->create(['user_id' => $user->id]);

        $this->post(route('login.store'), [
            'email'    => $landlord->user->email,
            'password' => 'password',
        ])->assertRedirect(route('landlord.dashboard'));
    }

    public function test_non_landlord_user_is_redirected_to_default_dashboard_after_login(): void
    {
        $user = User::factory()->withoutTwoFactor()->create();

        $this->post(route('login.store'), [
            'email'    => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('properties.index'));
    }
}
