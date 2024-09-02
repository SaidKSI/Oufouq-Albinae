<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 5; $i++) {
            Supplier::create([
                'full_name' => $faker->name,
                'phone' => $faker->phoneNumber,
                'email' => $faker->unique()->safeEmail,
                'city' => $faker->city,
                'address' => $faker->address,
                'rating' => $faker->randomFloat(1, 0, 5),
                'description' => $faker->paragraph,
                'ice' => $faker->text(10),
                'facebook_handle' => $faker->userName,
                'instagram_handle' => $faker->userName,
                'linkedin_handle' => $faker->userName,
                'twitter_handle' => $faker->userName,
            ]);
        }
    }
}