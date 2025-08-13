<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Websystem;

class WebsystemTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Websystem::create([
            'title' => 'Customer Management',
            'version' => '2.0.0',
            'country_code' => 'IDN',
            'max_process' => 50,
        ]);
    }
}
