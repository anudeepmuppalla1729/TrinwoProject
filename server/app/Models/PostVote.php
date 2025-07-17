<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostVote extends Model
{
    protected $fillable = [
        'user_id', 'post_id', 'vote_type'
    ];
    
    /**
     * Get the user that owns the vote.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Get the post that the vote belongs to.
     */
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}