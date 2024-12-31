<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Server extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'owner_id',
        'privacy_type',
        'max_members'
    ];

    protected static function boot()
    {
        parent::boot();

        // Génère automatiquement un slug lors de la création
        static::creating(function ($server) {
            $server->slug = Str::slug($server->name);
        });
    }

    // Relations
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->hasMany(ServerMember::class);
    }

    public function channels()
    {
        return $this->hasMany(Channel::class);
    }

    public function invites()
    {
        return $this->hasMany(ServerInvite::class);
    }

    // Méthodes utilitaires
    public function isOwner(User $user): bool
    {
        return $this->owner_id === $user->id;
    }

    public function isMember(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    public function getMemberRole(User $user): ?string
    {
        $member = $this->members()->where('user_id', $user->id)->first();
        return $member ? $member->role : null;
    }
}
