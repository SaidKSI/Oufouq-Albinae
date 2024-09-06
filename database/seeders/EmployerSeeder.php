<?php

namespace Database\Seeders;

use App\Models\Employer;
use App\Models\Profession;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class EmployerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $professions = Profession::all();

        for ($i = 0; $i < 10; $i++) {
            Employer::create([
                'full_name' => $faker->name,
                'phone' => $faker->phoneNumber,
                'city' => $faker->city,
                'cine' => $faker->numerify('CINE####'),
                'address' => $faker->address,
                'profession_id' => $professions->random()->id,
                'cnss' => $faker->boolean(),
                'wage_per_hr' => $faker->randomFloat(2, 10, 50),
            ]);
        }
    }
}