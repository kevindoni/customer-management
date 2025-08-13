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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->foreignId('user_customer_id')->references('id')->on('user_customers')->onDelete('cascade');
            $table->timestamp('payment_date');
            $table->integer('amount');
            $table->enum('payment_method', ['tripay', 'midtrans', 'bank transfer', 'cash', 'paylater']);
            $table->date('paylater_date')->nullable();
            $table->string('transaction_id')->unique();
            $table->string('bank')->nullable();
            $table->string('teller');
            $table->string('reconciliation_status')->nullable();
            $table->string('reconciliation_notes')->nullable();
            $table->enum('refund_status', ['full', 'partial'])->nullable();
            $table->integer('refunded_amount')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
