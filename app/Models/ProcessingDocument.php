<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessingDocument extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'complaint_logging_id',
        'file_path',
        'file_name',
        'mime_type',
    ];

    public function complaint_logging()
    {
        return $this->belongsTo(ComplaintLogging::class, 'complaint_logging_id');
    }
}
