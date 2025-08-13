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
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('value')->unique();
            $table->string('name');
            $table->string('merchant_code')->nullable();
            $table->string('production_api_key')->nullable();
            $table->string('production_secret_key')->nullable();
            $table->string('development_merchant_code')->nullable();
            $table->string('development_api_key')->nullable();
            $table->string('development_secret_key')->nullable();
            $table->boolean('is_active')->default(false);
            $table->enum('mode', ['development', 'production'])->default('development');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
