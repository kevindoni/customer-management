<?php

namespace Database\Seeders;

use App\Models\Pakets\InternetService;
use Illuminate\Database\Seeder;

class InternetServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        InternetService::create([
            'name' => 'PPP',
            'value' => 'ppp',
        ]);
        InternetService::create([
            'name' => 'IP Static',
            'value' => 'ip_static',
        ]);
    }
}
