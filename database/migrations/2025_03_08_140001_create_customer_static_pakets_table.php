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
        Schema::create('customer_static_pakets', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->foreignId('customer_paket_id')->references('id')->on('customer_pakets')->onDelete('cascade');
            $table->string('simpleque_id')->nullable();
            $table->string('simpleque_name')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('mac_address')->nullable();
            $table->string('interface')->nullable();
            $table->string('arp_id')->nullable();
            $table->string('address_list_id')->nullable();
            $table->boolean('online')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_static_pakets');
    }
};
