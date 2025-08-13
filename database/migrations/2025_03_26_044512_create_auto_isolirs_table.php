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
        Schema::create('auto_isolirs', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->foreignId('mikrotik_id')->references('id')->on('mikrotiks')->onDelete('cascade');
            $table->string('profile_id')->nullable();
            $table->string('script_id')->nullable();
            $table->string('schedule_id')->nullable();
            $table->string('address_list_isolir')->nullable();
            $table->boolean('activation_date')->default(false);
            $table->string('due_date')->nullable();
            $table->string('nat_id')->nullable();
            $table->string('nat_dst_address')->nullable();
            $table->string('nat_src_address_list')->nullable();
            $table->string('nat_dst_address_list')->nullable();
            $table->string('proxy_access_id')->nullable();
            $table->string('proxy_access_src_address')->nullable();
            $table->string('proxy_access_action_data')->nullable();
            $table->enum('proxy_access_action', ['redirect', 'deny', 'allow'])->default('deny');
            $table->string('firewall_filter_id')->default('0');
            $table->boolean('disabled')->default(true);
            $table->enum('run_isolir_with', ['mikrotik', 'cm'])->default('mikrotik');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auto_isolirs');
    }
};
