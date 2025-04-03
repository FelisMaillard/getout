<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRelation extends Model
{
    use SoftDeletes;

    protected $table = 'users_relations';

    protected $fillable = [
        'user_id',
        'friend_id',
        'status',
        'privacy_consent',
        'privacy_consent_date',
        'read_at'
    ];

    protected $casts = [
        'privacy_consent' => 'boolean',
        'privacy_consent_date' => 'datetime',
        'read_at' => ''
    ];

    // Relation avec l'utilisateur qui envoie la demande
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function friend()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }

}
