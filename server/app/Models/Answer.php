<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AnswerReport;

class Answer extends Model
{
    protected $primaryKey = 'answer_id';

    protected $fillable = [
        'question_id', 'user_id', 'content'
    ];

    protected static function boot()
    {
        parent::boot();

        // Cascade delete related data when answer is deleted
        static::deleting(function ($answer) {
            // Delete votes
            $answer->votes()->delete();
            
            // Delete reports
            AnswerReport::where('answer_id', $answer->answer_id)->delete();
        });
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function question() {
        return $this->belongsTo(Question::class, 'question_id');
    }
    
    /**
     * Get the votes for this answer
     */
    public function votes()
    {
        return $this->hasMany(AnswerVote::class, 'answer_id');
    }
    
    /**
     * Check if a user has voted on this answer
     */
    public function hasUserVoted($userId)
    {
        return $this->votes()->where('user_id', $userId)->exists();
    }
    
    /**
     * Get a user's vote on this answer
     */
    public function getUserVote($userId)
    {
        return $this->votes()->where('user_id', $userId)->first();
    }
    
    /**
     * Get the count of upvotes for this answer
     */
    public function getUpvotesCount()
    {
        return $this->votes()->where('vote_type', 'upvote')->count();
    }
    
    /**
     * Get the count of downvotes for this answer
     */
    public function getDownvotesCount()
    {
        return $this->votes()->where('vote_type', 'downvote')->count();
    }
    
    public function isAccepted()
    {
        return $this->question && $this->question->accepted_answer_id === $this->answer_id;
    }
}
