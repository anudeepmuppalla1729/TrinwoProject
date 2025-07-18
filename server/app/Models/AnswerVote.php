<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnswerVote extends Model
{
    protected $fillable = [
        'user_id', 'answer_id', 'vote_type'
    ];
    
    /**
     * Get the user that owns the vote.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Get the answer that the vote belongs to.
     */
    public function answer()
    {
        return $this->belongsTo(Answer::class, 'answer_id');
    }
}