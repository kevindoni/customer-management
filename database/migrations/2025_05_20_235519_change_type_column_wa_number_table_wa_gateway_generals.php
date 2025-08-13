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
        Schema::table('whatsapp_gateway_generals', function (Blueprint $table) {
            $table->string('whatsapp_number_boot')->nullable()->change();
            $table->string('whatsapp_number_notification')->nullable()->change();
            $table->dropColumn('schedule_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whatsapp_gateway_generals', function (Blueprint $table) {
            $table->foreignId('whatsapp_number_boot')->nullable()->change();
            $table->foreignId('whatsapp_number_notification')->nullable()->change();
            $table->string('schedule_time')->default('10:00');
        });
    }
};
