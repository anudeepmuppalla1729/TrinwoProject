<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\QuestionReport;
use App\Models\AnswerReport;

class Question extends Model
{
    protected $primaryKey = 'question_id';

    protected $fillable = [
        'user_id', 'title', 'description', 'visibility', 'is_closed'
    ];

    protected static function boot()
    {
        parent::boot();

        // Cascade delete related data when question is deleted
        static::deleting(function ($question) {
            // Delete question reports first
            QuestionReport::where('question_id', $question->question_id)->delete();
            
            // Delete answer reports first (before deleting answers)
            $answerIds = $question->answers()->pluck('answer_id');
            if ($answerIds->count() > 0) {
                AnswerReport::whereIn('answer_id', $answerIds)->delete();
            }
            
            // Delete answers (which will cascade delete answer votes)
            $question->answers()->delete();
            
            // Delete question tags
            $question->tags()->detach();
            
            // Delete bookmarks
            $question->bookmarks()->delete();
        });
    }

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

