<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChannelMember extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'channel_id',
        'user_id',
        'last_read_at'
    ];

    protected $casts = [
        'last_read_at' => 'datetime'
    ];

    // Relations
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Méthodes utilitaires
    public function hasUnreadMessages(): bool
    {
        if (!$this->last_read_at) {
            return $this->channel->messages()->exists();
        }

        return $this->channel->messages()
            ->where('created_at', '>', $this->last_read_at)
            ->exists();
    }
}
