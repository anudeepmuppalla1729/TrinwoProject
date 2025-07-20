<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class QuestionTag extends Pivot
{
    protected $primaryKey = 'question_tags';

    protected $fillable = ['question_id', 'tag_id'];
}
