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

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'post_id');
    }
    
    public function votes()
    {
        return $this->hasMany(PostVote::class, 'post_id');
    }
    
    public function bookmarks()
    {
        return $this->hasMany(PostBookmark::class, 'post_id');
    }
    
    /**
     * Check if a user has voted on this post
     */
    public function hasUserVoted($userId)
    {
        return $this->votes()->where('user_id', $userId)->exists();
    }
    
    /**
     * Get a user's vote on this post
     */
    public function getUserVote($userId)
    {
        return $this->votes()->where('user_id', $userId)->first();
    }
    
    /**
     * Check if a user has bookmarked this post
     */
    public function hasUserBookmarked($userId)
    {
        return $this->bookmarks()->where('user_id', $userId)->exists();
    }
    
    /**
     * Get a user's bookmark on this post
     */
    public function getUserBookmark($userId)
    {
        return $this->bookmarks()->where('user_id', $userId)->first();
    }
}
