<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintAction extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'name',
    ];

    public function complaint_loggings()
    {
        return $this->hasMany(ComplaintLogging::class, 'complaint_action_id');
    }

    public function verified_complaints()
    {
        return $this->hasMany(VerifiedComplaint::class, 'complaint_action_id');
    }
}
