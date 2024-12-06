<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class JudgeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('user') || Session::get('user')['role'] !== 'judge') {
            return redirect()->route('login')->with('error', 'Please login as a judge to access this area.');
        }

        return $next($request);
    }
}