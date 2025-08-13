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
        Schema::create('pakets', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->foreignId('paket_profile_id')->references('id')->on('paket_profiles')->nullable()->onDelete('cascade');
            $table->foreignId('mikrotik_id')->references('id')->on('mikrotiks')->onDelete('cascade');
            $table->string('mikrotik_ppp_profile_id')->nullable();
            $table->string('name');
            $table->integer('price');
            $table->boolean('tax_disabled')->default(true);
            $table->boolean('disabled')->default(false);
            $table->integer('trial_days')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pakets');
    }
};
