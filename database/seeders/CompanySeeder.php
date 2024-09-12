<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        DB::table('company_settings')->insert([
            'name' => $faker->company,
            'email' => $faker->companyEmail,
            'phone1' => $faker->phoneNumber,
            'phone2' => $faker->phoneNumber,
            'fax' => $faker->phoneNumber,
            'address' => $faker->address,
            'city' => $faker->city,
            'state' => $faker->state,
            'zip' => $faker->postcode,
            'country' => $faker->country,
            'if' => $faker->regexify('[A-Z0-9]{10}'),
            'ice' => $faker->regexify('[A-Z0-9]{15}'),
            'rc' => $faker->regexify('[A-Z0-9]{8}'),
            'cnss' => $faker->regexify('[A-Z0-9]{9}'),
            'patente' => $faker->regexify('[A-Z0-9]{7}'),
            'capital' => $faker->numberBetween(10000, 1000000),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}