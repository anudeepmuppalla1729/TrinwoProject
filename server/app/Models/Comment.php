<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // Tell Laravel about your custom primary key
    protected $primaryKey = 'comment_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['post_id', 'user_id', 'comment_text'];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
