<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'name',
    ];

    public function GetColor()
    {
        $color = "";
        if ($this->name == "Pending") {
            $color = "warning";
        }
        else if ($this->name == "KIV") {
            $color = "primary";
        }
        else if ($this->name == "Active") {
            $color = "info";
        }
        else if ($this->name == "Done") {
            $color = "secondary";
        }
        else if ($this->name == "Reprocessing") {
            $color = "danger";
        }
        else if ($this->name == "Closed") {
            $color = "success";
        }
        return $color;
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'status_id');
    }

    public function verified_complaints()
    {
        return $this->hasMany(VerifiedComplaint::class, 'status_id');
    }

    public function complaint_loggings()
    {
        return $this->hasMany(ComplaintLogging::class, 'status_id');
    }
}
