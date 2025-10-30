<?php

namespace Database\Factories;

use App\Models\Amenity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @template TModel of \App\Models\Amenity
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<TModel>
 */
class AmenityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TModel>
     */
    protected $model = Amenity::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Icon format: 'icon-set icon-name' (e.g., 'heroicons-solid wifi')
        $amenities = [
            'WiFi' => 'heroicons-solid wifi',
            'Parking' => 'font-awesome-solid car',
            'Air Conditioning' => 'heroicons-solid wind',
            'Heating' => 'heroicons-solid fire',
            'Security System' => 'font-awesome-solid shield-alt',
            'CCTV' => 'heroicons-solid video-camera',
            'Elevator' => 'heroicons-solid arrow-up',
            'Generator' => 'heroicons-solid bolt',
            'Water Supply' => 'heroicons-solid beaker',
            'Backup Power' => 'font-awesome-solid battery-full',
        ];

        $name = fake()->randomElement(array_keys($amenities));
        
        return [
            'name' => $name,
            'description' => fake()->sentence(),
            'icon' => $amenities[$name],
            'is_active' => fake()->boolean(90),
        ];
    }
}
