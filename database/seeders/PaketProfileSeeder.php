<?php

namespace Database\Seeders;

use App\Models\Pakets\PaketProfile;
use Illuminate\Database\Seeder;

class PaketProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        PaketProfile::create([
            'slug' => 'default',
            'profile_name' => 'default',
        ]);
    }
}
