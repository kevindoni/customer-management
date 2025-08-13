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
        Schema::create('paket_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            //general
            $table->string('profile_name')->nullable();
            $table->string('local_address')->nullable();
            $table->string('remote_address')->nullable();
            $table->string('dns_server')->nullable();
            //protocol
            $table->enum('use_ipv6', ['no', 'yes', 'required', 'default'])->default('yes');
            $table->enum('use_mpls', ['no', 'yes', 'required', 'default'])->default('default');
            $table->enum('use_encryption', ['no', 'yes', 'required', 'default'])->default('default');
            $table->enum('use_compression', ['no', 'yes', 'default'])->default('default');
            $table->string('rate_limit')->nullable();
            $table->string('rasio_cir')->nullable();
            $table->string('session_timeout')->nullable();
            $table->string('idle_timeout')->nullable();
            $table->enum('only_one', ['no', 'yes', 'default'])->default('default');
            //queue
            $table->string('insert_queue_before')->nullable();
            $table->string('parent_queue')->nullable();
            $table->string('queue_type')->nullable();
            //queue
            $table->string('script_on_up')->nullable();
            $table->string('script_on_down')->nullable();
            $table->boolean('disabled')->default(false);
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_profiles');
    }
};
