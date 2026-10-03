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

    public function test_homepage_shows_three_rental_options_without_commercial_option(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSee(__('Offices, showrooms, retail spaces and commercial premises for rent'));
        $response->assertSeeInOrder([
            '>01</div>',
            __('Rental'),
            '>02</div>',
            __('Short-term Spaces'),
            '>03</div>',
            __('Short-term Rental'),
        ], false);
    }
}
