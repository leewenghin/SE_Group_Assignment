<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'status_id',
        'img_or_video_path',
        'img_or_video_name',
        'mime_type',
        'is_video',
        'verified_complaint_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function verified_complaint()
    {
        return $this->belongsTo(VerifiedComplaint::class, 'verified_complaint_id');
    }
}
