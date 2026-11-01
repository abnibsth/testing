<?php

namespace App\Http\Controllers;

use App\Models\FileIklan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FileIklanController extends Controller
{
    // Folder media dibaca dari .env (MEDIA_PATH) agar tiap lingkungan/client memakai foldernya sendiri.
    private function mediaBase(): string
    {
        return rtrim(config('media.path'), '/');
    }

    // Client 1 (data lama) tetap 'iklan'; client lain 'media/{client_id}/iklan'
    private function mediaSub(): string
    {
        $cid = \App\Support\Tenant::id();
        return ($cid && $cid != 1) ? $cid . '/iklan' : 'iklan';
    }

    private function mediaDir(): string
    {
        return $this->mediaBase() . '/' . $this->mediaSub();
    }

    public function index(Request $request)
    {
        $query = FileIklan::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_iklan', 'like', "%$search%")
                  ->orWhere('pelanggan', 'like', "%$search%")
                  ->orWhere('tipe', 'like', "%$search%");
            });
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        $fileIklan = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('file-iklan.index', compact('fileIklan'));
    }

    public function create()
    {
        return view('file-iklan.create');
    }

    public function store(Request $request)
    {
        Log::info('=== UPLOAD ATTEMPT ===', [
            'has_file' => $request->hasFile('file_video'),
        ]);

        $validated = $request->validate([
            'pelanggan'     => 'required|string|max:255',
            'tipe'          => 'required|in:CONTENT,BUMPER,PROMO',
            'nama_iklan'    => 'required|string|max:255',
            'durasi_detik'  => 'nullable|integer|min:1',
            'catatan'       => 'nullable|string',
            'file_video'    => 'required|file|mimes:mp4,avi,mov,mkv|max:122880',
        ], [
            'file_video.required' => 'File video wajib diupload.',
            'file_video.file' => 'Harap upload file video yang valid.',
            'file_video.mimes' => 'Format video harus MP4, AVI, MOV, atau MKV.',
            'file_video.max' => 'Ukuran video tidak boleh lebih dari 120 MB.',
        ]);

        try {
            $data = [
                'pelanggan'    => $validated['pelanggan'],
                'tipe'         => $validated['tipe'],
                'nama_iklan'   => $validated['nama_iklan'],
                'durasi_detik' => $validated['durasi_detik'] ?? null,
                'catatan'      => $validated['catatan'] ?? null,
                'has_video'    => false,
                'aktif'        => 1,
            ];

            if ($request->hasFile('file_video')) {
                $file = $request->file('file_video');
                
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $fileSize = $_FILES['file_video']['size'];
                
                $fileName = time() . '_' . pathinfo($originalName, PATHINFO_FILENAME) . '.' . $extension;
                
                // ✅ Gunakan path absolut ke public_html (bukan public_path() Laravel)
                $destinationPath = $this->mediaDir();
                
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0775, true);
                }
                
                $tmpPath = $file->getRealPath();
                $fullPath = $destinationPath . '/' . $fileName;
                
                if (!move_uploaded_file($tmpPath, $fullPath)) {
                    throw new \Exception('Gagal memindahkan file upload.');
                }
                
                // Path untuk database: relatif ke public_html/media/
                $path = $this->mediaSub() . '/' . $fileName;

                Log::info('File uploaded successfully', [
                    'path' => $path, 
                    'size' => $fileSize,
                    'full_path' => $fullPath
                ]);

                $data['file_path']    = $path;
                $data['file_name']    = $fileName;
                $data['ukuran_bytes'] = $fileSize;
                $data['has_video']    = true;
            } else {
                throw new \Exception('File video tidak terdeteksi.');
            }

            $fileIklan = FileIklan::create($data);
            
            Log::info('Record created', ['id' => $fileIklan->id]);

            return redirect()->route('file-iklan.index')
                             ->with('success', 'File iklan berhasil ditambahkan.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            throw $e;
            
        } catch (\Exception $e) {
            Log::error('Upload error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            
            return back()
                ->withErrors(['file_video' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function edit(FileIklan $fileIklan)
    {
        return view('file-iklan.edit', compact('fileIklan'));
    }

    public function update(Request $request, FileIklan $fileIklan)
    {
        Log::info('=== UPDATE ATTEMPT ===', ['id' => $fileIklan->id]);

        $validated = $request->validate([
            'pelanggan'    => 'required|string|max:255',
            'tipe'         => 'required|in:CONTENT,BUMPER,PROMO',
            'nama_iklan'   => 'required|string|max:255',
            'durasi_detik' => 'nullable|integer|min:1',
            'catatan'      => 'nullable|string',
            'file_video'   => 'nullable|file|mimes:mp4,avi,mov,mkv|max:122880',
        ]);

        try {
            $data = [
                'pelanggan'    => $validated['pelanggan'],
                'tipe'         => $validated['tipe'],
                'nama_iklan'   => $validated['nama_iklan'],
                'durasi_detik' => $validated['durasi_detik'] ?? null,
                'catatan'      => $validated['catatan'] ?? null,
            ];

            if ($request->hasFile('file_video')) {
                // ✅ Hapus file lama - Gunakan path absolut
                if ($fileIklan->file_path) {
                    $oldFilePath = $this->mediaBase() . '/' . $fileIklan->file_path;
                    if (file_exists($oldFilePath)) {
                        @unlink($oldFilePath);
                        Log::info('Old file deleted', ['path' => $oldFilePath]);
                    }
                }

                $file = $request->file('file_video');
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $fileSize = $_FILES['file_video']['size'];
                
                $fileName = time() . '_' . pathinfo($originalName, PATHINFO_FILENAME) . '.' . $extension;
                
                $destinationPath = $this->mediaDir();
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0775, true);
                }
                
                $tmpPath = $file->getRealPath();
                $fullPath = $destinationPath . '/' . $fileName;
                
                if (!move_uploaded_file($tmpPath, $fullPath)) {
                    throw new \Exception('Gagal memindahkan file upload.');
                }
                
                $path = $this->mediaSub() . '/' . $fileName;

                $data['file_path']    = $path;
                $data['file_name']    = $fileName;
                $data['ukuran_bytes'] = $fileSize;
                $data['has_video']    = true;
                
                Log::info('New file uploaded', ['path' => $path]);
            }

            $fileIklan->update($data);
            Log::info('Record updated', ['id' => $fileIklan->id]);

            return redirect()->route('file-iklan.index')
                             ->with('success', 'File iklan berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Update error: ' . $e->getMessage());
            
            return back()
                ->withErrors(['file_video' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(FileIklan $fileIklan)
    {
        try {
            // ✅ Hapus file fisik - Gunakan path absolut
            if ($fileIklan->file_path) {
                $filePath = $this->mediaBase() . '/' . $fileIklan->file_path;
                if (file_exists($filePath)) {
                    @unlink($filePath);
                    Log::info('File deleted', ['path' => $filePath]);
                }
            }

            $fileIklan->delete();
            Log::info('Record deleted', ['id' => $fileIklan->id]);

            return redirect()->route('file-iklan.index')
                             ->with('success', 'File iklan berhasil dihapus.');
                             
        } catch (\Exception $e) {
            Log::error('Delete error: ' . $e->getMessage());
            
            return back()->withErrors(['error' => 'Gagal menghapus: ' . $e->getMessage()]);
        }
    }
}