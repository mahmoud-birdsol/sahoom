<?php

namespace Database\Factories;

use App\Models\Landlord;
use App\Models\States\LandlordKycStatus;
use App\Models\States\LandlordStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @template TModel of \App\Models\Landlord
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<TModel>
 */
class LandlordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TModel>
     */
    protected $model = Landlord::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'company_name' => fake()->word(),
            'contact_name' => fake()->name(),
            'contact_phone' => fake()->phoneNumber(),
            'contact_email' => fake()->email(),
            'status' => fake()->randomElement(LandlordStatus::toArray()),
            'kyc_status' => fake()->randomElement(LandlordKycStatus::toArray()),
            'verification_notes' => fake()->text(),
        ];
    }
}
