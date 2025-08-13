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
        Schema::create('mikrotiks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('host');
            $table->string('username');
            $table->string('password');
            $table->boolean('use_ssl')->default(false);
            $table->bigInteger('port')->default(8728);
            $table->bigInteger('web_port')->default(80);
            $table->string('merk_router')->nullable();
            $table->string('type_router')->nullable();
            $table->string('version')->nullable();
            $table->string('description')->nullable();
            $table->boolean('disabled')->default(false);
            $table->ipAddress('admin_ip_address')->nullable();
            $table->ipAddress('updated_ip_address')->nullable();
            $table->ipAddress('deleted_ip_address')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mikrotiks');
    }
};
