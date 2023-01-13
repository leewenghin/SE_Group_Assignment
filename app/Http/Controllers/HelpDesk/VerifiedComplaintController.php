<?php

namespace App\Http\Controllers\HelpDesk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Complaint;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Department;
use App\Models\Status;
use App\Models\VerifiedComplaint;
use App\Models\ComplaintLogging;

class VerifiedComplaintController extends Controller
{
    public function index(Request $request)
    {
        $verified_complaints = VerifiedComplaint::whereNot('status_id', 1);
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

        return view('helpdesk.helpdesk_complaint_verified_list')
            ->with('verified_complaints', $verified_complaints)
            ->with('total_verified_complaints', $full_total)
            ->with('statuses', Status::all());
    }

    public function create(Request $request)
    {
        $request->validate([
            'common_title' => [
                'required',
                'string',
                'max:255',
            ],
            'groupComplaint' => [
                'required',
                'array',
                'min:1'
            ],
            'groupComplaint.*' => [
                'required',
                'integer',
                'distinct',
            ],
        ]);
        $complaints_id = $request->groupComplaint;
        $complaints_query = Complaint::where('status_id', 1)->whereNull('verified_complaint_id')->where(function (Builder $query) use ($complaints_id) {
            foreach ($complaints_id as $c_id) {
                $query = $query->orWhere('id', $c_id);
            }
            return $query;
        });
        $complaints = $complaints_query->get();

        return view('helpdesk.helpdesk_complaint_verified_create')
            ->with('complaints', $complaints)
            ->with('departments', Department::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'common_title' => [
                'required',
                'string',
                'max:255',
            ],
            'action' => [
                'required',
                'boolean'
            ],
            'department' => [
                'required_if:action,1',
                'integer',
                'exists:departments,id'
            ],
            'remark' => [
                'required',
                'string',
            ],
            'groupComplaint' => [
                'required',
                'array',
                'min:1'
            ],
            'groupComplaint.*' => [
                'required',
                'integer',
                'distinct',
            ],
        ]);

        $complaints_id = $request->groupComplaint;
        $complaints_query = Complaint::where('status_id', 1)->whereNull('verified_complaint_id')->where(function (Builder $query) use ($complaints_id) {
            foreach ($complaints_id as $c_id) {
                $query = $query->orWhere('id', $c_id);
            }
            return $query;
        });
        $complaints = $complaints_query->get();

        $acceptance = ($request->action == "1");
        $verified_complaint = new VerifiedComplaint;

        if ($acceptance) {
            $status_id = 2;
            $complaints_query->update(['status_id' => $status_id]);
            $verified_complaint = $verified_complaint->create([
                'assigned_to_department_id' => $request->department,
                'common_title' => $request->common_title,
                'description' => $request->remark,
                'status_id' => $status_id,
                'complaint_action_id' => 1,
                'finalize_remark' => null,
            ]);
            $verified_complaint->complaint_loggings()->save(new ComplaintLogging([
                'user_id' => Auth::user()->id,
                'assigned_to_department_id' => $request->department,
                'remark' => $request->remark,
                'status_id' => $status_id,
                'complaint_action_id' => 1,
            ]));
            $verified_complaint->complaints()->saveMany($complaints);
        }
        else {
            $status_id = 6;
            $complaints_query->update(['status_id' => $status_id]);
            $verified_complaint = $verified_complaint->create([
                'assigned_to_department_id' => null,
                'common_title' => $request->common_title,
                'description' => $request->remark,
                'status_id' => $status_id,
                'complaint_action_id' => 9,
                'finalize_remark' => $request->remark,
            ]);
            $verified_complaint->complaint_loggings()->save(new ComplaintLogging([
                'user_id' => Auth::user()->id,
                'assigned_to_department_id' => null,
                'remark' => $request->remark,
                'status_id' => $status_id,
                'complaint_action_id' => 2,
            ]));
            $verified_complaint->complaint_loggings()->save(new ComplaintLogging([
                'user_id' => Auth::user()->id,
                'assigned_to_department_id' => null,
                'remark' => $request->remark,
                'status_id' => $status_id,
                'complaint_action_id' => 9,
            ]));
            $verified_complaint->complaints()->saveMany($complaints);
        }

        return redirect()->route('helpdesk.verified_complaints.show', ['verified_complaint' => $verified_complaint->id]);
    }

    public function add_complaint(Request $request)
    {
        $request->validate([
            'group_existing' => [
                'required',
                'integer',
                'exists:verified_complaints,id'
            ],
            'groupComplaint' => [
                'required',
                'array',
                'min:1'
            ],
            'groupComplaint.*' => [
                'required',
                'integer',
                'distinct',
            ],
        ]);

        $complaints_id = $request->groupComplaint;
        $complaints_query = Complaint::where('status_id', 1)->whereNull('verified_complaint_id')->where(function (Builder $query) use ($complaints_id) {
            foreach ($complaints_id as $c_id) {
                $query = $query->orWhere('id', $c_id);
            }
            return $query;
        });
        $complaints = $complaints_query->get();

        $verified_complaint = VerifiedComplaint::find($request->group_existing);
        $complaints_query->update(['status_id' => $verified_complaint->status_id]);
        $verified_complaint->complaints()->saveMany($complaints);

        return redirect()->route('helpdesk.verified_complaints.show', ['verified_complaint' => $verified_complaint->id]);
    }

    public function show(Request $request, VerifiedComplaint $verified_complaint)
    {
        return view('helpdesk.helpdesk_complaint_verify')
            ->with('verified_complaint', $verified_complaint)
            ->with('departments', Department::all());
    }

    public function action(Request $request, VerifiedComplaint $verified_complaint)
    {
        if ($verified_complaint->complaint_action_id == 1) {
            $request->validate([
                'department' => [
                    'required',
                    'integer',
                    'exists:departments,id'
                ],
            ]);
            $department_id = $request->department;
            if ($verified_complaint->assigned_to_department_id != $department_id) {
                $record_complaint_logging = ComplaintLogging::create([
                    'verified_complaint_id' => $verified_complaint->id,
                    'user_id' => Auth::id(),
                    'assigned_to_department_id' => $department_id,
                    'remark' => null,
                    'status_id' => 2,
                    'complaint_action_id' => 8,
                ]);
                $verified_complaint->assigned_to_department_id = $department_id;
                $verified_complaint->status_id = 2;
                $verified_complaint->complaint_action_id = 1;
                $verified_complaint->save();
            }
        }
        else if (in_array($verified_complaint->complaint_action_id, [4,5])) {
            $request->validate([
                'action' => [
                    'required',
                    'boolean'
                ],
                'department' => [
                    'required',
                    'integer',
                    'exists:departments,id'
                ],
                'remark' => [
                    'required',
                    'string',
                ],
            ]);

            $is_approve = ($request->action == "1");
            $department_id = $request->department;
            $complaint_action_id = $is_approve ? 6 : 7;
            $status_id = $is_approve ? 4 : 5;

            $record_complaint_logging_for_action = ComplaintLogging::create([
                'verified_complaint_id' => $verified_complaint->id,
                'user_id' => Auth::id(),
                'assigned_to_department_id' => $verified_complaint->assigned_to_department_id,
                'remark' => $request->remark,
                'status_id' => $status_id,
                'complaint_action_id' => $complaint_action_id,
            ]);

            if ($verified_complaint->assigned_to_department_id != $department_id) {
                $record_complaint_logging_for_change_department = ComplaintLogging::create([
                    'verified_complaint_id' => $verified_complaint->id,
                    'user_id' => Auth::id(),
                    'assigned_to_department_id' => $department_id,
                    'remark' => null,
                    'status_id' => 2,
                    'complaint_action_id' => 8,
                ]);
                $verified_complaint->assigned_to_department_id = $department_id;
                $verified_complaint->status_id = 2;
                $verified_complaint->complaint_action_id = 1;
                $verified_complaint->save();
            }
            else {
                $verified_complaint->status_id = $is_approve ? 6 : 5;
                $verified_complaint->complaint_action_id = $is_approve ? 9 : 1;
                $verified_complaint->save();
            }
        }
        return redirect()->route('helpdesk.verified_complaints.show', ['verified_complaint' => $verified_complaint->id]);
    }
}
