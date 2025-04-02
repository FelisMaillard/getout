<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeReportUser extends Model
{
    protected $fillable = [
        'name',
        'description',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    public function reports()
    {
        return $this->hasMany(ReportUser::class);
    }
}
