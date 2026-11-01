<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Setelah password direset admin, user diarahkan ke halaman ganti password
 * dan tidak bisa membuka halaman lain sampai password diganti.
 */
class ForcePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->must_change_password && !$request->routeIs('password.ganti', 'password.ganti.simpan', 'logout')) {
            return redirect()->route('password.ganti');
        }

        return $next($request);
    }
}
