<?php

namespace Database\Seeders;

use App\Models\CompanySetting;
use Illuminate\Database\Seeder;

class CompanySettingsSeeder extends Seeder
{
    public function run()
    {
        CompanySetting::create([
            'name' => 'Oufoq Albinae',
            'email' => 'contact@oufoqalbinae.com',
            'phone1' => '06 98 46 33 60',
            'phone2' => '06 61 78 99 70',
            'address' => 'N°97 Rue Assila Laayayda',
            'city' => 'Salé',
            'if' => '3341831',
            'ice' => '000095738000027',
            'rc' => '16137',
            'cnss' => '8712863',
            'patente' => '28565292',
            'capital' => '100000.00',
            'footer_text' => 'Merci de Votre Confiance'
        ]);
    }
} 