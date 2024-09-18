<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;
use App\Models\CompanySetting;

class CapitalTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Get the initial capital
        $companySetting = CompanySetting::first();
        $currentCapital = $companySetting->capital;

        while ($startOfMonth->lte($endOfMonth)) {
            $type = $faker->randomElement(['deposit', 'withdrawal']);
            $amount = $faker->randomFloat(2, 1000, 100000);

            // Insert the transaction
            DB::table('capital_transactions')->insert([
                'type' => $type,
                'amount' => $amount,
                'description' => $faker->sentence,
                'created_at' => $startOfMonth->copy(),
                'updated_at' => $startOfMonth->copy(),
            ]);

            // Update the capital based on the transaction type
            if ($type === 'deposit') {
                $currentCapital += $amount;
            } else {
                $currentCapital -= $amount;
            }

            // Update the company setting with the new capital
            $companySetting->capital = $currentCapital;
            $companySetting->save();

            // Increment the date by a random number of hours to simulate multiple transactions per day
            $startOfMonth->addHours($faker->numberBetween(1, 24));
        }
    }
}