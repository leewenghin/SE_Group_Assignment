<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $avatar_path = 'documents/avatar/';
    private $processing_document_path = 'documents/processing_document/';
    private $complaint_document_path = 'documents/complaint_document/';

    public function getAvatarPath()
    {
        return $this->avatar_path;
    }

    public function getProcessingDocumentPath()
    {
        return $this->processing_document_path;
    }

    public function getComplaintDocumentPath()
    {
        return $this->complaint_document_path;
    }

    public function createFolder($path)
    {
        if (!File::exists($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
    }

    public function createUniqueFileName($id, $original_file_name)
    {
        $random_str = Str::random(32);
        $time_now = Carbon::now();
        $write_time = $time_now->format('YmdHis');
        $file_name = $random_str.'_'.$write_time.'_'.$id.'_'.$original_file_name;
        return $file_name;
    }

    public function deleteFile($file_path)
    {
        if (File::exists($file_path)) {
            File::delete($file_path);
        }
    }
}
