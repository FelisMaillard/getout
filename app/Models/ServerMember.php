<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServerMember extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'server_id',
        'user_id',
        'role',
        'privacy_consent',
        'privacy_consent_date',
        'last_read_at'
    ];

    protected $casts = [
        'privacy_consent' => 'boolean',
        'privacy_consent_date' => 'datetime',
        'last_read_at' => 'datetime'
    ];

    // Relations
    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Méthodes utilitaires
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasConsent(): bool
    {
        return $this->privacy_consent && $this->privacy_consent_date !== null;
    }
}
