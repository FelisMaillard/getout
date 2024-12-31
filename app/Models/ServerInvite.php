<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ServerInvite extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'server_id',
        'inviter_id',
        'invitee_id',
        'token',
        'expires_at',
        'accepted_at',
        'rejected_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invite) {
            $invite->token = Str::random(32);
        });
    }

    // Relations
    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function invitee()
    {
        return $this->belongsTo(User::class, 'invitee_id');
    }

    // Méthodes utilitaires
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isPending(): bool
    {
        return !$this->accepted_at && !$this->rejected_at && !$this->isExpired();
    }

    public function accept(): void
    {
        $this->update(['accepted_at' => now()]);
    }

    public function reject(): void
    {
        $this->update(['rejected_at' => now()]);
    }
}
