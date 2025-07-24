<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSeenPost extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'user_seen_posts';
    protected $fillable = ['user_id', 'post_id', 'created_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
} 