<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JadwalTayang extends Model
{
    use \App\Models\Concerns\BelongsToClient;

    protected $table = 'jadwal_tayang';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'client_id',
        'nama_jadwal',
        'keterangan',
        'aktif',
        'prioritas',
        'user_id',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'prioritas' => 'integer',
    ];

    /**
     * Relasi ke File Iklan (melalui pivot table)
     * ⚠️ PENTING: orderBy pakai 'urutan' (bukan 'pivot_urutan')
     */
    public function fileIklan(): BelongsToMany
    {
        return $this->belongsToMany(
            FileIklan::class, 
            'jadwal_file_iklan', 
            'jadwal_tayang_id', 
            'file_iklan_id'
        )
        ->withPivot('id', 'urutan', 'durasi')
        ->orderBy('urutan', 'asc');  // ✅ Nama kolom asli di database
    }

    /**
     * Relasi ke Distribusi Jadwal
     */
    public function distribusiJadwal(): HasMany
    {
        return $this->hasMany(DistribusiJadwal::class, 'jadwal_tayang_id');
    }

    /**
     * Scope: Hanya ambil jadwal aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('aktif', 1);
    }

    /**
     * Scope: Urutkan berdasarkan prioritas
     */
    public function scopeByPrioritas($query)
    {
        return $query->orderBy('prioritas', 'desc');
    }
}