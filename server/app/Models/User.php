<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'status',
        'avatar',
        'last_login_at',
        'profile_pic',
        'phone', 
        'age', 
        'gender', 
        'studying_in',
        'expert_in', 
        'interests', 
        'bio'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


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
    
    public function isFollowing(User $user) {
        return $this->following()->where('user_id', $user->user_id)->exists();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'user_id');
    }
    
    public function postVotes()
    {
        return $this->hasMany(PostVote::class, 'user_id');
    }

    /**
     * Check if the user can login
     */
    public function canLogin()
    {
        return $this->status === 'active';
    }

    /**
     * Check if the user is banned
     */
    public function isBanned()
    {
        return $this->status === 'banned';
    }

    /**
     * Check if the user is inactive
     */
    public function isInactive()
    {
        return $this->status === 'inactive';
    }

}
