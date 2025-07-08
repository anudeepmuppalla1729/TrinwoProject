<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{
    public $incrementing = false;
    protected $primaryKey = null;
    public $timestamps = false;

    public function post() {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function tag() {
        return $this->belongsTo(Tag::class, 'tag_id');
    }
}

