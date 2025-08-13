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
        Schema::create('whatsapp_boot_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('whatsapp_boot_message_id')->nullable();
            $table->integer('command_number')->nullable();
            $table->string('name')->nullable();
            $table->text('message')->nullable();
            $table->string('action_message')->nullable();
            $table->boolean('end_message')->default(false);
            $table->boolean('hidden_message')->default(false);
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
        Schema::dropIfExists('whatsapp_boot_messages');
    }
};
