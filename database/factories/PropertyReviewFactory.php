<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\PropertyReview;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @template TModel of \App\Models\PropertyReview
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<TModel>
 */
class PropertyReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TModel>
     */
    protected $model = PropertyReview::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'     => User::factory(),
            'property_id' => Property::factory(),
            'rating'      => $this->faker->numberBetween(1, 5),
            'review'      => $this->faker->optional(0.7)->paragraph(),
        ];
    }
}
