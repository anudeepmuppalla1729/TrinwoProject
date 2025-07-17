<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $primaryKey = 'question_id';

    protected $fillable = [
        'user_id', 'title', 'description', 'visibility', 'is_closed', 'upvotes', 'downvotes'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function answers() {
        return $this->hasMany(Answer::class, 'question_id');
    }

    public function acceptedAnswer() {
        return $this->belongsTo(Answer::class, 'accepted_answer_id');
    }

    public function tags() {
        return $this->belongsToMany(Tag::class, 'question_tags', 'question_id', 'tag_id');
    }

    public function bookmarks()
    {
        return $this->hasMany(\App\Models\QuestionBookmark::class, 'question_id');
    }
}

