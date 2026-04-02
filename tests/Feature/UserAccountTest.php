<?php

namespace Tests\Feature;

use App\Livewire\UserAccount;
use App\Models\Contract;
use App\Models\Landlord;
use App\Models\Property;
use App\Models\PropertyFavorite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class UserAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('account'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_visit_account_page(): void
    {
        $user = User::factory()->withoutTwoFactor()->create();

        Livewire::actingAs($user)
            ->test(UserAccount::class)
            ->assertOk()
            ->assertSet('section', 'profile');
    }

    public function test_profile_section_is_pre_filled_with_user_data(): void
    {
        $user = User::factory()->withoutTwoFactor()->create([
            'name'  => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '+1234567890',
        ]);

        Livewire::actingAs($user)
            ->test(UserAccount::class)
            ->assertSet('name', 'Jane Doe')
            ->assertSet('email', 'jane@example.com')
            ->assertSet('phone', '+1234567890');
    }

    public function test_user_can_update_profile(): void
    {
        $user = User::factory()->withoutTwoFactor()->create(['name' => 'Old Name']);

        Livewire::actingAs($user)
            ->test(UserAccount::class)
            ->set('name', 'New Name')
            ->set('email', $user->email)
            ->call('saveProfile')
            ->assertSet('profileSaved', true);

        $this->assertEquals('New Name', $user->fresh()->name);
    }

    public function test_profile_update_requires_name_and_email(): void
    {
        $user = User::factory()->withoutTwoFactor()->create();

        Livewire::actingAs($user)
            ->test(UserAccount::class)
            ->set('name', '')
            ->set('email', 'not-an-email')
            ->call('saveProfile')
            ->assertHasErrors(['name', 'email']);
    }

    public function test_user_can_change_password(): void
    {
        $user = User::factory()->withoutTwoFactor()->create([
            'password' => Hash::make('OldPassword1!'),
        ]);

        Livewire::actingAs($user)
            ->test(UserAccount::class)
            ->call('setSection', 'password')
            ->set('currentPassword', 'OldPassword1!')
            ->set('newPassword', 'NewPassword1!')
            ->set('confirmPassword', 'NewPassword1!')
            ->call('changePassword')
            ->assertSet('passwordSaved', true)
            ->assertSet('passwordError', null);

        $this->assertTrue(Hash::check('NewPassword1!', $user->fresh()->password));
    }

    public function test_change_password_fails_with_wrong_current_password(): void
    {
        $user = User::factory()->withoutTwoFactor()->create([
            'password' => Hash::make('CorrectPassword1!'),
        ]);

        Livewire::actingAs($user)
            ->test(UserAccount::class)
            ->call('setSection', 'password')
            ->set('currentPassword', 'WrongPassword!')
            ->set('newPassword', 'NewPassword1!')
            ->set('confirmPassword', 'NewPassword1!')
            ->call('changePassword')
            ->assertSet('passwordSaved', false)
            ->assertSet('passwordError', __('The current password is incorrect.'));
    }

    public function test_rents_section_shows_contracts_for_user_email(): void
    {
        $user     = User::factory()->withoutTwoFactor()->create(['email' => 'renter@test.com']);
        $landlord = Landlord::factory()->create();
        $property = Property::factory()->for($landlord)->create();

        Contract::factory()->for($property)->for($landlord)->create([
            'renter_email' => 'renter@test.com',
        ]);

        $component = Livewire::actingAs($user)
            ->test(UserAccount::class)
            ->call('setSection', 'rents');

        $this->assertCount(1, $component->get('rents'));
    }

    public function test_rents_does_not_show_other_users_contracts(): void
    {
        $user     = User::factory()->withoutTwoFactor()->create(['email' => 'me@test.com']);
        $landlord = Landlord::factory()->create();
        $property = Property::factory()->for($landlord)->create();

        Contract::factory()->for($property)->for($landlord)->create([
            'renter_email' => 'someone-else@test.com',
        ]);

        $component = Livewire::actingAs($user)
            ->test(UserAccount::class)
            ->call('setSection', 'rents');

        $this->assertCount(0, $component->get('rents'));
    }

    public function test_favorites_section_shows_user_favorites(): void
    {
        $user     = User::factory()->withoutTwoFactor()->create();
        $landlord = Landlord::factory()->create();
        $property = Property::factory()->for($landlord)->create();

        PropertyFavorite::factory()->create([
            'user_id'     => $user->id,
            'property_id' => $property->id,
        ]);

        $component = Livewire::actingAs($user)
            ->test(UserAccount::class)
            ->call('setSection', 'favorites');

        $this->assertCount(1, $component->get('favorites'));
    }

    public function test_user_can_remove_a_favorite(): void
    {
        $user     = User::factory()->withoutTwoFactor()->create();
        $landlord = Landlord::factory()->create();
        $property = Property::factory()->for($landlord)->create();

        PropertyFavorite::factory()->create([
            'user_id'     => $user->id,
            'property_id' => $property->id,
        ]);

        Livewire::actingAs($user)
            ->test(UserAccount::class)
            ->call('setSection', 'favorites')
            ->call('removeFavorite', $property->id);

        $this->assertDatabaseMissing('property_favorites', [
            'user_id'     => $user->id,
            'property_id' => $property->id,
        ]);
    }

    public function test_section_can_be_switched_via_url_parameter(): void
    {
        $user = User::factory()->withoutTwoFactor()->create();

        Livewire::actingAs($user)
            ->test(UserAccount::class, ['section' => 'favorites'])
            ->assertSet('section', 'favorites');
    }
}
