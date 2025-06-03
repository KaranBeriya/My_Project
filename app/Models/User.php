<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
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
}
