<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $table = 'theme';

    protected $fillable = [
        'name',
        'description',
        'color',
    ];

    public function servers()
    {
        return $this->belongsToMany(Server::class, 'server_theme');
    }
}
