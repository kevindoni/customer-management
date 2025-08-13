<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\WhatsappGateway\WhatsappGatewayGeneral;

class WhatsappGatewayGeneralTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WhatsappGatewayGeneral::create([
            'name' => 'Griyanet WA Gateway',
            'disabled' => true,
            'remaining_day' => 0,
            'country_code' => 62,

        ]);
    }
}
