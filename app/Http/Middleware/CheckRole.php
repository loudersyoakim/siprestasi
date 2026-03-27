<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Cek apakah user sudah login dan kode_role-nya sesuai (SA, AD, FK, JR, MHS)
        if (!Auth::check() || !in_array(Auth::user()->role->kode_role, $roles)) {
            abort(403, 'Akses ditolak. Halaman ini bukan untuk Role Anda.');
        }

        return $next($request);
    }
}
