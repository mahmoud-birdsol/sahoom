<?php

namespace Database\Factories;

use App\Models\Contract;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @template TModel of \App\Models\Contract
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<TModel>
 */
class ContractFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TModel>
     */
    protected $model = Contract::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $property = \App\Models\Property::factory()->create();
        $startDate = fake()->dateTimeBetween('now', '+3 months');
        $endDate = fake()->dateTimeBetween($startDate, $startDate->format('Y-m-d') . ' +1 year');
        
        return [
            'property_id' => $property->id,
            'landlord_id' => $property->landlord_id,
            'renter_name' => fake()->name(),
            'renter_company' => fake()->optional(0.4)->company(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_value' => fake()->randomFloat(2, 5000, 50000),
            'currency' => fake()->randomElement(['USD', 'EUR', 'GBP']),
            'payment_status' => fake()->randomElement(\App\Models\States\PaymentStatus::toArray()),
            'contract_status' => fake()->randomElement(\App\Models\States\ContractStatus::toArray()),
            'notes_internal' => fake()->optional(0.5)->paragraph(),
        ];
    }
}
