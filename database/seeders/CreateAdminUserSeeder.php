<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\Admins\UserAdmin;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'first_name' => 'Administrator',
            'username' => 'admin-'.str(Str::random(5))->slug(),
            'email' => 'administrator@admin.com',
            'password' => bcrypt('123456'),
            'disabled' => false,
        ]);
        $address = new UserAddress();
        $admin = new UserAdmin();
        $user->user_address()->save($address);
        $user->user_admin()->save($admin);
        $user->assignRole('admin');

    }
}
