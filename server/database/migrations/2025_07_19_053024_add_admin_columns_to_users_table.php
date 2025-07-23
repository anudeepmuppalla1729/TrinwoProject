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
            // Add new columns for admin functionality
            // Removed first_name and last_name columns permanently
            $table->string('username')->unique()->nullable()->after('name');
            $table->enum('role', ['user', 'admin'])->default('user')->after('username');
            $table->enum('status', ['active', 'inactive', 'banned'])->default('active')->after('role');
            $table->string('avatar')->nullable()->after('status');
            $table->timestamp('last_login_at')->nullable()->after('avatar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn(['first_name', 'last_name', 'username', 'role', 'status', 'avatar', 'last_login_at']);
            // Do not rename avatar_old back to profile_pic
        });
    }
};
