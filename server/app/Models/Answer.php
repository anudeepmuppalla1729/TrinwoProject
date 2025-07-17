<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $primaryKey = 'answer_id';

    protected $fillable = [
        'question_id', 'user_id', 'content', 'upvotes', 'downvotes'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function question() {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function isAccepted()
    {
        return $this->question && $this->question->accepted_answer_id === $this->answer_id;
    }
}
