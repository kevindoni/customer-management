<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::beginTransaction();
        Artisan::call('db:seed --class=CreateRoleSeeder');
        //Artisan::call('db:seed --class=CreateAdminUserSeeder');
        Artisan::call('db:seed --class=InternetServiceSeeder');
        Artisan::call('db:seed --class=CreatePppTypeSeeder');
        Artisan::call('db:seed --class=PaketProfileSeeder');
        Artisan::call('db:seed --class=WhatsappGatewayGeneralTableSeeder');
        Artisan::call('db:seed --class=WebsystemTableSeeder');
        Artisan::call('db:seed --class=WhatsappNotificationMessageTableSeeder');
        Artisan::call('db:seed --class=WhatsappBootMessageTableSeeder');
        Artisan::call('db:seed --class=PaymentGatewaySeeder');
        Artisan::call('db:seed --class=CreateAcsSeeder');
        DB::commit();
    }
}
