<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class PelangganOrGuest
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return $next($request);
        }

        if (Auth::user()->hasRole('pelanggan')) {
            return $next($request);
        }

        abort(403, 'Access denied.');
    }
}
