<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'role', 'contact', 'email', 'password', 'profile_picture'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // Automatically hash password when setting it
    public function setPasswordAttribute($password)
    {        
        $this->attributes['password'] = Hash::needsRehash($password) ? Hash::make($password) : $password;
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
}
