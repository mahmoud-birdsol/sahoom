<?php

namespace Tests\Feature\Landlord;

use App\Models\Landlord;
use App\Models\Property;
use App\Models\PropertyFavorite;
use App\Models\PropertyReview;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LandlordPropertiesTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/landlord/properties')->assertRedirect('/login');
    }

    public function test_user_without_landlord_profile_is_redirected(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(\App\Livewire\Landlord\PropertiesIndex::class)
            ->assertRedirect(route('dashboard'));
    }

    public function test_landlord_user_can_visit_properties_page(): void
    {
        $landlord = Landlord::factory()->create();
        $this->actingAs($landlord->user);

        $this->get('/landlord/properties')->assertStatus(200);
    }

    public function test_properties_page_shows_empty_state_when_no_properties(): void
    {
        $landlord = Landlord::factory()->create();
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\PropertiesIndex::class)
            ->assertSee(__('No properties yet'));
    }

    public function test_properties_page_lists_landlord_properties(): void
    {
        $landlord = Landlord::factory()->create();
        Property::factory()->count(3)->create(['landlord_id' => $landlord->id]);
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\PropertiesIndex::class)
            ->assertViewHas('properties', fn ($p) => $p->total() === 3);
    }

    public function test_properties_are_paginated_in_groups_of_nine(): void
    {
        $landlord = Landlord::factory()->create();
        Property::factory()->count(10)->create(['landlord_id' => $landlord->id]);
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\PropertiesIndex::class)
            ->assertViewHas('properties', fn ($p) => $p->count() === 9 && $p->total() === 10);
    }

    public function test_properties_only_show_current_landlord_properties(): void
    {
        $landlord      = Landlord::factory()->create();
        $otherLandlord = Landlord::factory()->create();

        Property::factory()->count(2)->create(['landlord_id' => $landlord->id]);
        Property::factory()->count(5)->create(['landlord_id' => $otherLandlord->id]);

        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\PropertiesIndex::class)
            ->assertViewHas('properties', fn ($p) => $p->total() === 2);
    }

    public function test_property_review_can_be_created(): void
    {
        $user     = User::factory()->create();
        $property = Property::factory()->create();

        $review = PropertyReview::factory()->create([
            'user_id'     => $user->id,
            'property_id' => $property->id,
            'rating'      => 5,
            'review'      => 'Excellent property!',
        ]);

        $this->assertEquals(5, $review->rating);
        $this->assertDatabaseHas('property_reviews', [
            'user_id'     => $user->id,
            'property_id' => $property->id,
            'rating'      => 5,
        ]);
    }

    public function test_property_favorite_can_be_created(): void
    {
        $user     = User::factory()->create();
        $property = Property::factory()->create();

        PropertyFavorite::factory()->create([
            'user_id'     => $user->id,
            'property_id' => $property->id,
        ]);

        $this->assertDatabaseHas('property_favorites', [
            'user_id'     => $user->id,
            'property_id' => $property->id,
        ]);
    }

    public function test_property_average_rating_is_computed_correctly(): void
    {
        $property = Property::factory()->create();

        PropertyReview::factory()->create(['property_id' => $property->id, 'rating' => 4]);
        PropertyReview::factory()->create(['property_id' => $property->id, 'rating' => 5]);

        $this->assertEquals(4.5, $property->averageRating());
    }

    public function test_opening_create_form_shows_modal(): void
    {
        $landlord = Landlord::factory()->create();
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\PropertiesIndex::class)
            ->assertSet('showPropertyForm', false)
            ->call('openCreateForm')
            ->assertSet('showPropertyForm', true)
            ->assertSet('isEditing', false);
    }

    public function test_closing_form_hides_modal_and_resets_state(): void
    {
        $landlord = Landlord::factory()->create();
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\PropertiesIndex::class)
            ->call('openCreateForm')
            ->set('formTitle', 'Test')
            ->call('closeForm')
            ->assertSet('showPropertyForm', false)
            ->assertSet('formTitle', '');
    }

    public function test_landlord_can_create_a_property(): void
    {
        $landlord = Landlord::factory()->create();
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\PropertiesIndex::class)
            ->call('openCreateForm')
            ->set('formTitle', 'My Retail Space')
            ->set('formAddress', '123 Main St, New York, NY')
            ->set('formPricingType', 'monthly')
            ->set('formMonthlyRent', 2500)
            ->set('formDescription', 'A great retail space.')
            ->call('saveProperty')
            ->assertSet('showPropertyForm', false);

        $this->assertDatabaseHas('properties', [
            'title'          => 'My Retail Space',
            'address_line_1' => '123 Main St, New York, NY',
            'monthly_rent'   => 2500,
            'landlord_id'    => $landlord->id,
        ]);
    }

    public function test_create_property_requires_title_and_address(): void
    {
        $landlord = Landlord::factory()->create();
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\PropertiesIndex::class)
            ->call('openCreateForm')
            ->set('formPricingType', 'monthly')
            ->set('formMonthlyRent', 1000)
            ->call('saveProperty')
            ->assertHasErrors(['formTitle', 'formAddress']);
    }

    public function test_opening_edit_form_populates_fields(): void
    {
        $landlord = Landlord::factory()->create();
        $property = Property::factory()->create([
            'landlord_id'    => $landlord->id,
            'title'          => 'Existing Property',
            'address_line_1' => '456 Market St',
            'pricing_type'   => 'monthly',
            'monthly_rent'   => 1800,
        ]);
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\PropertiesIndex::class)
            ->call('openEditForm', $property->id)
            ->assertSet('showPropertyForm', true)
            ->assertSet('isEditing', true)
            ->assertSet('formTitle', 'Existing Property')
            ->assertSet('formAddress', '456 Market St')
            ->assertSet('formMonthlyRent', 1800.0);
    }

    public function test_landlord_can_update_a_property(): void
    {
        $landlord = Landlord::factory()->create();
        $property = Property::factory()->create([
            'landlord_id'  => $landlord->id,
            'pricing_type' => 'monthly',
        ]);
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\PropertiesIndex::class)
            ->call('openEditForm', $property->id)
            ->set('formTitle', 'Updated Title')
            ->set('formAddress', 'Updated Address')
            ->set('formPricingType', 'monthly')
            ->set('formMonthlyRent', 3000)
            ->call('saveProperty')
            ->assertSet('showPropertyForm', false);

        $this->assertDatabaseHas('properties', [
            'id'             => $property->id,
            'title'          => 'Updated Title',
            'address_line_1' => 'Updated Address',
            'monthly_rent'   => 3000,
        ]);
    }

    public function test_landlord_can_delete_their_property(): void
    {
        $landlord = Landlord::factory()->create();
        $property = Property::factory()->create(['landlord_id' => $landlord->id]);
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\PropertiesIndex::class)
            ->call('deleteProperty', $property->id);

        $this->assertDatabaseMissing('properties', ['id' => $property->id]);
    }

    public function test_landlord_cannot_delete_another_landlords_property(): void
    {
        $landlord      = Landlord::factory()->create();
        $otherLandlord = Landlord::factory()->create();
        $property      = Property::factory()->create(['landlord_id' => $otherLandlord->id]);
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\PropertiesIndex::class)
            ->call('deleteProperty', $property->id)
            ->assertForbidden();
    }

    public function test_opening_details_sets_property_id_and_shows_modal(): void
    {
        $landlord = Landlord::factory()->create();
        $property = Property::factory()->create(['landlord_id' => $landlord->id]);
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\PropertiesIndex::class)
            ->call('openDetails', $property->id)
            ->assertSet('showPropertyDetails', true)
            ->assertSet('detailsPropertyId', $property->id);
    }

    public function test_closing_details_hides_modal(): void
    {
        $landlord = Landlord::factory()->create();
        $property = Property::factory()->create(['landlord_id' => $landlord->id]);
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\PropertiesIndex::class)
            ->call('openDetails', $property->id)
            ->call('closeDetails')
            ->assertSet('showPropertyDetails', false)
            ->assertSet('detailsPropertyId', null);
    }

    public function test_landlord_can_publish_a_draft_property(): void
    {
        $landlord = Landlord::factory()->create();
        $property = Property::factory()->create(['landlord_id' => $landlord->id]);
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\PropertiesIndex::class)
            ->call('publishProperty', $property->id);

        $this->assertDatabaseHas('properties', [
            'id'     => $property->id,
            'status' => \App\Models\States\PropertyStatus::APPROVED->value,
        ]);
    }

    public function test_landlord_cannot_publish_another_landlords_property(): void
    {
        $landlord      = Landlord::factory()->create();
        $otherLandlord = Landlord::factory()->create();
        $property      = Property::factory()->create(['landlord_id' => $otherLandlord->id]);
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\PropertiesIndex::class)
            ->call('publishProperty', $property->id)
            ->assertForbidden();
    }

    public function test_landlord_can_deactivate_an_active_property(): void
    {
        $landlord = Landlord::factory()->create();
        $property = Property::factory()->create(['landlord_id' => $landlord->id, 'is_active' => true]);
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\PropertiesIndex::class)
            ->call('toggleActiveProperty', $property->id);

        $this->assertDatabaseHas('properties', ['id' => $property->id, 'is_active' => false]);
    }

    public function test_landlord_can_reactivate_an_inactive_property(): void
    {
        $landlord = Landlord::factory()->create();
        $property = Property::factory()->inactive()->create(['landlord_id' => $landlord->id]);
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\PropertiesIndex::class)
            ->call('toggleActiveProperty', $property->id);

        $this->assertDatabaseHas('properties', ['id' => $property->id, 'is_active' => true]);
    }

    public function test_landlord_cannot_toggle_another_landlords_property(): void
    {
        $landlord      = Landlord::factory()->create();
        $otherLandlord = Landlord::factory()->create();
        $property      = Property::factory()->create(['landlord_id' => $otherLandlord->id]);
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\PropertiesIndex::class)
            ->call('toggleActiveProperty', $property->id)
            ->assertForbidden();
    }

    public function test_property_defaults_to_active_when_created(): void
    {
        $property = Property::factory()->create();

        $this->assertTrue($property->is_active);
    }

    public function test_property_factory_approved_state_sets_status(): void
    {
        $property = Property::factory()->approved()->create();

        $this->assertEquals(\App\Models\States\PropertyStatus::APPROVED, $property->status);
    }

    public function test_property_factory_inactive_state_sets_is_active_false(): void
    {
        $property = Property::factory()->inactive()->create();

        $this->assertFalse($property->is_active);
    }
}
