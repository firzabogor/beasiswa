<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || Auth::user()->email !== 'admin@bpsdmp.go.id') {
            abort(403, 'Akses ditolak. Panel ini hanya dapat diakses oleh akun resmi Administrator BPSDMP.');
        }
        return $next($request);
    }
}
