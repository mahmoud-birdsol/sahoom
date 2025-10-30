<?php

namespace Database\Factories;

use App\Models\Landlord;
use App\Models\Property;
use App\Models\States\PropertyStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @template TModel of \App\Models\Property
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<TModel>
 */
class PropertyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TModel>
     */
    protected $model = Property::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'landlord_id' => Landlord::factory(),
            'title' => fake()->sentence(3),
            'slug' => fake()->unique()->slug(3),
            'description' => fake()->paragraph(),
            'status' => PropertyStatus::DRAFT,
            'address_line_1' => fake()->streetAddress(),
            'address_line_2' => fake()->optional(0.3)->secondaryAddress(),
            'city' => fake()->city(),
            'state' => fake()->optional()->state(),
            'postal_code' => fake()->postcode(),
            'country' => fake()->country(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'size_sqm' => fake()->numberBetween(50, 500),
            'traffic_score' => fake()->numberBetween(1, 10),
            'is_featured' => false,
            'rejection_reason' => null, // Only set when status is REJECTED
        ];
    }
}
