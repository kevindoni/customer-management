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
        Schema::create('user_teller_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->reference('id')->on('users')->onDelete('cascade');
            $table->foreignId('payment_id')->reference('id')->on('payments')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_teller_payments');
    }
};
