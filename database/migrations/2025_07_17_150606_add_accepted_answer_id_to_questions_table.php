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
        Schema::table('questions', function (Blueprint $table) {
            if (!Schema::hasColumn('questions', 'accepted_answer_id')) {
                $table->unsignedBigInteger('accepted_answer_id')->nullable()->after('is_closed');
            }
            $table->foreign('accepted_answer_id')->references('answer_id')->on('answers')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['accepted_answer_id']);
            $table->dropColumn('accepted_answer_id');
        });
    }
};
