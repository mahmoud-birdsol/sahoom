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
        $startDate = $this->faker->dateTimeBetween('now', '+6 months');
        $endDate = $this->faker->dateTimeBetween($startDate, $startDate->format('Y-m-d') . ' +30 days');
        
        return [
            'property_id' => \App\Models\Property::factory(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $this->faker->randomElement(\App\Models\States\AvailabilityBlockStatus::toArray()),
            'source' => $this->faker->randomElement(\App\Models\States\AvailabilityBlockSource::toArray()),
            'contract_reference' => $this->faker->optional(0.6)->bothify('CONTRACT-####-??'),
            'notes' => $this->faker->optional(0.5)->sentence(),
        ];
    }
}
