<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            DB::table('projects')->insert([
                'client_id' => $faker->numberBetween(1, 5),
                'name' => $faker->sentence(3),
                'ref' => $faker->unique()->numerify('PRJ-#####'),
                'description' => $faker->paragraph,
                'city' => $faker->city,
                'address' => $faker->address,
                'start_date' => $faker->date,
                'end_date' => $faker->optional()->date,
                'status' => $faker->randomElement(['pending', 'in_progress', 'completed']),
                'progress_percentage' => $faker->numberBetween(0, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}