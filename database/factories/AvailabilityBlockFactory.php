<?php

namespace Database\Factories;

use App\Models\AvailabilityBlock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @template TModel of \App\Models\AvailabilityBlock
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<TModel>
 */
class AvailabilityBlockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TModel>
     */
    protected $model = AvailabilityBlock::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('now', '+6 months');
        $endDate = fake()->dateTimeBetween($startDate, $startDate->format('Y-m-d') . ' +30 days');
        
        return [
            'property_id' => \App\Models\Property::factory(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => fake()->randomElement(\App\Models\States\AvailabilityBlockStatus::toArray()),
            'source' => fake()->randomElement(\App\Models\States\AvailabilityBlockSource::toArray()),
            'contract_reference' => fake()->optional(0.6)->bothify('CONTRACT-####-??'),
            'notes' => fake()->optional(0.5)->sentence(),
        ];
    }
}
