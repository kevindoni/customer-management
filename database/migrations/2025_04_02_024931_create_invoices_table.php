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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_customer_id')->references('id')->on('user_customers')->onDelete('cascade');
            $table->foreignId('customer_paket_id')->references('id')->on('customer_pakets')->onDelete('cascade');
            $table->string('invoice_number')->unique()->nullable();
            $table->date('issue_date');
            $table->date('periode');
            $table->date('start_periode')->nullable();
            $table->date('end_periode')->nullable();
            $table->date('due_date')->nullable();
            $table->integer('amount');
            $table->integer('tax')->default(0);
            //$table->integer('received_amount')->default(0);
            $table->integer('discount')->default(0);
            $table->enum('status', ['pending', 'process', 'paid', 'overdue', 'partially_paid','refunded']);
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('status_history')->nullable();
            $table->boolean('upcoming_reminder_sent')->nullable();
            $table->integer('reminder_count')->nullable();
            $table->timestamp('last_reminder_date')->nullable();
            $table->string('invoice_path', 2048)->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
