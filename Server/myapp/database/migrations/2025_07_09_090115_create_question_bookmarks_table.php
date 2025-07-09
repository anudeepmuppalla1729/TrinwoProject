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
        Schema::create('question_bookmarks', function (Blueprint $table) {
            $table->id('bookmark_id');
            $table->foreignId('user_id')->constrained('users', 'user_id');
            $table->foreignId('question_id')->constrained('questions', 'question_id');
            $table->timestamps();
            $table->unique(['user_id', 'question_id']);
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_bookmarks');
    }
};
