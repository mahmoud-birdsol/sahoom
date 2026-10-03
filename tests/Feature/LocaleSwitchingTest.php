<?php

namespace Tests\Feature;

use Tests\TestCase;

class LocaleSwitchingTest extends TestCase
{
    public function test_locale_switch_route_stores_locale_in_session(): void
    {
        $response = $this->post(route('locale.switch'), ['locale' => 'fr']);

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'fr');
    }

    public function test_locale_switch_route_accepts_english(): void
    {
        $response = $this->post(route('locale.switch'), ['locale' => 'en']);

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'en');
    }

    public function test_set_locale_middleware_applies_locale_from_session(): void
    {
        $response = $this->withSession(['locale' => 'fr'])->get('/');

        $response->assertStatus(200);
        $this->assertEquals('fr', app()->getLocale());
    }

    public function test_first_visit_uses_french_by_default(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $this->assertEquals('fr', app()->getLocale());
        $response->assertSee('lang="fr"', false);
        $response->assertSee(__('What are you looking for?'));
    }

    public function test_saved_english_locale_overrides_french_default(): void
    {
        $response = $this->withSession(['locale' => 'en'])->get('/');

        $response->assertStatus(200);
        $this->assertEquals('en', app()->getLocale());
        $response->assertSee('lang="en"', false);
    }

    public function test_french_translation_returns_correct_string(): void
    {
        app()->setLocale('fr');

        $this->assertEquals('Accueil', __('Home'));
        $this->assertEquals('Propriétés', __('Properties'));
        $this->assertEquals('Se connecter', __('Log in'));
    }

    public function test_english_translation_returns_original_string(): void
    {
        app()->setLocale('en');

        $this->assertEquals('Home', __('Home'));
        $this->assertEquals('Properties', __('Properties'));
        $this->assertEquals('Log in', __('Log in'));
    }

    public function test_locale_switcher_is_accessible_to_guests(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee(route('locale.switch'), false);
    }
}
