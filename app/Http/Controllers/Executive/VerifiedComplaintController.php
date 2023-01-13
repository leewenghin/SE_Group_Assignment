<?php

namespace App\Http\Controllers\Executive;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;
use App\Models\VerifiedComplaint;
use App\Models\Status;
use App\Models\Department;
use App\Models\ComplaintLogging;
use App\Models\ProcessingDocument;

class VerifiedComplaintController extends Controller
{
    public function __construct()
    {
        $this->middleware('own_department')->except(['dashboard', 'index']);
    }

    public function dashboard()
    {
        $vc_kiv_number = 0;
        $vc_active_number = 0;
        $vc_done_number = 0;
        $vc_reprocessing_number = 0;
        $vc_closed_number = 0;
        if (Auth::user()->department_id != null) {
            $verified_complaints = VerifiedComplaint::where('assigned_to_department_id', Auth::user()->department_id)->get();
            $vc_kiv_number = $verified_complaints->where('status_id', 2)
                                    ->count();
            $vc_active_number = $verified_complaints->where('status_id', 3)
                                    ->count();
            $vc_done_number = $verified_complaints->where('status_id', 4)
                                    ->count();
            $vc_reprocessing_number = $verified_complaints->where('status_id', 5)
                                    ->count();
            $vc_closed_number = $verified_complaints->where('status_id', 6)
                                    ->count();
        }
        $verified_complaint_status = [
            ["name" => "Keep in view", "number" => $vc_kiv_number],
            ["name" => "Active", "number" => $vc_active_number],
            ["name" => "Done", "number" => $vc_done_number],
            ["name" => "Reprocessing", "number" => $vc_reprocessing_number],
            ["name" => "Closed", "number" => $vc_closed_number],
        ];

        return view('executive.executive_dashboard')
            ->with('status_verified_complaint', $verified_complaint_status);
    }

    public function index(Request $request)
    {
        $verified_complaints = VerifiedComplaint::whereNot('status_id', 1);
        if (Auth::user()->department_id != null) {
            $verified_complaints = $verified_complaints->where('assigned_to_department_id', Auth::user()->department_id);
        }
        else {
            // If doesn't have department then filter to nothing data
            $verified_complaints = $verified_complaints->whereNull('assigned_to_department_id')->whereNotNull('assigned_to_department_id');
        }

        $full_total = $verified_complaints->count();
        $validate_search = Validator::make($request->all(), [
            'search' => 'nullable|string|max:255'
        ]);

        if (!$validate_search->fails() && !empty($request->search)) {
            $search = $request->search;
            $array_search = explode(" ", trim($search));

            foreach ($array_search as $s) {
                $verified_complaints = $verified_complaints->where(function (Builder $query) use ($s) {
                    return $query->where('common_title', 'like', '%'.$s.'%')
                        ->orWhere('description', 'like', '%'.$s.'%')
                        ->orWhere('finalize_remark', 'like', '%'.$s.'%');
                });
            }
        }

        $validate_status = Validator::make($request->all(), [
            'status_filter' => 'nullable|integer|exists:statuses,id'
        ]);

        if (!$validate_status->fails() && !empty($request->status_filter) && $request->status_filter > 0) {
            $status_id = $request->status_filter;
            $verified_complaints = $verified_complaints->where('status_id', $status_id);
        }

        $verified_complaints = $verified_complaints->orderBy('updated_at', 'desc')->paginate(20)->withQueryString();

        return view('executive.executive_complaint_list')
            ->with('verified_complaints', $verified_complaints)
            ->with('total_verified_complaints', $full_total)
            ->with('statuses', Status::all());
    }

    public function show(VerifiedComplaint $verified_complaint)
    {
        return view('executive.executive_complaint_show')
            ->with('verified_complaint', $verified_complaint)
            ->with('departments', Department::all());
    }

    public function action(Request $request, VerifiedComplaint $verified_complaint)
    {
        $vc = $verified_complaint;
        if ($vc->complaint_action_id == 1) {
            $request->validate([
                'action' => [
                    'required',
                    'boolean',
                ],
                'remark' => [
                    'required',
                    'string',
                ],
                'file' => [
                    'required_if:action,0',
                    'max:10240'
                ],
            ]);
            $department_id = $vc->assigned_to_department_id;
            $is_accept = $request->action == "1";
            $complaint_action = $is_accept ? 3 : 4;
            $status_id = $is_accept ? 3 : 2;

            if ($is_accept) {
                $vc->complaint_loggings()->create([
                    'user_id' => Auth::id(),
                    'assigned_to_department_id' => $department_id,
                    'remark' => $request->remark,
                    'status_id' => $status_id,
                    'complaint_action_id' => $complaint_action,
                ]);
                $vc->status_id = $status_id;
                $vc->complaint_action_id = $complaint_action;
                $vc->save();
            }
            else {
                $complaint_logging = ComplaintLogging::create([
                    'verified_complaint_id' => $vc->id,
                    'user_id' => Auth::id(),
                    'assigned_to_department_id' => $department_id,
                    'remark' => $request->remark,
                    'status_id' => $status_id,
                    'complaint_action_id' => $complaint_action,
                ]);
                if ($request->hasFile('file') && $request->file('file')->isValid()) {
                    $processing_document = ProcessingDocument::create([
                        'complaint_logging_id' => $complaint_logging->id,
                        'file_path' => null,
                        'file_name' => null,
                        'mime_type' => null,
                    ]);

                    $this->createFolder($this->getProcessingDocumentPath());
                    $original_file_name = $request->file('file')->getClientOriginalName();
                    $mime_types = $request->file('file')->getMimeType();
                    $file_name = $this->createUniqueFileName($processing_document->id, $original_file_name);
                    $request->file('file')->move($this->getProcessingDocumentPath(), $file_name);

                    $processing_document->file_path = $this->getProcessingDocumentPath();
                    $processing_document->file_name = $file_name;
                    $processing_document->mime_type = $mime_types;

                    $processing_document->save();
                }

                $vc->status_id = $status_id;
                $vc->complaint_action_id = $complaint_action;
                $vc->save();
            }
        }
        else if ($vc->complaint_action_id == 3) {
            $request->validate([
                'remark' => [
                    'required',
                    'string',
                ],
                'file' => [
                    'required',
                    'max:10240'
                ],
            ]);
            $department_id = $vc->assigned_to_department_id;
            $complaint_action = 5;
            $status_id = 4;

            $complaint_logging = ComplaintLogging::create([
                'verified_complaint_id' => $vc->id,
                'user_id' => Auth::id(),
                'assigned_to_department_id' => $department_id,
                'remark' => $request->remark,
                'status_id' => $status_id,
                'complaint_action_id' => $complaint_action,
            ]);
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                $processing_document = ProcessingDocument::create([
                    'complaint_logging_id' => $complaint_logging->id,
                    'file_path' => null,
                    'file_name' => null,
                    'mime_type' => null,
                ]);

                $this->createFolder($this->getProcessingDocumentPath());
                $original_file_name = $request->file('file')->getClientOriginalName();
                $mime_types = $request->file('file')->getMimeType();
                $file_name = $this->createUniqueFileName($processing_document->id, $original_file_name);
                $request->file('file')->move($this->getProcessingDocumentPath(), $file_name);

                $processing_document->file_path = $this->getProcessingDocumentPath();
                $processing_document->file_name = $file_name;
                $processing_document->mime_type = $mime_types;

                $processing_document->save();
            }

            $vc->status_id = $status_id;
            $vc->complaint_action_id = $complaint_action;
            $vc->save();
        }

        return redirect()->route('executive.verified_complaints.show', ['verified_complaint' => $vc->id]);
    }
}
