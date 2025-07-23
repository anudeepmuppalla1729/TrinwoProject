<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostReport extends Model
{
    protected $primaryKey = 'report_id';

    protected $fillable = ['reporter_id', 'post_id', 'reason', 'status'];

    public function reporter() {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function post() {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
