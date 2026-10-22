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
}