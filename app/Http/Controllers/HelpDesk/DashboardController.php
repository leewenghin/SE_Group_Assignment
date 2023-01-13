<?php

namespace App\Http\Controllers\HelpDesk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\VerifiedComplaint;

class DashboardController extends Controller
{
    public function index()
    {
        $complaints = Complaint::all();
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

        $verified_complaints = VerifiedComplaint::all();
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

        $verified_complaint_status = [
            ["name" => "Keep in view", "number" => $vc_kiv_number],
            ["name" => "Active", "number" => $vc_active_number],
            ["name" => "Done", "number" => $vc_done_number],
            ["name" => "Reprocessing", "number" => $vc_reprocessing_number],
            ["name" => "Closed", "number" => $vc_closed_number],
        ];

        return view('helpdesk.helpdesk_dashboard')
            ->with('status_complaint', $status)
            ->with('status_verified_complaint', $verified_complaint_status);
    }
}
