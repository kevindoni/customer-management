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
        Schema::create('wan_monitorings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mikrotik_id')->references('id')->on('mikrotiks')->onDelete('cascade');
            $table->string('interface_name')->nullable();
            $table->string('tx_rate')->nullable();
            $table->string('rx_rate')->nullable();
            $table->string('ping_ms')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wan_monitorings');
    }
};
