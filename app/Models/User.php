<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'prenom',
        'nom',
        'tag',
        'email',
        'password',
        'bio',
        'private',
        'last_tag_change',
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

    public function sentRelations()
    {
        return $this->hasMany(UserRelation::class, 'user_id');
    }

    // Relations que l'utilisateur a reçues
    public function receivedRelations()
    {
        return $this->hasMany(UserRelation::class, 'friend_id');
    }

    // Obtenir les amis (relations acceptées)
    public function friends()
    {
        return $this->sentRelations()
            ->where('status', 'accepted')
            ->whereNotNull('privacy_consent_date');
    }

    // Obtenir le nombre de followers (relations acceptées)
    public function followersCount()
    {
        return $this->receivedRelations()
            ->where('status', 'accepted')
            ->whereNotNull('privacy_consent_date')
            ->count();
    }

    // Obtenir le nombre de personnes suivies
    public function followingCount()
    {
        return $this->sentRelations()
            ->where('status', 'accepted')
            ->whereNotNull('privacy_consent_date')
            ->count();
    }

    // Vérifier si l'utilisateur courant suit cet utilisateur
    public function isFollowedBy(User $user)
    {
        return $this->receivedRelations()
            ->where('user_id', $user->id)
            ->where('status', 'accepted')
            ->whereNotNull('privacy_consent_date')
            ->exists();
    }

    /**
     * Les serveurs dont l'utilisateur est propriétaire
     */
    public function ownedServers()
    {
        return $this->hasMany(Server::class, 'owner_id');
    }

    /**
     * Les serveurs dont l'utilisateur est membre
     */
    public function memberServers()
    {
        return $this->belongsToMany(Server::class, 'server_members')
                    ->withPivot('role', 'privacy_consent', 'privacy_consent_date')
                    ->withTimestamps();
    }

    /**
     * Tous les serveurs de l'utilisateur (possédés + membre)
     */
    public function servers()
    {
        return $this->memberServers();
    }
}
