<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! session()->has('nama')) {
            return redirect('login');
        }

        return $next($request);
    }
}
