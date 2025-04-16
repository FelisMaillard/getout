<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SanctionUser extends Model
{
    use HasFactory;

    protected $table = 'sanctions_users';

    protected $fillable = [
        'user_id',
        'type_report_users_id',
        'description',
        'start_at',
        'end_at',
        'status',
        'is_permanent'
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_permanent' => 'boolean'
    ];

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec le type de rapport
    public function typeReport()
    {
        return $this->belongsTo(TypeReportUser::class, 'type_report_users_id');
    }

    // Vérifier si la sanction est actuellement active
    public function isActive(): bool
    {
        // Si le statut est explicitement "inactive", retourner false
        if ($this->status === 'inactive') {
            return false;
        }

        // Si la sanction est permanente, elle est toujours active
        if ($this->is_permanent) {
            return true;
        }

        // Vérifier si nous sommes dans l'intervalle de temps de la sanction
        $now = now();
        return $now->greaterThanOrEqualTo($this->start_at) &&
               ($this->end_at === null || $now->lessThanOrEqualTo($this->end_at));
    }
}
