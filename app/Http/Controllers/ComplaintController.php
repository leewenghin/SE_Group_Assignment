<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Complaint;
use App\Models\Status;
use App\Rules\ImageOrVideo;
use Illuminate\Support\Str;

class ComplaintController extends Controller
{
    public function __construct()
    {
        $this->middleware('own_complaint')->only(['show', 'edit', 'update', 'destroy']);
        $this->middleware('is_complaint_changable')->only(['edit', 'update', 'destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $complaints = Complaint::where('user_id', Auth::user()->id);
        $full_total = $complaints->count();
        $validate_search = Validator::make($request->all(), [
            'search' => 'nullable|string|max:255'
        ]);

        if (!$validate_search->fails() && !empty($request->search)) {
            $search = $request->search;
            $array_search = explode(" ", trim($search));

            // Below is searching base on one column including all specific value in search term.

            // $complaints = $complaints->where(function (Builder $query) use ($array_search) {
            //     return $query->where(function (Builder $q) use ($array_search) {
            //         foreach ($array_search as $s) {
            //             $q = $q->where('title', 'like', '%'.$s.'%');
            //         }
            //         return $q;
            //     })->orWhere(function (Builder $q) use ($array_search) {
            //         foreach ($array_search as $s) {
            //             $q = $q->where('description', 'like', '%'.$s.'%');
            //         }
            //         return $q;
            //     });
            // });

            // Below is searching base on multiple column including in any specific value search term.

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

        return view('student.student_complaint_list')
            ->with('complaints', $complaints)
            ->with('total_complaints', $full_total)
            ->with('statuses', Status::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('student.student_complaint_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'file' => [
                'required',
                'file',
                new ImageOrVideo,
                'mimes:jpg,jpeg,png,bmp,gif,svg,webp,mkv,webm,flv,f4v,swf,ogg,ogv,avi,wmv,rmvb,mp4,m4p,m4v,mov,avchd,mpg,mpeg,mp2,3gp,3g2',
                'max:10240',
            ],
        ]);

        $complaint = Complaint::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::user()->id,
            'status_id' => 1,
            'verified_complaint_id' => null,
        ]);

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $this->createFolder($this->getComplaintDocumentPath());

            $original_file_name = $request->file('file')->getClientOriginalName();
            $mime_types = $request->file('file')->getMimeType();

            $file_name = $this->createUniqueFileName($complaint->id, $original_file_name);
            $request->file('file')->move($this->getComplaintDocumentPath(), $file_name);

            $complaint->img_or_video_path = $this->getComplaintDocumentPath();
            $complaint->img_or_video_name = $file_name;
            $complaint->mime_type = $mime_types;
            $flag = false;
            if (Str::startsWith($mime_types, "video/")) {
                $flag = true;
            }

            $complaint->is_video = $flag;

            $complaint->save();
        }

        return redirect()->route('complaints.show', ['complaint' => $complaint->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Complaint $complaint)
    {
        return view('student.student_complaint_view')->with('complaint', $complaint);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Complaint $complaint)
    {
        return view('student.student_complaint_edit')->with('complaint', $complaint);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Complaint $complaint)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'file' => [
                'file',
                new ImageOrVideo,
                'mimes:jpg,jpeg,png,bmp,gif,svg,webp,mkv,webm,flv,f4v,swf,ogg,ogv,avi,wmv,rmvb,mp4,m4p,m4v,mov,avchd,mpg,mpeg,mp2,3gp,3g2',
                'max:10240',
            ],
        ]);

        $update_complaint = $complaint;

        $update_complaint->title = $request->title;
        $update_complaint->description = $request->description;


        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            if ($update_complaint->img_or_video_path != null && $update_complaint->img_or_video_name != null) {
                $this->deleteFile($update_complaint->img_or_video_path.$update_complaint->img_or_video_name);
            }

            $this->createFolder($this->getComplaintDocumentPath());

            $original_file_name = $request->file('file')->getClientOriginalName();
            $mime_types = $request->file('file')->getMimeType();

            $file_name = $this->createUniqueFileName($update_complaint->id, $original_file_name);
            $request->file('file')->move($this->getComplaintDocumentPath(), $file_name);

            $update_complaint->img_or_video_path = $this->getComplaintDocumentPath();
            $update_complaint->img_or_video_name = $file_name;
            $update_complaint->mime_type = $mime_types;
            $flag = false;
            if (Str::startsWith($mime_types, "video/")) {
                $flag = true;
            }

            $update_complaint->is_video = $flag;
        }

        $update_complaint->save();

        return redirect()->route('complaints.show', ['complaint' => $update_complaint->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Complaint $complaint)
    {
        $delete_complaint = $complaint;
        $title = $complaint->title;
        if ($delete_complaint->img_or_video_path != null && $delete_complaint->img_or_video_name != null) {
            $this->deleteFile($delete_complaint->img_or_video_path.$delete_complaint->img_or_video_name);
        }
        $delete_complaint->delete();
        return redirect()->route('complaints.index')->with('action_message', 'Successfully to delete complaint ('.$title.')!');
    }
}
