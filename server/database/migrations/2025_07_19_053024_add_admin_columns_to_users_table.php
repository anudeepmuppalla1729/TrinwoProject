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
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('username')->unique()->nullable()->after('last_name');
            $table->enum('role', ['user', 'moderator', 'admin'])->default('user')->after('username');
            $table->enum('status', ['active', 'inactive', 'banned'])->default('active')->after('role');
            $table->string('avatar')->nullable()->after('status');
            $table->timestamp('last_login_at')->nullable()->after('avatar');
            
            // Rename profile_pic to avatar for consistency
            $table->renameColumn('profile_pic', 'avatar_old');
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
            
            // Rename back
            $table->renameColumn('avatar_old', 'profile_pic');
        });
    }
};
