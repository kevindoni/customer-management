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
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('name', 'first_name');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->after('id')->unique();
            $table->string('last_name')->after('first_name')->nullable();
            $table->string('profile_photo_path', 2048)->after('remember_token')->nullable();
            $table->boolean('disabled')->after('profile_photo_path')->default(false);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
            $table->renameColumn('first_name', 'name');
            $table->dropColumn('last_name');
            $table->dropColumn('disabled');
            $table->dropColumn('profile_photo_path');
            $table->dropColumn('deleted_at');
        });
    }
};
