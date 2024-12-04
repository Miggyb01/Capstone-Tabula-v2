<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable
{
    protected $fillable = [
        'full_name', 
        'username', 
        'email', 
        'password', 
        'role', 
        'status', 
        'email_verification_token',
        'email_verified_at'
    ];

    public function hasVerifiedEmail()
    {
        return $this->email_verified_at !== null;
    }
}
