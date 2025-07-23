<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionReport extends Model
{
    protected $primaryKey = 'report_id';

    protected $fillable = ['reporter_id', 'question_id', 'reason', 'status'];

    public function reporter() {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function question() {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
