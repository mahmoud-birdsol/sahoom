<?php

namespace Database\Seeders;

use App\Models\Landlord;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LandlordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Landlord::factory()->count(10)->create();
    }
}
