<?php

namespace Database\Seeders;

use App\Models\Acs;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class CreateAcsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Acs::create([
            'slug' => 'acs1',
            'name' => 'Acs',
            'disabled' => false,
        ]);
    }
}
