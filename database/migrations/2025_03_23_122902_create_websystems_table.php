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
        Schema::create('websystems', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable(true);
            $table->string('version')->nullable(true);
            $table->string('comment_payment')->default('payment')->nullable();
            $table->string('comment_unpayment')->default('unpayment')->nullable();
            $table->enum('subscription_mode', ['prabayar', 'pascabayar'])->default('prabayar');
            $table->enum('isolir_driver', ['mikrotik', 'cronjob'])->default('cronjob');
            $table->integer('max_process')->default('25');
            $table->boolean('enable_tax')->default(false);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->string('email')->nullable();
            $table->string('url')->nullable();
            $table->string('address')->nullable();
            $table->string('subdistrict')->nullable();
            $table->string('district')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country_code')->nullable()->default('IDN');
            $table->string('phone')->nullable();
            $table->string('customer_code')->nullable();
            $table->integer('long_customer_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('websystems');
    }
};
