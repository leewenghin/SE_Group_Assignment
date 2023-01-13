<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Complaint;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $complaints = Complaint::where('user_id', Auth::id())->get();
        $pending_number = $complaints->where('status_id', 1)
                                    ->whereNull('verified_complaint_id')
                                    ->count();
        $kiv_number = $complaints->where('status_id', 2)
                                ->whereNotNull('verified_complaint_id')
                                ->count();
        $active_number = $complaints->where('status_id', 3)
                                ->whereNotNull('verified_complaint_id')
                                ->count();
        $done_number = $complaints->where('status_id', 4)
                                ->whereNotNull('verified_complaint_id')
                                ->count();
        $reprocessing_number = $complaints->where('status_id', 5)
                                ->whereNotNull('verified_complaint_id')
                                ->count();
        $closed_number = $complaints->where('status_id', 6)
                                ->whereNotNull('verified_complaint_id')
                                ->count();

        $status = [
            ["name" => "Pending", "number" => $pending_number],
            ["name" => "Keep in view", "number" => $kiv_number],
            ["name" => "Active", "number" => $active_number],
            ["name" => "Done", "number" => $done_number],
            ["name" => "Reprocessing", "number" => $reprocessing_number],
            ["name" => "Closed", "number" => $closed_number],
        ];

        return view('student.student_dashboard')
            ->with('status', $status);
    }
}
