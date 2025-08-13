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
        Schema::create('mikrotik_client_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mikrotik_id')->references('id')->on('mikrotiks')->onDelete('cascade');
            $table->foreignId('customer_paket_id')->references('id')->on('customer_pakets')->onDelete('cascade');
            $table->enum('type', ['ppp', 'ip_static', 'hotspot']);
            $table->string('history')->nullable();
            $table->enum('status', ['up', 'down', 'error']);
            $table->timestamp('recorded_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_mikrotik_histories');
    }
};
