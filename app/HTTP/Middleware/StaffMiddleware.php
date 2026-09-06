<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StaffMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isStaff()) {
            return redirect()->route('staff.login')->with('error', 'Unauthorized access');
        }

        return $next($request);
    }
}