@extends('layouts.app')
@section('title', 'Tambah File Iklan')
@section('page-title', 'Iklan > File Iklan > Tambah File')

@section('content')

{{-- 💡 CSS DIKUNCI MATI AGAR 100% KONSISTEN DENGAN FORM LAIN --}}
<style>
    .vortex-card-form {
        background: var(--bg-card, #ffffff); border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        border: 1px solid var(--border-main, #e9ecef); overflow: hidden; width: 100%; max-width: 900px; margin-top: 10px;
    }
    .vortex-header-form { padding: 24px 24px 16px 24px; border-bottom: 1px solid var(--border-main, #e9ecef); display: flex; align-items: center; }
    .vortex-header-form h5 { margin: 0; font-weight: 800; color: var(--text-main, #2c3e50); font-size: 1.25rem; display: flex; align-items: center; }
    .icon-title-box { width: 42px; height: 42px; background: rgba(54, 185, 204, 0.15); color: #36b9cc; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 16px; }
    .vortex-body-form { padding: 30px 24px; }
    .vortex-label-locked { font-size: 12px; font-weight: 800; color: var(--text-muted, #7f8c8d); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 8px; display: block; }
    .vortex-input-locked { width: 100% !important; display: block !important; padding: 14px 16px; border: 1px solid var(--border-table, #cbd5e1); border-radius: 10px; font-size: 14px; font-weight: 600; background: var(--bg-input, #f8f9fa); color: var(--text-main, #2c3e50); transition: all 0.2s ease; box-sizing: border-box; }
    .vortex-input-locked:focus { outline: none; border-color: #36b9cc; box-shadow: 0 0 0 3px rgba(54, 185, 204, 0.15); background: var(--bg-card, #ffffff); }
    .vortex-error-text { color: #e74c3c; font-size: 12px; font-weight: 700; margin-top: 6px; display: block; }
    
    .vortex-btn-locked { padding: 12px 24px; border-radius: 8px; font-weight: 800; font-size: 13px; text-transform: uppercase; border: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s ease; cursor: pointer; text-decoration: none; }
    .btn-save-v { background: #2c3e50; color: #ffffff; box-shadow: 0 4px 10px rgba(44, 62, 80, 0.2); }
    .btn-save-v:hover { background: #1a252f; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(44, 62, 80, 0.3); color: #ffffff; }
    .btn-cancel-v { background: #b2bec3; color: #ffffff; }
    .btn-cancel-v:hover { background: #95a5a6; transform: translateY(-2px); color: #ffffff; }
    
    .vortex-form-row { display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 24px; width: 100%; }
    .vortex-form-col { flex: 1; min-width: 250px; }
    .upload-info { font-size: 11px; color: #7f8c8d; margin-top: 8px; display: block; font-weight: 600; }
</style>

<div class="vortex-card-form">
    <div class="vortex-header-form">
        <h5>
            <div class="icon-title-box"><i class="fas fa-film" style="font-size: 16px;"></i></div>
            Form Tambah File Iklan Baru
        </h5>
    </div>
    
    <div class="vortex-body-form">
        <form method="POST" action="{{ route('file-iklan.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="vortex-form-row">
                {{-- Pelanggan --}}
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Pelanggan / Brand <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="pelanggan" class="vortex-input-locked @error('pelanggan') is-invalid @enderror" 
                           value="{{ old('pelanggan') }}" placeholder="Masukkan nama pelanggan" required>
                    @error('pelanggan') <span class="vortex-error-text">{{ $message }}</span> @enderror
                </div>

                {{-- Tipe --}}
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Tipe File <span style="color: #e74c3c;">*</span></label>
                    <select name="tipe" class="vortex-input-locked @error('tipe') is-invalid @enderror" required>
                        @foreach(['CONTENT','BUMPER','PROMO'] as $t)
                            <option value="{{ $t }}" {{ old('tipe', 'CONTENT') === $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                    @error('tipe') <span class="vortex-error-text">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Nama Iklan --}}
            <div style="margin-bottom: 24px;">
                <label class="vortex-label-locked">Nama Iklan <span style="color: #e74c3c;">*</span></label>
                <input type="text" name="nama_iklan" class="vortex-input-locked @error('nama_iklan') is-invalid @enderror" 
                       value="{{ old('nama_iklan') }}" placeholder="Contoh: Iklan Promo Lebaran 2026" required>
                @error('nama_iklan') <span class="vortex-error-text">{{ $message }}</span> @enderror
            </div>

            {{-- Upload File --}}
            <div style="margin-bottom: 24px;">
                <label class="vortex-label-locked">Upload File Video <span style="color: #e74c3c;">*</span></label>
                <input type="file" name="file_video" class="vortex-input-locked @error('file_video') is-invalid @enderror" 
                       accept="video/mp4,video/avi,video/quicktime,video/x-matroska" required 
                       style="padding: 10px;">
                <span class="upload-info">
                    <i class="fas fa-info-circle mr-1"></i> Format: MP4, AVI, MOV, MKV. Ukuran Maksimal: 500 MB.
                </span>
                @error('file_video') <span class="vortex-error-text">{{ $message }}</span> @enderror
            </div>

            {{-- Keterangan --}}
            <div style="margin-bottom: 12px;">
                <label class="vortex-label-locked">Keterangan / Catatan Internal</label>
                <textarea name="keterangan" class="vortex-input-locked" rows="3" 
                          placeholder="Tambahkan catatan khusus mengenai file iklan ini (opsional)...">{{ old('keterangan') }}</textarea>
            </div>

            {{-- Tombol Aksi --}}
            <div style="display: flex; gap: 12px; margin-top: 36px; padding-top: 24px; border-top: 1px solid var(--border-main, #e9ecef);">
                <button type="submit" class="vortex-btn-locked btn-save-v">
                    <i class="fas fa-save"></i> Simpan File Iklan
                </button>
                <a href="{{ route('file-iklan.index') }}" class="vortex-btn-locked btn-cancel-v">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                </a>
            </div>
        </form>
    </div>
</div>

@endsection