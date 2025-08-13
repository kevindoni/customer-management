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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->enum('payment_gateway_channel', ['tripay', 'midtrans']);
            $table->string('reference')->nullable();
            $table->string('merchant_ref')->nullable();
            $table->string('payment_selection_type')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_name')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->integer('amount')->nullable();
            $table->integer('fee_merchant')->nullable();
            $table->string('fee_customer')->nullable();
            $table->string('total_fee')->nullable();
            $table->string('amount_received')->nullable();
            $table->string('pay_code')->nullable();
            $table->string('order_items')->nullable();
            $table->enum('status', ['pending', 'expired', 'paid', 'failed', 'refund']);
            $table->timestamp('expired_time')->nullable();
            $table->text('instructions')->nullable();
            $table->string('pdf_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
