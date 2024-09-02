<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 5) as $index) {
            DB::table('clients')->insert([
                'name' => $faker->company,
                'type' => $faker->randomElement(['company', 'person']),
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'ice' => $faker->optional()->numerify('##########'),
                'city' => $faker->city,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}