<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Client extends Model
{
    /** Subdomain yang tidak boleh dipakai client. */
    public const RESERVED_SUBDOMAINS = [
        'admin', 'www', 'api', 'staging', 'dev', 'test', 'app', 'login',
        'dashboard', 'mail', 'webmail', 'smtp', 'ftp', 'cpanel', 'ns1', 'ns2',
    ];

    protected $table = 'clients';
    protected $fillable = ['nama', 'kode', 'aktif', 'subdomain'];
    protected $casts = ['aktif' => 'boolean'];

    public function users()
    {
        return $this->hasMany(User::class, 'client_id');
    }

    /**
     * Buat subdomain unik dari teks (misalnya nama atau kode client).
     * Hasil: huruf kecil/angka/tanda hubung, 3-30 karakter, tidak bentrok
     * dengan nama cadangan maupun client lain.
     */
    public static function buatSubdomain(string $sumber, ?int $abaikanId = null): string
    {
        $dasar = trim(substr(Str::slug($sumber), 0, 24), '-');

        if (strlen($dasar) < 3) {
            $dasar = $dasar === '' ? 'client' : 'client-' . $dasar;
        }

        $kandidat = $dasar;
        $i = 2;

        while (
            in_array($kandidat, self::RESERVED_SUBDOMAINS, true)
            || static::where('subdomain', $kandidat)
                ->when($abaikanId, fn ($q) => $q->where('id', '!=', $abaikanId))
                ->exists()
        ) {
            $kandidat = $dasar . '-' . $i++;
        }

        return $kandidat;
    }

    /** Aturan format subdomain untuk validasi input manual. */
    public static function subdomainValid(string $subdomain): bool
    {
        return (bool) preg_match('/^[a-z0-9][a-z0-9-]{1,28}[a-z0-9]$/', $subdomain)
            && !in_array($subdomain, self::RESERVED_SUBDOMAINS, true);
    }

    /** Alamat dashboard client ini, misalnya https://tokoabc.vortexsign.web.id */
    public function url(): string
    {
        $appUrl = config('app.url');

        if (!$this->subdomain) {
            return $appUrl;
        }

        return (parse_url($appUrl, PHP_URL_SCHEME) ?: 'https')
            . '://' . $this->subdomain . '.' . parse_url($appUrl, PHP_URL_HOST);
    }
}
