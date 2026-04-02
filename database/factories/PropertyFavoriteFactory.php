<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\PropertyFavorite;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @template TModel of \App\Models\PropertyFavorite
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<TModel>
 */
class PropertyFavoriteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TModel>
     */
    protected $model = PropertyFavorite::class;

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
        ];
    }
}
