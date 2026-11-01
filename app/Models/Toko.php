<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Toko extends Model
{
    use \App\Models\Concerns\BelongsToClient;

    // ✅ Nama tabel (jika tidak sesuai konvensi Laravel)
    protected $table = 'toko';

    // ✅ Primary key (default: 'id')
    protected $primaryKey = 'id';

    // ✅ Auto-increment
    public $incrementing = true;

    // ✅ Tipe data primary key
    protected $keyType = 'int';

    // ✅ Timestamps
    public $timestamps = true;

   // ✅ Kolom yang boleh diisi mass-assignment (SUDAH DIPERBARUI)
    protected $fillable = [
        'client_id',
        'nama_toko',
        'kode_toko',     // Tambahkan ini
        'channel',       // Tambahkan ini (PENTING)
        'sim_card',      // Tambahkan ini
        'area',
        'alamat',
        'kota',
        'provinsi',
        'telepon',
        'jam_buka',      // Tambahkan ini
        'jam_tutup',     // Tambahkan ini
        'area_teknisi',  // Tambahkan ini (PENTING)
        'keterangan',
        'aktif',
    ];

    // ✅ Casting tipe data
    protected $casts = [
        'aktif' => 'boolean',
    ];

    /**
     * Relasi: Satu toko memiliki banyak signage
     */
    public function digitalSignages(): HasMany
    {
        return $this->hasMany(DigitalSignage::class, 'toko_id');
    }

    /**
     * Scope: Hanya ambil toko yang aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('aktif', 1);
    }

    /**
     * Helper: Get nama toko dengan area
     */
    public function getNamaLengkapAttribute(): string
    {
        return $this->nama_toko . ($this->area ? " - {$this->area}" : '');
    }
}