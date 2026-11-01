<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Services\JadwalSnapshot;

class DistribusiJadwal extends Model
{
    use \App\Models\Concerns\BelongsToClient;

    // ✅ Nama tabel
    protected $table = 'distribusi_jadwal';

    // ✅ Primary key
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    // ✅ Timestamps
    public $timestamps = true;

    // ✅ Kolom yang boleh diisi mass-assignment
    protected $fillable = [
        'client_id',
        'signage_id',           // ✅ Foreign key ke digital_signage (bukan device_id)
        'jadwal_tayang_id',     // ✅ Foreign key ke jadwal_tayang (bukan jadwal_id)
        'tanggal_mulai',
        'tanggal_selesai',
        'waktu_mulai',
        'waktu_selesai',
        'frekuensi_hari',
        'frekuensi_tanggal',
        'frekuensi_bulan',
        'aktif',
    ];

    // ✅ Casting tipe data
    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'aktif'           => 'boolean',
    ];

    // Isi snapshot bisa besar; jangan ikut tampil saat model di-JSON-kan ke dashboard.
    protected $hidden = ['snapshot'];

    /**
     * Relasi ke DigitalSignage
     * ⚠️ PENTING: Gunakan 'signage_id' sebagai foreign key (sesuai database)
     */
    public function digitalSignage(): BelongsTo
    {
        return $this->belongsTo(DigitalSignage::class, 'signage_id');
    }

    /**
     * Relasi ke JadwalTayang
     * ⚠️ PENTING: Gunakan 'jadwal_tayang_id' sebagai foreign key (sesuai database)
     */
    public function jadwalTayang(): BelongsTo
    {
        return $this->belongsTo(JadwalTayang::class, 'jadwal_tayang_id');
    }

    /**
     * Attribute helper: Format waktu tayang
     */
    public function getWaktuTayangAttribute(): string
    {
        $mulai = $this->waktu_mulai ?? '00:00';
        $selesai = $this->waktu_selesai ?? '23:59';
        return substr($mulai, 0, 5) . ' - ' . substr($selesai, 0, 5);
    }

    /**
     * Scope: Hanya ambil distribusi yang aktif & dalam periode berlaku
     */
    public function scopeAktifDanBerlaku($query)
    {
        return $query->where('aktif', 1)
                    ->where('tanggal_mulai', '<=', now())
                    ->where('tanggal_selesai', '>=', now());
    }

    /**
     * Scope: Filter by signage_id
     */
    public function scopeBySignage($query, $signageId)
    {
        return $query->where('signage_id', $signageId);
    }

    /**
     * Menyimpan form Distribusi = mendistribusikan: bekukan isi jadwal agar dibaca TV.
     * (Edit di Jadwal Tayang saja tidak mengubah apa yang diterima TV.)
     */
    protected static function booted()
    {
        static::saved(function ($dist) {
            JadwalSnapshot::store($dist);
        });
    }

    /**
     * Untuk badge "perlu distribusi ulang" di dashboard:
     * true bila data live berbeda dari yang sudah didistribusikan.
     */
    public function perluDistribusiUlang(): bool
    {
        return JadwalSnapshot::isStale($this);
    }
}
