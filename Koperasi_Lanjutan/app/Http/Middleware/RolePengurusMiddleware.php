<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolePengurusMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::guard('pengurus')->user();

        if (!$user) {
            abort(403, 'Anda tidak memiliki akses (belum login sebagai pengurus).');
        }

        // Jika role user tidak ada dalam daftar role yang diperbolehkan
        if (!in_array($user->role, $roles)) {
            abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        return $next($request);
    }
}
