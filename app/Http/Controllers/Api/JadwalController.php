<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DistribusiJadwal;
use App\Models\JadwalTayang;
use App\Services\JadwalSnapshot;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class JadwalController extends Controller
{
    /**
     * Jadwal untuk TV. Yang dikirim adalah SNAPSHOT hasil distribusi, bukan data jadwal live,
     * sehingga edit jadwal baru sampai ke TV setelah didistribusikan ulang.
     */
    public function getJadwal(Request $request, string $deviceId)
    {
        $device    = $request->get('_device');
        $today     = now()->toDateString();
        $dayOfWeek = now()->dayOfWeek;

        // 'aktif' tetap dibaca live: menonaktifkan/menghapus distribusi langsung menghentikan tayangan.
        $distribusiList = DistribusiJadwal::where('signage_id', $device->id)
            ->where('aktif', true)
            ->get();

        // Jadwal tayang yang sudah dihapus langsung berhenti tayang (tidak menunggu distribusi ulang).
        $jadwalAda = JadwalTayang::whereIn('id', $distribusiList->pluck('jadwal_tayang_id')->filter()->unique())
            ->pluck('id')->all();

        $jadwalResponse = [];
        foreach ($distribusiList as $dist) {
            if (!in_array($dist->jadwal_tayang_id, $jadwalAda)) {
                continue;
            }

            $snap = JadwalSnapshot::read($dist);

            if ($snap === null) {
                // Baris lama / baru yang belum punya snapshot: bekukan kondisi saat ini sebagai baseline.
                JadwalSnapshot::store($dist, false);
                $snap = JadwalSnapshot::read($dist);
            }
            if ($snap === null) {
                continue;   // jadwal tayangnya sudah tidak ada
            }

            // Filter tanggal & frekuensi memakai nilai di SNAPSHOT (bukan kolom live).
            $tglMulai   = $snap['tanggal_mulai']   ?? null;
            $tglSelesai = $snap['tanggal_selesai'] ?? null;
            if (!$tglMulai || !$tglSelesai || $today < $tglMulai || $today > $tglSelesai) {
                continue;
            }

            $hariOk = match ($snap['frekuensi_hari'] ?? 'Setiap Hari') {
                'Setiap Hari'  => true,
                'Senin-Jumat'  => $dayOfWeek >= 1 && $dayOfWeek <= 5,
                'Sabtu-Minggu' => $dayOfWeek === 0 || $dayOfWeek === 6,
                default        => true,
            };
            if (!$hariOk) {
                continue;
            }

            $jadwalResponse[] = Arr::except($snap, ['frekuensi_hari']);
        }

        if (empty($jadwalResponse)) {
            return response()->json(['success' => true, 'jadwal' => [], 'versi' => null]);
        }

        // Versi konten: hash seluruh isi jadwal (perubahan jam/tanggal juga mengubah versi).
        $versi = md5(json_encode($jadwalResponse));

        return response()->json([
            'success'     => true,
            'device_id'   => $deviceId,
            'server_time' => now()->toIso8601String(),
            'versi'       => $versi,
            'orientasi'   => $device->orientasi,
            'jadwal'      => $jadwalResponse,
        ]);
    }
}
