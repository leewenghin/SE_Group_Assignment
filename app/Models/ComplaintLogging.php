<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintLogging extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'verified_complaint_id',
        'user_id',
        'assigned_to_department_id',
        'remark',
        'status_id',
        'complaint_action_id',
    ];

    public function processing_document()
    {
        return $this->hasOne(ProcessingDocument::class, 'complaint_logging_id');
    }

    public function verified_complaint()
    {
        return $this->belongsTo(VerifiedComplaint::class, 'verified_complaint_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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
