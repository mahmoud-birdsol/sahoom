<?php

namespace Database\Factories;

use App\Models\ViewingRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @template TModel of \App\Models\ViewingRequest
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<TModel>
 */
class ViewingRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TModel>
     */
    protected $model = ViewingRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'property_id' => \App\Models\Property::factory(),
            'renter_name' => fake()->name(),
            'renter_email' => fake()->safeEmail(),
            'renter_phone' => fake()->optional(0.8)->phoneNumber(),
            'message' => fake()->optional(0.7)->paragraph(),
            'preferred_date' => fake()->optional(0.6)->dateTimeBetween('now', '+30 days'),
            'status' => fake()->randomElement(\App\Models\States\ViewingRequestStatus::toArray()),
            'handled_by_user_id' => fake()->optional(0.4)->randomDigit(), // Will need real user IDs
        ];
    }
}
