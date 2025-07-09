<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'email', 'password', 'name', 'profile_pic',
        'phone', 'age', 'gender', 'studying_in',
        'expert_in', 'interests'
    ];

    public function posts() {
        return $this->hasMany(Post::class, 'user_id');
    }

    public function questions() {
        return $this->hasMany(Question::class, 'user_id');
    }

    public function answers() {
        return $this->hasMany(Answer::class, 'user_id');
    }

    public function followers() {
        return $this->hasMany(Follower::class, 'user_id');
    }

    public function following() {
        return $this->hasMany(Follower::class, 'follower_user_id');
    }
}
