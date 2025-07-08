<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $primaryKey = 'user_id';

    public function posts() {
        return $this->hasMany(Post::class, 'user_id');
    }

    public function answers() {
        return $this->hasMany(Answer::class, 'user_id');
    }

    public function postVotes() {
        return $this->hasMany(PostVote::class, 'user_id');
    }

    public function answerVotes() {
        return $this->hasMany(AnswerVote::class, 'user_id');
    }

    public function bookmarks() {
        return $this->hasMany(Bookmark::class, 'user_id');
    }

    public function followers() {
        return $this->hasMany(Follower::class, 'user_id');
    }

    public function following() {
        return $this->hasMany(Follower::class, 'follower_user_id');
    }
}
