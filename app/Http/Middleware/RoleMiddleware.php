<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
{
    // Periksa apakah pengguna sudah login dan memiliki role yang sesuai
    if (Auth::check() && Auth::user()->role == $role) {
        return $next($request);
    }

    // Redirect jika role tidak sesuai
    return redirect('/login')->withErrors(['message' => 'Akses ditolak']);
}

}
