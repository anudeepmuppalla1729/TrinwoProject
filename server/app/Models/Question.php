<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $primaryKey = 'question_id';

    protected $fillable = [
        'user_id', 'title', 'description', 'visibility', 'is_closed'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function answers() {
        return $this->hasMany(Answer::class, 'question_id');
    }

    public function tags() {
        return $this->belongsToMany(Tag::class, 'question_tags', 'question_id', 'tag_id');
    }
}

