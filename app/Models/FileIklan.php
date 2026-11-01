<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FileIklan extends Model
{
    use \App\Models\Concerns\BelongsToClient;

    protected $table = 'file_iklan';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    // ✨ FIX 1: Daftar kolom disamakan dengan struktur Database & Controller terbaru
    protected $fillable = [
        'client_id',
        'pelanggan',
        'tipe',
        'nama_iklan',
        'durasi_detik',
        'catatan',
        'file_path',
        'file_name',
        'ukuran_bytes',
        'has_video',
        'aktif'
    ];

    // ✨ FIX 2: Penyesuaian tipe data agar tidak eror
    protected $casts = [
        'aktif'        => 'boolean',
        'has_video'    => 'boolean',
        'ukuran_bytes' => 'integer',
        'durasi_detik' => 'integer',
    ];

    // Relasi ke Jadwal Tayang (melalui pivot table)
    public function jadwalTayang()
    {
        return $this->belongsToMany(JadwalTayang::class, 'jadwal_file_iklan', 'file_iklan_id', 'jadwal_tayang_id')
                    ->withPivot('urutan', 'durasi');
    }

    // Scope: Hanya ambil file aktif
    public function scopeAktif($query)
    {
        return $query->where('aktif', 1);
    }

    // Scope: Filter berdasarkan tipe
    public function scopeTipe($query, $tipe)
    {
        return $query->where('tipe', $tipe);
    }

    // Attribute: Get URL lengkap
    public function getUrlLengkapAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    // ✨ FIX 3: Menyesuaikan nama kolom untuk perhitungan ukuran file
    public function getUkuranFormattedAttribute(): string
    {
        $bytes = $this->ukuran_bytes; // Diubah dari $this->ukuran
        if (!$bytes) return '0 B';
        if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024) return number_format($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }
}