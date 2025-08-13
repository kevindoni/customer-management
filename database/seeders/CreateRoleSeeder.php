<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserAddress;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $role = Role::create(['name' => 'admin']);
        $permissions = Permission::pluck('id', 'id')->all();
        $role->syncPermissions($permissions);
        $role = Role::create(['name' => 'teller']);
        $role = Role::create(['name' => 'technitian']);
        $role = Role::create(['name' => 'customer']);
    }
}
