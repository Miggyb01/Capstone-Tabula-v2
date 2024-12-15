<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        \Log::info('AdminMiddleware Check:', [
            'session' => Session::all(),
            'hasUser' => Session::has('user'),
            'userRole' => Session::get('user.role')
        ]);
    
        if (!Session::has('user') || Session::get('user')['role'] !== 'admin') {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }
    
        return $next($request);
    }
}
