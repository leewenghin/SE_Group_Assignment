<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'name',
        'description',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'department_id');
    }

    public function complaint_loggings()
    {
        return $this->hasMany(ComplaintLogging::class, 'assigned_to_department_id');
    }

    public function verified_complaints()
    {
        return $this->hasMany(VerifiedComplaint::class, 'assigned_to_department_id');
    }
}
