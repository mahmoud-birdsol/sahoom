<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @template TModel of \App\Models\PropertyImage
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<TModel>
 */
class PropertyImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TModel>
     */
    protected $model = PropertyImage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'property_id' => Property::factory(),
            'path'        => 'properties/' . $this->faker->uuid() . '.jpg',
            'order'       => 0,
        ];
    }
}
