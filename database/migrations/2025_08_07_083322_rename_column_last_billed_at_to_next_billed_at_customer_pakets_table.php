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
        Schema::table('customer_pakets', function (Blueprint $table) {
            $table->renameColumn('last_billed_at', 'next_billed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_pakets', function (Blueprint $table) {
           $table->renameColumn('next_billed_at', 'last_billed_at');
        });
    }
};
