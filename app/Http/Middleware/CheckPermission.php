<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        // Memanggil fungsi hasPermission() yang sudah kita buat di Model User
        if (!Auth::check() || !Auth::user()->hasPermission($permission)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin (permission) untuk fitur ini.');
        }

        return $next($request);
    }
}
