<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $primaryKey = 'post_id';

    protected $fillable = [
        'user_id', 'heading', 'details', 'visibility', 'upvotes', 'downvotes'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function images() {
        return $this->hasMany(PostImage::class, 'post_id');
    }

    public function tags() {
        return $this->belongsToMany(Tag::class, 'post_tags', 'post_id', 'tag_id');
    }
}
