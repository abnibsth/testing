<?php

namespace App\Services;

use App\Models\DigitalSignage;
use App\Models\DistribusiJadwal;
use App\Models\JadwalTayang;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Snapshot jadwal per distribusi.
 *
 * - build()   : susun isi jadwal dari data LIVE (jadwal tayang + file iklan + pengaturan distribusi).
 * - store()   : "distribusikan" = simpan hasil build() sebagai snapshot. Hanya ini yang dibaca TV.
 * - read()    : baca snapshot yang tersimpan.
 * - isStale() : true bila data live sudah berbeda dari snapshot (artinya perlu distribusi ulang).
 */
class JadwalSnapshot
{
    public static function encode(array $payload): string
    {
        return json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /** Susun isi jadwal dari data live. Null bila jadwal tayangnya sudah tidak ada. */
    public static function build(DistribusiJadwal $dist): ?array
    {
        $dist->load('jadwalTayang.fileIklan');

        $jadwal = $dist->jadwalTayang;
        if (!$jadwal) {
            // antisipasi nama kolom ganda (jadwal_tayang_id vs jadwal_id), sama seperti kode lama
            $idAlt = $dist->jadwal_tayang_id ?? $dist->jadwal_id ?? null;
            if ($idAlt) {
                $jadwal = JadwalTayang::with('fileIklan')->find($idAlt);
            }
        }
        if (!$jadwal) {
            return null;
        }

        $videos = [];
        foreach (($jadwal->fileIklan ?? collect()) as $index => $file) {
            $urutan = $index + 1;
            if ($file->pivot && isset($file->pivot->urutan)) {
                $urutan = $file->pivot->urutan;
            }
            $videos[] = [
                'id'           => $file->id,
                'nama_iklan'   => $file->nama_iklan,
                'durasi_detik' => $file->durasi_detik,
                'ukuran_bytes' => $file->ukuran_bytes,
                'urutan'       => $urutan,
                'video_url'    => $file->file_path ? config('app.url') . '/media/' . $file->file_path : null,
                'has_video'    => $file->has_video,
            ];
        }
        usort($videos, fn ($a, $b) => $a['urutan'] <=> $b['urutan']);

        return [
            'distribusi_id'   => $dist->id,
            'nama_jadwal'     => $jadwal->nama_jadwal,
            'waktu_mulai'     => $dist->waktu_mulai ? substr($dist->waktu_mulai, 0, 5) : '00:00',
            'waktu_selesai'   => $dist->waktu_selesai ? substr($dist->waktu_selesai, 0, 5) : '23:59',
            'tanggal_mulai'   => $dist->tanggal_mulai ? Carbon::parse($dist->tanggal_mulai)->format('Y-m-d') : null,
            'tanggal_selesai' => $dist->tanggal_selesai ? Carbon::parse($dist->tanggal_selesai)->format('Y-m-d') : null,
            'frekuensi_hari'  => $dist->frekuensi_hari ?: 'Setiap Hari',
            'videos'          => $videos,
        ];
    }

    /**
     * Distribusikan: simpan data live saat ini sebagai snapshot.
     * Mengembalikan true bila isinya berubah dari snapshot sebelumnya.
     * $tandaiTv=false dipakai untuk baseline awal (tidak menandai TV "Belum Terbaru").
     * Memakai query builder langsung agar tidak memicu event model (tidak rekursif) dan updated_at tidak berubah.
     */
    public static function store(DistribusiJadwal $dist, bool $tandaiTv = true): bool
    {
        $payload = self::build($dist);
        if ($payload === null) {
            return false;
        }

        $json    = self::encode($payload);
        $hash    = md5($json);
        $changed = $dist->snapshot_hash !== $hash;

        DB::table($dist->getTable())
            ->where($dist->getKeyName(), $dist->getKey())
            ->update(['snapshot' => $json, 'snapshot_hash' => $hash, 'snapshot_at' => now()]);

        // samakan atribut di memori dengan DB tanpa menandainya "belum disimpan"
        $dist->setRawAttributes(array_merge($dist->getAttributes(), [
            'snapshot'      => $json,
            'snapshot_hash' => $hash,
        ]), true);

        if ($changed && $tandaiTv && $dist->signage_id) {
            // tandai TV: ada konten baru yang belum diunduh (direset saat TV melapor via /status)
            DigitalSignage::whereKey($dist->signage_id)->update(['needs_update' => 1, 'status_konten' => 'Belum Terbaru']);
        }

        return $changed;
    }

    public static function read(DistribusiJadwal $dist): ?array
    {
        if (!$dist->snapshot) {
            return null;
        }
        $data = json_decode($dist->snapshot, true);
        return is_array($data) ? $data : null;
    }

    /** Apakah data live berbeda dari yang sudah didistribusikan? (untuk badge "perlu distribusi ulang") */
    public static function isStale(DistribusiJadwal $dist): bool
    {
        $payload = self::build($dist);
        if ($payload === null) {
            return false;
        }
        return md5(self::encode($payload)) !== $dist->snapshot_hash;
    }

    /** Distribusi ulang semua jadwal milik satu signage. Mengembalikan jumlah yang berubah. */
    public static function distributeSignage(int $signageId): int
    {
        $n = 0;
        foreach (DistribusiJadwal::where('signage_id', $signageId)->get() as $dist) {
            if (self::store($dist)) {
                $n++;
            }
        }
        return $n;
    }

    /** Distribusi ulang banyak sekaligus: semua, atau hanya yang memakai jadwal tayang tertentu. Mengembalikan jumlah yang berubah. */
    public static function distributeAll(?int $jadwalTayangId = null): int
    {
        $q = DistribusiJadwal::query();
        if ($jadwalTayangId) {
            $q->where(function ($w) use ($jadwalTayangId) {
                $w->where('jadwal_tayang_id', $jadwalTayangId);
            });
        }

        $n = 0;
        foreach ($q->get() as $dist) {
            if (self::store($dist)) {
                $n++;
            }
        }
        return $n;
    }
}
