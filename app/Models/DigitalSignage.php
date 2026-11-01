<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClient;
use App\Support\Tenant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DigitalSignage extends Model
{
    use HasFactory, BelongsToClient;

    protected $table = 'digital_signage';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_aktivasi' => 'date',
        'unduh_terakhir'   => 'datetime',
        'last_ping'        => 'datetime',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
    ];

    /**
     * Otomatis ubah device_id menjadi HURUF BESAR dan hapus spasi.
     */
    public function setDeviceIdAttribute($value): void
    {
        $this->attributes['device_id'] = $value !== null
            ? strtoupper(trim((string) $value))
            : null;
    }

    /**
     * Pastikan saat dibaca dari model juga tampil HURUF BESAR.
     */
    public function getDeviceIdAttribute($value): ?string
    {
        return $value !== null ? strtoupper((string) $value) : null;
    }

    public function getTanggalAktivasiAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return $value instanceof Carbon ? $value : Carbon::parse($value);
    }

    public function getUnduhTerakhirAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return $value instanceof Carbon ? $value : Carbon::parse($value);
    }

    public function getLastPingAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return $value instanceof Carbon ? $value : Carbon::parse($value);
    }

    /**
     * Relasi ke Toko.
     */
    public function toko()
    {
        return $this->belongsTo(Toko::class, 'toko_id');
    }

    /**
     * Relasi ke Model Signage (TV).
     * Menggunakan withoutGlobalScopes() agar data master model_signage selalu terbaca,
     * serta withDefault() untuk fallback ke kolom device_model jika model_signage_id belum dipilih.
     */
    public function modelSignage()
    {
        return $this->belongsTo(ModelSignage::class, 'model_signage_id')
            ->withoutGlobalScopes()
            ->withDefault(function ($model, $signage) {
                if (!empty($signage->device_model)) {
                    $model->nama_model_tv = $signage->device_model;
                }
            });
    }

    /**
     * Relasi ke Distribusi Jadwal.
     */
    public function distribusiJadwal()
    {
        return $this->hasMany(DistribusiJadwal::class, 'signage_id');
    }

    public function distribusi()
    {
        return $this->hasMany(DistribusiJadwal::class, 'signage_id');
    }

    /**
     * Klaim perangkat yang sudah terdaftar tanpa client_id, atau buat/perbarui perangkat.
     */
    public static function claimOrCreate(array $attributes, ?int $clientId = null): self
    {
        $clientId = $clientId ?: Tenant::id();

        $deviceId = isset($attributes['device_id'])
            ? strtoupper(trim((string) $attributes['device_id']))
            : '';

        $attributes['device_id'] = $deviceId;

        if ($deviceId !== '') {
            $existing = static::withoutGlobalScopes()
                ->whereRaw('UPPER(device_id) = ?', [$deviceId])
                ->first();

            if ($existing) {
                if (empty($existing->client_id) && $clientId) {
                    $existing->client_id = $clientId;
                }

                $existing->fill($attributes);

                if (empty($existing->device_token)) {
                    $existing->device_token = Str::random(60);
                }

                $existing->save();

                return $existing;
            }
        }

        if ($clientId) {
            $attributes['client_id'] = $clientId;
        }

        if (empty($attributes['device_token'])) {
            $attributes['device_token'] = Str::random(60);
        }

        return static::create($attributes);
    }

    public function isOnline(): bool
    {
        if ($this->last_ping) {
            return $this->last_ping->gt(now()->subMinutes(5));
        }

        return strtolower((string) $this->koneksi_status) === 'online';
    }
}