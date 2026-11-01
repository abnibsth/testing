<?php

namespace App\Http\Controllers;

use App\Models\DigitalSignage;
use App\Models\Toko;
use App\Models\ModelSignage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // ✅ Pastikan ini terimpor agar query lancar
use Illuminate\Support\Facades\Log;

class DigitalSignageDeviceController extends Controller
{
    /**
     * Tampilkan semua device signage
     */
    public function index(Request $request)
    {
        $query = DigitalSignage::with(['toko', 'modelSignage']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_signage', 'like', "%$search%")
                  ->orWhere('device_id', 'like', "%$search%")
                  ->orWhere('status', 'like', "%$search%");
            });
        }

        $devices = $query->orderBy('last_ping', 'desc')->paginate(15)->withQueryString();

        return view('digital-signage.index', compact('devices'));
    }

    /**
     * 🎯 FIX UTAMA: Menambahkan fungsi create() yang dipanggil oleh form Tambah Signage Anda
     */
	public function create()
    {
        // Tarik data model TV
        $modelSignage = \App\Models\ModelSignage::orderBy('nama_model', 'asc')->get();
        
        // Tarik data toko
        $toko = \App\Models\Toko::orderBy('nama_toko', 'asc')->get(); 

        // Return ke view signage.create (View yang sama)
        return view('signage.create', compact('modelSignage', 'toko'));
    }

    /**
     * API: Android device registrasi/heartbeat
     */
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'device_id'      => 'required|string|max:255',
                'nama_signage'   => 'nullable|string|max:255',
                'token'          => 'nullable|string|max:255',
            ]);

            $device = DigitalSignage::updateOrCreate(
                ['device_id' => $validated['device_id']],
                [
                    'nama_signage'   => $validated['nama_signage'] ?? 'Signage ' . substr($validated['device_id'], 0, 8),
                    'token'          => $validated['token'] ?? null,
                    'last_ping'      => now(),
                    'status'         => 'Aktif',
                    'koneksi_status' => 'Online', 
                ]
            );

            Log::info("Device heartbeat received & marked Online: {$validated['device_id']}");

            return response()->json([
                'success' => true,
                'message' => 'Device registered and marked Online successfully',
                'data' => [
                    'device_id' => $device->device_id,
                    'nama_signage' => $device->nama_signage,
                    'koneksi_status' => $device->koneksi_status,
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Device registration error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Edit device
     */
 
    public function edit(DigitalSignage $device)
    {
        $device->load(['toko', 'modelSignage']);
        
        $toko = \App\Models\Toko::orderBy('nama_toko', 'asc')->get();
        $modelSignage = \App\Models\ModelSignage::orderBy('nama_model', 'asc')->get();
        
        // Sesuaikan target view dengan file blade edit Anda (misal 'digital-signage.edit' atau 'signage.edit')
        return view('digital-signage.edit', compact('device', 'toko', 'modelSignage'));
    }

    /**
     * Update device
     */
    public function update(Request $request, DigitalSignage $device)
    {
        $validated = $request->validate([
            'nama_signage'      => 'required|string|max:255',
            'toko_id'           => ['nullable', \App\Support\Tenant::exists('toko')],
            'model_signage_id'  => 'nullable|exists:model_signage,id',
            'status'            => 'required|in:Aktif,Nonaktif,Maintenance',
        ]);

        $device->update($validated);

        return redirect()->route('devices.index')
                        ->with('success', 'Device berhasil diupdate');
    }

    /**
     * Delete device
     */
    public function destroy(DigitalSignage $device)
    {
        $device->delete();
        return redirect()->route('devices.index')
                        ->with('success', 'Device berhasil dihapus');
    }
}