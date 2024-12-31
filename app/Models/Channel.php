<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Channel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'server_id',
        'name',
        'slug',
        'description',
        'type',
        'is_private'
    ];

    protected $casts = [
        'is_private' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($channel) {
            $channel->slug = Str::slug($channel->name);
        });
    }

    // Relations
    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function members()
    {
        return $this->hasMany(ChannelMember::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Méthodes utilitaires
    public function canAccess(User $user): bool
    {
        if (!$this->is_private) {
            return $this->server->isMember($user);
        }

        return $this->members()->where('user_id', $user->id)->exists();
    }
}
