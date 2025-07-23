<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnswerReport extends Model
{
    protected $primaryKey = 'report_id';

    protected $fillable = ['reporter_id', 'answer_id', 'reason', 'status'];

    public function reporter() {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function answer() {
        return $this->belongsTo(Answer::class, 'answer_id');
    }
}

