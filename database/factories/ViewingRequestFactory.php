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
            'renter_name' => $this->faker->name(),
            'renter_email' => $this->faker->safeEmail(),
            'renter_phone' => $this->faker->optional(0.8)->phoneNumber(),
            'message' => $this->faker->optional(0.7)->paragraph(),
            'preferred_date' => $this->faker->optional(0.6)->dateTimeBetween('now', '+30 days')?->format('Y-m-d'),
            'start_date' => $this->faker->optional(0.7)->dateTimeBetween('now', '+3 months')?->format('Y-m-d'),
            'end_date' => $this->faker->optional(0.5)->dateTimeBetween('+3 months', '+15 months')?->format('Y-m-d'),
            'status' => $this->faker->randomElement(\App\Models\States\ViewingRequestStatus::toArray()),
            'handled_by_user_id' => null, // Set manually in seeder if needed
        ];
    }
}
