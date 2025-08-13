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
        Schema::create('mikrotik_monitorings', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->foreignId('mikrotik_id')->references('id')->on('mikrotiks')->onDelete('cascade');
            $table->string('interface')->nullable();
            $table->string('interface_type')->nullable();
            $table->string('min_upload')->nullable();
            $table->string('min_download')->nullable();
            $table->string('max_upload')->nullable();
            $table->string('max_download')->nullable();
            $table->boolean('disabled')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mikrotik_monitorings');
    }
};
