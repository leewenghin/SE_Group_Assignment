<?php

namespace App\Http\Controllers\HelpDesk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Complaint;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Status;
use App\Models\VerifiedComplaint;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $complaints = new Complaint;
        $full_total = $complaints->count();
        $validate_search = Validator::make($request->all(), [
            'search' => 'nullable|string|max:255'
        ]);

        if (!$validate_search->fails() && !empty($request->search)) {
            $search = $request->search;
            $array_search = explode(" ", trim($search));

            foreach ($array_search as $s) {
                $complaints = $complaints->where(function (Builder $query) use ($s) {
                    return $query->where('title', 'like', '%'.$s.'%')
                        ->orWhere('description', 'like', '%'.$s.'%');
                });
            }
        }

        $validate_status = Validator::make($request->all(), [
            'status_filter' => 'nullable|integer|exists:statuses,id'
        ]);

        if (!$validate_status->fails() && !empty($request->status_filter) && $request->status_filter > 0) {
            $status_id = $request->status_filter;
            $complaints = $complaints->where('status_id', $status_id);
            if ($status_id == 1) {
                $complaints = $complaints->whereNull('verified_complaint_id');
            }
            else {
                $complaints = $complaints->whereNotNull('verified_complaint_id');
            }
        }

        $complaints = $complaints->orderBy('updated_at', 'desc')->paginate(20)->withQueryString();

        $verified_complaints = VerifiedComplaint::select('id', 'common_title')->orderBy('updated_at', 'desc')->get();

        return view('helpdesk.helpdesk_complaint_list')
            ->with('complaints', $complaints)
            ->with('total_complaints', $full_total)
            ->with('statuses', Status::all())
            ->with('verified_complaints', $verified_complaints);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Complaint $complaint)
    {
        return view('helpdesk.helpdesk_complaint_detail')->with('complaint', $complaint);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
