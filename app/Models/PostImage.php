<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostImage extends Model
{
    protected $primaryKey = 'image_id';

    protected $fillable = ['post_id', 'image_url'];

    public function post() {
        return $this->belongsTo(Post::class, 'post_id');
    }
}

