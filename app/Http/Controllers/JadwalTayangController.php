<?php

namespace App\Http\Controllers;

use App\Models\JadwalTayang;
use App\Models\FileIklan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // ✨ Tambahkan ini untuk query update cepat

class JadwalTayangController extends Controller
{
    public function index(Request $request)
    {
        $query = JadwalTayang::with('fileIklan');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_jadwal', 'like', "%$search%")
                  ->orWhere('keterangan', 'like', "%$search%");
        }

        $jadwal = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('jadwal.index', compact('jadwal'));
    }

    public function create()
    {
        $fileIklan = FileIklan::where('aktif', true)->orderBy('nama_iklan')->get();
        return view('jadwal.create', compact('fileIklan'));
    }

    public function store(Request $request)
    {
        Log::info('=== JADWAL STORE ATTEMPT ===', [
            'input' => $request->only(['nama_jadwal', 'keterangan', 'tanggal_mulai', 'file_iklan_ids']),
        ]);

        $validated = $request->validate([
            'nama_jadwal'    => 'required|string|max:255',
            'keterangan'     => 'nullable|string',
            'tanggal_mulai'  => 'nullable|date',
            'tanggal_selesai'=> 'nullable|date|after_or_equal:tanggal_mulai',
            'jam_mulai'      => 'nullable|date_format:H:i',
            'jam_selesai'    => 'nullable|date_format:H:i',
            'hari'           => 'nullable|string|max:50',
            'file_iklan_ids' => 'required|array|min:1',
            'file_iklan_ids.*' => [\App\Support\Tenant::exists('file_iklan')],
        ], [
            'file_iklan_ids.required' => 'Minimal 1 video harus dipilih.',
            'file_iklan_ids.min' => 'Minimal 1 video harus dipilih.',
        ]);

        try {
            $jadwal = JadwalTayang::create([
                'nama_jadwal'   => $validated['nama_jadwal'],
                'keterangan'    => $validated['keterangan'] ?? null,
                'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
                'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
                'jam_mulai'     => $validated['jam_mulai'] ?? null,
                'jam_selesai'   => $validated['jam_selesai'] ?? null,
                'hari'          => $validated['hari'] ?? null,
            ]);

            $syncData = [];
            foreach ($validated['file_iklan_ids'] as $index => $fileId) {
                $syncData[$fileId] = ['urutan' => $index + 1];
            }
            $jadwal->fileIklan()->sync($syncData);

            Log::info('Jadwal created', ['id' => $jadwal->id, 'nama' => $jadwal->nama_jadwal]);

            return redirect()->route('jadwal.index')
                             ->with('success', 'Jadwal tayang berhasil ditambahkan.');

        } catch (\Exception $e) {
            Log::error('Jadwal store error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            
            return back()
                ->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function edit(JadwalTayang $jadwal)
    {
        $fileIklan = FileIklan::where('aktif', true)->orderBy('nama_iklan')->get();
        $selectedFileIds = $jadwal->fileIklan()->pluck('file_iklan.id')->toArray();

        return view('jadwal.edit', compact('jadwal', 'fileIklan', 'selectedFileIds'));
    }

    public function update(Request $request, JadwalTayang $jadwal)
    {
        Log::info('=== JADWAL UPDATE ATTEMPT ===', ['id' => $jadwal->id]);

        $validated = $request->validate([
            'nama_jadwal'    => 'required|string|max:255',
            'keterangan'     => 'nullable|string',
            'tanggal_mulai'  => 'nullable|date',
            'tanggal_selesai'=> 'nullable|date|after_or_equal:tanggal_mulai',
            'jam_mulai'      => 'nullable|date_format:H:i',
            'jam_selesai'    => 'nullable|date_format:H:i',
            'hari'           => 'nullable|string|max:50',
            'file_iklan_ids' => 'required|array|min:1',
            'file_iklan_ids.*' => [\App\Support\Tenant::exists('file_iklan')],
        ]);

        try {
            $jadwal->update([
                'nama_jadwal'   => $validated['nama_jadwal'],
                'keterangan'    => $validated['keterangan'] ?? null,
                'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
                'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
                'jam_mulai'     => $validated['jam_mulai'] ?? null,
                'jam_selesai'   => $validated['jam_selesai'] ?? null,
                'hari'          => $validated['hari'] ?? null,
            ]);

            $syncData = [];
            foreach ($validated['file_iklan_ids'] as $index => $fileId) {
                $syncData[$fileId] = ['urutan' => $index + 1];
            }
            $jadwal->fileIklan()->sync($syncData);
            
            // ✨ FIX 1: Paksa perbarui timestamp updated_at agar hash versi di Android ikut berubah
            $jadwal->touch();

            // Edit jadwal TIDAK langsung berlaku di TV: TV membaca snapshot hasil distribusi.
            // Karena itu TV tidak ditandai "Belum Terbaru" di sini; perubahan baru dikirim
            // setelah distribusi ulang (menu Distribusi > Edit > Distribusi Ulang ke TV).
            $dipakaiDistribusi = DB::table('distribusi_jadwal')
                ->where('jadwal_tayang_id', $jadwal->id)
                ->exists();

            Log::info('Jadwal updated (menunggu distribusi ulang)', ['id' => $jadwal->id]);

            $pesan = 'Jadwal tayang berhasil diperbarui.';
            if ($dipakaiDistribusi) {
                $pesan .= ' Perubahan BELUM dikirim ke TV: lakukan distribusi ulang lewat menu Distribusi (Edit > Distribusi Ulang ke TV).';
            }

            return redirect()->route('jadwal.index')
                             ->with('success', $pesan);

        } catch (\Exception $e) {
            Log::error('Jadwal update error: ' . $e->getMessage());
            
            return back()
                ->withErrors(['error' => 'Gagal memperbarui: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(JadwalTayang $jadwal)
    {
        try {
            // Beritahu TV terlebih dahulu sebelum jadwalnya dihapus resmi
            $signageIds = DB::table('distribusi_jadwal')
                ->where('jadwal_tayang_id', $jadwal->id)
                ->pluck('signage_id');

            if ($signageIds->isNotEmpty()) {
                DB::table('digital_signage')->whereIn('id', $signageIds)->update(['needs_update' => 1]);
            }

            $jadwal->fileIklan()->detach(); 
            $jadwal->delete();
            
            Log::info('Jadwal deleted', ['id' => $jadwal->id]);

            return redirect()->route('jadwal.index')
                             ->with('success', 'Jadwal tayang berhasil dihapus.');
                             
        } catch (\Exception $e) {
            Log::error('Jadwal delete error: ' . $e->getMessage());
            
            return back()->withErrors(['error' => 'Gagal menghapus: ' . $e->getMessage()]);
        }
    }
}