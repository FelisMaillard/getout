<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'channel_id',
        'user_id',
        'content',
        'type',
        'metadata',
        'edited_at'
    ];

    protected $casts = [
        'metadata' => 'array',
        'edited_at' => 'datetime'
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
    public function isEdited(): bool
    {
        return $this->edited_at !== null;
    }

    public function edit(string $newContent): void
    {
        $this->update([
            'content' => $newContent,
            'edited_at' => now()
        ]);
    }
}
