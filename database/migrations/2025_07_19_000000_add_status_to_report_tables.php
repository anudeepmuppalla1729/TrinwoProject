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
        // Add status to post_reports table
        Schema::table('post_reports', function (Blueprint $table) {
            $table->enum('status', ['pending', 'review', 'resolved', 'dismissed'])->default('pending')->after('reason');
        });

        // Add status to question_reports table
        Schema::table('question_reports', function (Blueprint $table) {
            $table->enum('status', ['pending', 'review', 'resolved', 'dismissed'])->default('pending')->after('reason');
        });

        // Add status to answer_reports table
        Schema::table('answer_reports', function (Blueprint $table) {
            $table->enum('status', ['pending', 'review', 'resolved', 'dismissed'])->default('pending')->after('reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_reports', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('question_reports', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('answer_reports', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}; 