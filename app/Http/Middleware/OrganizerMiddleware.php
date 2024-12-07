<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class OrganizerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('user') || Session::get('user')['role'] !== 'organizer') {
            return redirect()->route('login')
                ->with('error', 'Please login as an organizer to access this area.');
        }

        return $next($request);
    }
}