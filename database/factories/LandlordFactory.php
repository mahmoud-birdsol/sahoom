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
            'company_name' => $this->faker->word(),
            'contact_name' => $this->faker->name(),
            'contact_phone' => $this->faker->phoneNumber(),
            'contact_email' => $this->faker->email(),
            'status' => $this->faker->randomElement(LandlordStatus::toArray()),
            'kyc_status' => $this->faker->randomElement(LandlordKycStatus::toArray()),
            'verification_notes' => $this->faker->text(),
        ];
    }
}
