<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DigitalSignage;
use App\Models\JadwalTayang;
use App\Models\DistribusiJadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DeviceController extends Controller
{
    /**
     * Register device baru atau update data device yang sudah ada
     * POST /api/device/register
     */
    public function register(Request $request)
    {
        $request->validate([
            'device_id'       => 'required|string|max:100',
            'device_model'    => 'nullable|string|max:100',
            'android_version' => 'nullable|string|max:50',
            'app_version'     => 'nullable|string|max:20',
            'resolusi_width'  => 'nullable|integer',
            'resolusi_height' => 'nullable|integer',
        ]);

        $device = DigitalSignage::where('device_id', $request->device_id)->first();

        if (!$device) {
            // MODE INPUT MANUAL: perangkat TIDAK dibuat otomatis lagi.
            // Admin harus menambahkan Device ID ini lewat dashboard (Digital Signage > Tambah).
            return response()->json([
                'success'    => false,
                'registered' => false,
                'status'     => 'unregistered',
                'message'    => 'Perangkat belum terdaftar. Berikan Device ID ini ke admin untuk ditambahkan di dashboard.',
                'device_id'  => $request->device_id,
            ], 200);
        }

        // Device sudah ada -- pastikan token selalu ada
        if (!$device->device_token) {
            $device->device_token = Str::random(64);
        }

        $device->device_model    = $request->device_model    ?? $device->device_model;
        $device->android_version = $request->android_version ?? $device->android_version;
        $device->app_version     = $request->app_version     ?? $device->app_version;
        $device->koneksi_status  = 'online';
        
        // ✅ FIX 2: Mengubah '=> now()' menjadi '= now()'
        $device->last_ping       = now(); 
        $device->save();

        // Refresh dari database untuk pastikan token tersimpan
        $device->refresh();

        return response()->json([
            'success'      => $device->status === 'Aktif',
            'registered'   => true,
            'message'      => $device->status === 'Aktif' ? 'Perangkat aktif.' : 'Perangkat belum diaktifkan.',
            // Token hanya diberikan jika perangkat tidak dinonaktifkan admin
            'device_token' => $device->status === 'Nonaktif' ? null : $device->device_token,
            'device_id'    => $device->device_id,
            'status'       => 'status' === 'Aktif' ? 'Aktif' : $device->status,
            'nama_signage' => $device->nama_signage,
        ]);
    }

    /**
     * Get device info
     * GET /api/device/{device_id}
     */
    public function info(Request $request, string $deviceId)
    {
        $device = DigitalSignage::where('device_id', $deviceId)
                                ->with(['toko', 'modelSignage'])
                                ->firstOrFail();

        return response()->json([
            'success' => true,
            'device'  => [
                'id'             => $device->id,
                'device_id'      => $device->device_id,
                'nama_signage'   => $device->nama_signage,
                'status'         => $device->status,
                'status_konten'  => $device->status_konten,
                'resolusi'       => $device->resolusi,
                'orientasi'      => $device->orientasi,
                'toko'           => $device->toko?->nama_toko,
                'versi_konten'   => $device->versi_konten,
                'unduh_terakhir' => $device->unduh_terakhir,
            ],
        ]);
    }

    /**
     * Device ping untuk update last_seen
     * POST /api/device/{device_id}/ping
     */
    public function ping(Request $request, string $deviceId)
    {
        $device = $request->get('_device') ?? DigitalSignage::where('device_id', $deviceId)->first();
        
        if (!$device) {
            return response()->json(['success' => false, 'message' => 'Device not found'], 404);
        }

        $device->update([
            'koneksi_status' => 'online',
            'last_ping'      => now(),
        ]);

        return response()->json([
            'success'     => true,
            'server_time' => now()->toIso8601String(),
        ]);
    }

    /**
     * Update status konten dari device
     * POST /api/device/{device_id}/status
     */
    public function updateStatus(Request $request, string $deviceId)
    {
        $request->validate([
            'versi_konten' => 'required|string',
            'status'       => 'nullable|string',
        ]);

        $device = $request->get('_device') ?? DigitalSignage::where('device_id', $deviceId)->first();
        
        if (!$device) {
            return response()->json(['success' => false, 'message' => 'Device not found'], 404);
        }

        $device->update([
            'status_konten'    => 'Sudah Terbaru',
            'versi_konten'     => $request->versi_konten,
            'unduh_terakhir'   => now(),
            'capture_terakhir' => now(),
            'koneksi_status'   => 'online',
            'last_ping'        => now(),
            'needs_update'     => 0, 
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status konten diperbarui.'
        ]);
    }

    /**
     * Check apakah ada update konten untuk device ini
     * GET /api/device/{device_id}/check-update
     */
    public function checkUpdate(Request $request, string $deviceId)
    {
        $device = $request->get('_device') ?? DigitalSignage::where('device_id', $deviceId)->first();
        
        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found'
            ], 404);
        }

        $currentVersion = $this->generateContentVersion($device);
        
        $hasUpdate = $device->needs_update == 1 || 
                     $device->versi_konten !== $currentVersion;

        return response()->json([
            'success'        => true,
            'has_update'     => $hasUpdate,
            'current_version'=> $device->versi_konten,
            'latest_version' => $currentVersion,
            'last_sync'      => $device->unduh_terakhir,
            'status_konten'  => $device->status_konten,
            'message'        => $hasUpdate ? 'Ada konten baru tersedia' : 'Konten sudah terbaru',
        ]);
    }

    /**
     * Force sync device dengan server
     * POST /api/device/{device_id}/sync
     */
    public function sync(Request $request, string $deviceId)
    {
        $device = $request->get('_device') ?? DigitalSignage::where('device_id', $deviceId)->first();
        
        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found'
            ], 404);
        }

        $newVersion = $this->generateContentVersion($device);
        
        $device->update([
            'versi_konten'   => $newVersion,
            'unduh_terakhir' => now(),
            'needs_update'   => 0,
            'status_konten'  => 'Sudah Terbaru',
        ]);

        return response()->json([
            'success' => true,
            'version' => $newVersion,
            'message' => 'Sync berhasil dilakukan'
        ]);
    }

    /**
     * Get jadwal aktif untuk device ini
     * GET /api/device/{device_id}/jadwal
     */
    public function getJadwal(Request $request, string $deviceId)
    {
        $device = DigitalSignage::where('device_id', $deviceId)->first();
        
        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found'
            ], 404);
        }

        $jadwalIds = DistribusiJadwal::where('signage_id', $device->id)
            ->where('aktif', 1)
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now())
            ->pluck('jadwal_tayang_id');

        if ($jadwalIds->isEmpty()) {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }

        $jadwalList = JadwalTayang::whereIn('id', $jadwalIds)
            ->with(['fileIklan'])
            ->get()
            ->map(function($jadwal) {
                return [
                    'id'          => $jadwal->id,
                    'nama_jadwal' => $jadwal->nama_jadwal,
                    'file_iklan'  => $jadwal->fileIklan->map(function($file) {
                        return [
                            'id'        => $file->id,
                            'url'       => asset('storage/' . $file->file_path),
                            'nama_file' => $file->nama_file,
                            'tipe'      => $file->tipe ?? 'video',
                        ];
                    })->values(),
                ];
            })->values();

        return response()->json([
            'success' => true,
            'data' => $jadwalList
        ]);
    }

    /**
     * Generate hash version dari konten yang terhubung dengan device
     */
    private function generateContentVersion($device)
    {
        $distribusiIds = DistribusiJadwal::where('signage_id', $device->id)
            ->where('aktif', 1)
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now())
            ->pluck('jadwal_tayang_id')
            ->toArray();

        if (empty($distribusiIds)) {
            return md5('empty_' . $device->id);
        }

        $contentData = JadwalTayang::whereIn('id', $distribusiIds)
            ->with(['fileIklan'])
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function($jadwal) {
                return [
                    'jadwal_id'        => $jadwal->id,
                    'updated_at'       => $jadwal->updated_at?->timestamp,
                    'file_iklan_count' => $jadwal->fileIklan->count(),
                    'file_iklan'       => $jadwal->fileIklan->map(function($file) {
                        return [
                            'id'         => $file->id,
                            'updated_at' => $file->updated_at?->timestamp,
                            'nama_file'  => $file->nama_file ?? '',
                            'file_path'  => $file->file_path ?? '',
                        ];
                    }),
                ];
            })
            ->toArray();

        return md5(json_encode($contentData));
    }
}