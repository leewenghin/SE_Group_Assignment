<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifiedComplaint extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'assigned_to_department_id',
        'common_title',
        'description',
        'status_id',
        'complaint_action_id',
        'finalize_remark'
    ];

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'verified_complaint_id');
    }

    public function complaint_loggings()
    {
        return $this->hasMany(ComplaintLogging::class, 'verified_complaint_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'assigned_to_department_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function complaint_action()
    {
        return $this->belongsTo(ComplaintAction::class, 'complaint_action_id');
    }
}
