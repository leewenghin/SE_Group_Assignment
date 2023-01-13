<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnDepartment
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
        $verified_complaint = $request->verified_complaint;
        $is_own_department = (Auth::user()->department_id != null) ? ($verified_complaint->assigned_to_department_id == Auth::user()->department_id) : false;
        if ($is_own_department) {
            return $next($request);
        }
        return abort(404);
    }
}
