<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $fillable = [
        'username', 'password', 'name', 'status'
    ];
    
    protected $hidden = ['password'];
    
    protected $casts = [
        'password' => 'hashed',
    ];
    
    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }
    
    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getAttribute($this->getAuthIdentifierName());
    }

    /**
     * Check if the admin can login
     */
    public function canLogin()
    {
        return $this->status === 'active';
    }

    /**
     * Check if the admin is inactive
     */
    public function isInactive()
    {
        return $this->status === 'inactive';
    }
} 