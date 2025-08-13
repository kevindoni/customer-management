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
        Schema::create('customer_pakets', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('paket_id')->references('id')->on('pakets')->onDelete('cascade')->nullable();
            $table->integer('price')->default(0);
            $table->integer('discount')->default(0);
            $table->foreignId('internet_service_id')->references('id')->on('internet_services')->onDelete('cascade');
            $table->enum('renewal_period', ['monthly', 'bimonthly', 'quarterly', 'semi-annually', 'annually']);
            $table->boolean('auto_renew')->default(true);
            $table->enum('status', ['active', 'suspended', 'cancelled', 'expired','pending'])->default('pending');
            $table->date('activation_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('expired_date')->nullable();
            $table->date('paylater_date')->nullable();
            $table->timestamp('last_billed_at')->nullable();
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
        Schema::dropIfExists('customer_pakets');
    }
};
