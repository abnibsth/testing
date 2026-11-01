<?php

namespace App\Http\Middleware;

use App\Models\Client;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Mengenali client dari subdomain dan memastikan user hanya memakai
 * alamat milik clientnya sendiri.
 *
 *  - vortexsign.web.id / www      : perilaku lama (masa transisi)
 *  - admin.vortexsign.web.id      : khusus superadmin
 *  - {subdomain}.vortexsign.web.id: khusus user dari client tersebut
 *
 * Domain dasar diambil dari APP_URL, jadi staging otomatis memakai domain stagingnya.
 */
class EnsureTenantHost
{
    public const ADMIN_SUBDOMAIN = 'admin';

    public function handle(Request $request, Closure $next): Response
    {
        $base = parse_url(config('app.url'), PHP_URL_HOST);
        $host = strtolower($request->getHost());

        // Domain utama atau host di luar domain dasar: perilaku lama.
        if (!$base || $host === $base || !str_ends_with($host, '.' . $base)) {
            return $next($request);
        }

        $sub = substr($host, 0, -strlen('.' . $base));

        if ($sub === 'www') {
            return $next($request);
        }

        // Hanya satu tingkat subdomain yang diterima (a.b.domain ditolak).
        if (str_contains($sub, '.')) {
            abort(404);
        }

        $user = auth()->user();

        // Portal admin
        if ($sub === self::ADMIN_SUBDOMAIN) {
            if ($user && !$user->isSuperAdmin()) {
                return $this->tolak($request, 'Alamat ini khusus admin.');
            }
            return $next($request);
        }

        // Dashboard client
        $client = Client::where('subdomain', $sub)->first();

        if (!$client) {
            abort(404);
        }
        if (!$client->aktif) {
            abort(403, 'Client ini sedang dinonaktifkan.');
        }

        if ($user && !$user->isSuperAdmin() && (int) $user->client_id !== (int) $client->id) {
            return $this->tolak($request, 'Akun ini tidak terdaftar pada alamat ini.');
        }

        $request->attributes->set('tenant_client', $client);

        return $next($request);
    }

    private function tolak(Request $request, string $pesan): Response
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->withErrors(['username' => $pesan]);
    }
}