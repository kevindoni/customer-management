<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('whatsapp_gateway_generals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('remaining_day')->default(1);
            $table->integer('schedule_time')->default(10);
            $table->foreignId('whatsapp_number_boot')->nullable();
            $table->foreignId('whatsapp_number_notification')->nullable();
            $table->integer('country_code')->default(62)->nullable();
            //  $table->string('token')->nullable();
            $table->string('url')->nullable();
            $table->string('url_callback')->nullable();
            //  $table->string('gateway_username')->nullable();
            //  $table->string('api_key')->nullable();
            $table->string('cert_file')->nullable();
            $table->boolean('send_wa_admin')->default(true);
            $table->boolean('disabled')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_gateway_generals');
    }
};
