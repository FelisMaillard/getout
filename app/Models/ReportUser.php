<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportUser extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'reporter_id',
        'reported_user_id',
        'type_report_id',
        'description',
        'evidence',
        'status',
        'resolution_note',
        'resolved_by',
        'resolved_at'
    ];

    protected $casts = [
        'evidence' => 'array',
        'resolved_at' => 'datetime'
    ];

    // Relations
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }

    public function typeReport()
    {
        return $this->belongsTo(TypeReportUser::class, 'type_report_id');
    }

    public function resolvedBy()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
