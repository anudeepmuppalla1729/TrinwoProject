<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostImage extends Model
{
    protected $primaryKey = 'image_id';

    public function post() {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
