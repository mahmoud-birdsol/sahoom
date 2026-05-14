<?php

namespace Database\Factories;

use App\Models\Landlord;
use App\Models\Property;
use App\Models\States\PropertyStatus;
use App\Models\States\PropertyType;
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
            'title' => $this->faker->sentence(3),
            'slug' => $this->faker->unique()->slug(3),
            'description' => $this->faker->paragraph(),
            'status' => PropertyStatus::DRAFT,
            'address_line_1' => $this->faker->streetAddress(),
            'address_line_2' => $this->faker->optional(0.3)->secondaryAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->optional()->state(),
            'postal_code' => $this->faker->postcode(),
            'country' => $this->faker->country(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'size_sqm' => $this->faker->numberBetween(50, 500),
            'traffic_score'    => $this->faker->numberBetween(1, 10),
            'is_featured'      => false,
            'is_active'        => true,
            'rejection_reason' => null,
            'pricing_type'     => 'monthly',
            'monthly_rent'     => $this->faker->numberBetween(1000, 10000),
            'currency'         => 'USD',
            'security_deposit' => null,
            'application_fee'  => null,
            'min_lease_months' => null,
            'max_lease_months' => null,
            'nearby_places'    => null,
            'property_type'    => PropertyType::RESIDENTIAL,
        ];
    }

    public function approved(): static
    {
        return $this->state(['status' => PropertyStatus::APPROVED]);
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }

    public function residential(): static
    {
        return $this->state(['property_type' => PropertyType::RESIDENTIAL]);
    }

    public function commercial(): static
    {
        return $this->state(['property_type' => PropertyType::COMMERCIAL]);
    }
}
