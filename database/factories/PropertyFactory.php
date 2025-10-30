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
            'slug' => fake()->slug(3),
            'description' => fake()->text(100),
            'status' => PropertyStatus::DRAFT,
            'address_line_1' => fake()->address(),
            'address_line_2' => fake()->address(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'postal_code' => fake()->postcode(),
            'country' => fake()->country(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'size_sqm' => fake()->numberBetween(100, 1000),
            'traffic_score' => fake()->numberBetween(1, 10),
            'is_featured' => fake()->boolean(10),
            'rejection_reason' => fake()->text(100),
        ];
    }
}
