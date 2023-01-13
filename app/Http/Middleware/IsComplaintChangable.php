<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsComplaintChangable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $complaint = $request->complaint;
        $is_complaint_changable = ($complaint->status_id == 1 && $complaint->verified_complaint_id == null);

        if ($is_complaint_changable) {
            return $next($request);
        }
        return redirect()->route('complaints.index');
    }
}
