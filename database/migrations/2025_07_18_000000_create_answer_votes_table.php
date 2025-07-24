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
        Schema::create('answer_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('answer_id')->constrained('answers', 'answer_id')->onDelete('cascade');
            $table->enum('vote_type', ['upvote', 'downvote']);
            $table->timestamps();
            
            // Ensure a user can only have one vote per answer
            $table->unique(['user_id', 'answer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answer_votes');
    }
};