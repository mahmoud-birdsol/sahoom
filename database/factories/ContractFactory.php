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
        
        // Generate pricing based on pricing type
        $pricingType = fake()->randomElement(\App\Models\States\PricingType::toArray());
        $monthlyRent = fake()->numberBetween(2000, 15000);
        
        // Calculate other pricing options
        $weeklyRent = round($monthlyRent / 4.33, 2); // Average weeks per month
        $yearlyRent = $monthlyRent * 12;
        $dailyRent = round($monthlyRent / 30, 2);
        
        // Calculate total value based on contract duration
        $startDateTime = new \DateTime($startDate->format('Y-m-d'));
        $endDateTime = new \DateTime($endDate->format('Y-m-d'));
        $durationMonths = $startDateTime->diff($endDateTime)->m + ($startDateTime->diff($endDateTime)->y * 12);
        $totalValue = $monthlyRent * max($durationMonths, 1);
        
        return [
            'property_id' => $property->id,
            'landlord_id' => $property->landlord_id,
            'renter_name' => fake()->name(),
            'renter_company' => fake()->optional(0.4)->company(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'pricing_type' => $pricingType,
            'monthly_rent' => $monthlyRent,
            'weekly_rent' => $weeklyRent,
            'yearly_rent' => $yearlyRent,
            'daily_rent' => $dailyRent,
            'security_deposit' => fake()->optional(0.7)->numberBetween($monthlyRent, $monthlyRent * 2),
            'service_fee' => fake()->optional(0.5)->numberBetween(100, 500),
            'cleaning_fee' => fake()->optional(0.4)->numberBetween(50, 300),
            'total_value' => $totalValue,
            'currency' => fake()->randomElement(['SAR', 'USD', 'EUR', 'AED']),
            'payment_status' => fake()->randomElement(\App\Models\States\PaymentStatus::toArray()),
            'contract_status' => fake()->randomElement(\App\Models\States\ContractStatus::toArray()),
            'notes_internal' => fake()->optional(0.5)->paragraph(),
        ];
    }
}
