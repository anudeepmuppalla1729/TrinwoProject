<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnswerVote extends Model
{
    protected $primaryKey = 'vote_id';

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function answer() {
        return $this->belongsTo(Answer::class, 'answer_id');
    }
}
