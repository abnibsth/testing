@extends('layouts.app')
@section('title', 'Edit File Iklan')
@section('page-title', 'Iklan > File Iklan > Edit File Iklan')

@section('content')

{{-- 💡 CSS DIKUNCI MATI AGAR 100% KONSISTEN DENGAN MODUL LAIN --}}
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
    .vortex-btn-locked { padding: 12px 24px; border-radius: 8px; font-weight: 800; font-size: 13px; text-transform: uppercase; border: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s ease; cursor: pointer; text-decoration: none; }
    .btn-save-v { background: #2c3e50; color: #ffffff; box-shadow: 0 4px 10px rgba(44, 62, 80, 0.2); }
    .btn-cancel-v { background: #b2bec3; color: #ffffff; }
    .vortex-form-row { display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 24px; width: 100%; }
    .vortex-form-col { flex: 1; min-width: 250px; }
    .file-info-alert { background: #f8f9fa; border: 1px solid #e9ecef; padding: 10px 14px; border-radius: 8px; font-size: 12px; color: #7f8c8d; margin-bottom: 12px; }
</style>

<div class="vortex-card-form">
    <div class="vortex-header-form">
        <h5>
            <div class="icon-title-box"><i class="fas fa-edit" style="font-size: 16px;"></i></div>
            Edit File Iklan: {{ $fileIklan->nama_iklan }}
        </h5>
    </div>
    
    <div class="vortex-body-form">
        <form method="POST" action="{{ route('file-iklan.update', $fileIklan->id) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="vortex-form-row">
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Pelanggan <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="pelanggan" class="vortex-input-locked" value="{{ old('pelanggan', $fileIklan->pelanggan) }}" required>
                </div>
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Tipe <span style="color: #e74c3c;">*</span></label>
                    <select name="tipe" class="vortex-input-locked">
                        @foreach(['CONTENT','BUMPER','PROMO'] as $t)
                            <option value="{{ $t }}" {{ old('tipe', $fileIklan->tipe) === $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 24px;">
                <label class="vortex-label-locked">Nama Iklan <span style="color: #e74c3c;">*</span></label>
                <input type="text" name="nama_iklan" class="vortex-input-locked" value="{{ old('nama_iklan', $fileIklan->nama_iklan) }}" required>
            </div>

            <div class="vortex-form-row">
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Durasi Video (detik)</label>
                    <input type="number" name="durasi_detik" class="vortex-input-locked" value="{{ old('durasi_detik', $fileIklan->durasi_detik) }}" min="1">
                </div>
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Ganti File Video</label>
                    @if($fileIklan->file_path)
                        <div class="file-info-alert">
                            File saat ini: <strong>{{ $fileIklan->file_path }}</strong>
                        </div>
                    @endif
                    <input type="file" name="file_video" class="vortex-input-locked" accept="video/mp4,video/avi,video/quicktime,video/x-matroska" style="padding: 10px;">
                    <small style="display:block; margin-top:5px; color:#95a5a6; font-size:11px;">Kosongkan jika tidak ingin mengganti file.</small>
                </div>
            </div>

            <div style="margin-bottom: 12px;">
                <label class="vortex-label-locked">Catatan</label>
                <textarea name="catatan" class="vortex-input-locked" rows="2">{{ old('catatan', $fileIklan->catatan) }}</textarea>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 36px; padding-top: 24px; border-top: 1px solid var(--border-main, #e9ecef);">
                <button type="submit" class="vortex-btn-locked btn-save-v"><i class="fas fa-save"></i> Update Data</button>
                <a href="{{ route('file-iklan.index') }}" class="vortex-btn-locked btn-cancel-v"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection