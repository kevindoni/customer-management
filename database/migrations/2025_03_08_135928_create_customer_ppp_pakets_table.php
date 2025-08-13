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
        Schema::create('customer_ppp_pakets', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->foreignId('customer_paket_id')->references('id')->on('customer_pakets')->onDelete('cascade');
            $table->string('secret_id')->nullable();
            $table->string('username')->nullable();
            $table->string('password_ppp')->nullable();
            $table->foreignId('ppp_type_id')->references('id')->on('ppp_types')->onDelete('cascade')->nullable();
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
        Schema::dropIfExists('customer_ppp_pakets');
    }
};
