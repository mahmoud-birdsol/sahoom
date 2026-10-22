<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_about_page_reproduces_company_presentation_with_only_current_services(): void
    {
        $response = $this->withSession(['locale' => 'fr'])->get('/about');

        $response->assertOk();
        $response->assertSee('À propos de Sahoome');
        $response->assertSeeInOrder([
            'Notre vision',
            'Notre positionnement',
            'Nos activités',
            'Notre ambition',
            'Nos engagements',
            'Ils nous font confiance',
        ]);
        $response->assertSee('Location <em>courte durée</em>', false);
        $response->assertSee('Espaces <em>flexibles</em>', false);
        $response->assertDontSee('longue durée');
        $response->assertDontSee('Vente');
        $response->assertDontSee('vente');
        $response->assertDontSee('vendue');
        $response->assertDontSee('location.html');
        $response->assertDontSee('contact.html');
    }

    public function test_homepage_shows_only_flexible_spaces_and_short_term_rentals(): void
    {
        $response = $this->withSession(['locale' => 'fr'])->get('/');

        $response->assertOk();
        $response->assertSeeInOrder([
            '>01</div>',
            'Espaces flexibles',
            '>02</div>',
            'Location courte durée',
        ], false);
        $response->assertDontSee('>03</div>', false);
        $response->assertDontSee('longue durée');
        $response->assertDontSee('Nos autres services');
        $response->assertSee('?isShortTerm=1&propertyType=commercial', false);
    }

    public function test_homepage_shows_short_term_service_sections_after_the_property_selection(): void
    {
        $response = $this->withSession(['locale' => 'fr'])->get('/');

        $response->assertOk();
        $response->assertSeeInOrder([
            'Notre sélection',
            '01 — Baux commerciaux courte durées',
            'Voir les locaux disponibles',
            '02 — Location courte durée',
            'Transfert aéroport inclus',
            'Chauffeur privé disponible',
            'Conciergerie dédiée 7j/7',
            'Découvrir le service',
            'Nos services',
        ]);
        $response->assertSee(route('properties.index', [
            'isShortTerm' => 1,
            'propertyType' => 'commercial',
        ]));
        $response->assertSee(route('properties.index', [
            'isShortTerm' => 1,
            'propertyType' => 'residential',
        ]));
        $response->assertDontSee('ephemere.html');
        $response->assertDontSee('ephemere-residence.html');
    }
}
