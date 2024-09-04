<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $units = ['pieces', 'kg', 'liters', 'm2', 'm3'];

        for ($i = 0; $i < 50; $i++) {
            Product::create([
                'name' => $faker->word,
                'unit' => $faker->randomElement($units),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}