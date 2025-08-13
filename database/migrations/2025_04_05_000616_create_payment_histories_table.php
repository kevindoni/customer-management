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
        Schema::create('payment_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('payment_id')->nullable();
            $table->integer('invoice_id')->nullable();
            $table->integer('user_customer_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('payment_method');
            $table->date('paylater_date')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('status');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_histories');
    }
};
