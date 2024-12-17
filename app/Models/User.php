<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nom',
        'prenom',
        'tag',
        'email',
        'password',
        'profile_photo_url',
        'searchable',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'searchable' => 'boolean',
    ];

    // Mutator pour s'assurer que le tag est toujours en minuscules
    public function setTagAttribute($value)
    {
        $this->attributes['tag'] = strtolower($value);
    }
}
