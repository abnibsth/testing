@extends('layouts.app')
@section('title', 'Edit Model Signage')
@section('page-title', 'Data Master > Model Signage > Edit')

@section('content')

{{-- 
  💡 CSS DIKUNCI MATI DI DALAM KONTEN
  Agar 100% kembar identik dengan halaman Tambah.
--}}
<style>
    .vortex-card-edit {
        background: var(--bg-card, #ffffff);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        border: 1px solid var(--border-main, #e9ecef);
        overflow: hidden;
        width: 100%;
        max-width: 800px; /* Lebar dibatasi agar proporsional dan tidak kepanjangan */
        margin-top: 10px;
    }

    .vortex-header-edit {
        padding: 24px 24px 16px 24px;
        border-bottom: 1px solid var(--border-main, #e9ecef);
        display: flex;
        align-items: center;
    }

    .vortex-header-edit h5 {
        margin: 0;
        font-weight: 800;
        color: var(--text-main, #2c3e50);
        font-size: 1.25rem;
        display: flex;
        align-items: center;
    }

    .icon-title-box {
        width: 42px; height: 42px; 
        background: rgba(54, 185, 204, 0.15); 
        color: #36b9cc; 
        border-radius: 10px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        margin-right: 16px;
    }

    .vortex-body-edit { padding: 30px 24px; }

    .vortex-label-locked {
        font-size: 12px;
        font-weight: 800;
        color: var(--text-muted, #7f8c8d);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 8px;
        display: block;
    }

    .vortex-input-locked {
        width: 100% !important;
        display: block !important;
        padding: 14px 16px;
        border: 1px solid var(--border-table, #cbd5e1);
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        font-family: inherit;
        background: var(--bg-input, #f8f9fa);
        color: var(--text-main, #2c3e50);
        transition: all 0.2s ease;
        box-sizing: border-box;
    }

    .vortex-input-locked:focus {
        outline: none;
        border-color: #36b9cc;
        box-shadow: 0 0 0 3px rgba(54, 185, 204, 0.15);
        background: var(--bg-card, #ffffff);
    }

    .vortex-btn-locked {
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 800;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s ease;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-save-v { background: #2c3e50; color: #ffffff; box-shadow: 0 4px 10px rgba(44, 62, 80, 0.2); }
    .btn-save-v:hover { background: #1a252f; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(44, 62, 80, 0.3); color: #ffffff; }

    .btn-cancel-v { background: #b2bec3; color: #ffffff; }
    .btn-cancel-v:hover { background: #95a5a6; transform: translateY(-2px); color: #ffffff; }

    .vortex-error-text { color: #e74c3c; font-size: 12px; font-weight: 700; margin-top: 6px; display: block; }
</style>

<div class="vortex-card-edit">
    <div class="vortex-header-edit">
        <h5>
            <div class="icon-title-box">
                <i class="fas fa-edit" style="font-size: 16px;"></i>
            </div>
            Ubah Data Model Signage
        </h5>
    </div>
    
    <div class="vortex-body-edit">
        <form action="{{ route('model-signage.update', $modelSignage) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 24px;">
                <label for="nama_model" class="vortex-label-locked">
                    Nama Model TV / Perangkat <span style="color: #e74c3c; font-size: 14px; margin-left: 2px;">*</span>
                </label>
                <input type="text" 
                       name="nama_model" 
                       id="nama_model" 
                       class="vortex-input-locked" 
                       value="{{ old('nama_model', $modelSignage->nama_model) }}" 
                       placeholder="Contoh: QLED 55 Inch Ultra" 
                       required>
                @error('nama_model')
                    <span class="vortex-error-text"><i class="fas fa-exclamation-circle" style="margin-right: 4px;"></i> {{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 24px;">
                <label for="merk" class="vortex-label-locked">Merk / Pabrikan</label>
                <input type="text" 
                       name="merk" 
                       id="merk" 
                       class="vortex-input-locked" 
                       value="{{ old('merk', $modelSignage->merk) }}"
                       placeholder="Contoh: Samsung / LG / Polytron">
                @error('merk')
                    <span class="vortex-error-text"><i class="fas fa-exclamation-circle" style="margin-right: 4px;"></i> {{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 24px;">
                <label for="resolusi" class="vortex-label-locked">Resolusi Layar</label>
                <input type="text" 
                       name="resolusi" 
                       id="resolusi" 
                       class="vortex-input-locked" 
                       value="{{ old('resolusi', $modelSignage->resolusi) }}"
                       placeholder="Contoh: 1920x1080 atau 1080x1920">
                @error('resolusi')
                    <span class="vortex-error-text"><i class="fas fa-exclamation-circle" style="margin-right: 4px;"></i> {{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 24px;">
                <label for="orientasi" class="vortex-label-locked">Orientasi Layar</label>
                <select name="orientasi" id="orientasi" class="vortex-input-locked">
                    <option value="landscape" {{ old('orientasi', $modelSignage->orientasi) == 'landscape' ? 'selected' : '' }}>Landscape (Horizontal)</option>
                    <option value="portrait" {{ old('orientasi', $modelSignage->orientasi) == 'portrait' ? 'selected' : '' }}>Portrait (Vertikal)</option>
                </select>
                @error('orientasi')
                    <span class="vortex-error-text"><i class="fas fa-exclamation-circle" style="margin-right: 4px;"></i> {{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 12px;">
                <label for="keterangan" class="vortex-label-locked">Keterangan Tambahan</label>
                <textarea name="keterangan" 
                          id="keterangan" 
                          class="vortex-input-locked" 
                          rows="5"
                          placeholder="Deskripsi spesifikasi tambahan (opsional)...">{{ old('keterangan', $modelSignage->keterangan) }}</textarea>
                @error('keterangan')
                    <span class="vortex-error-text"><i class="fas fa-exclamation-circle" style="margin-right: 4px;"></i> {{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; gap: 12px; margin-top: 36px; padding-top: 24px; border-top: 1px solid var(--border-main, #e9ecef);">
                <button type="submit" class="vortex-btn-locked btn-save-v">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('model-signage.index') }}" class="vortex-btn-locked btn-cancel-v">
                    <i class="fas fa-times"></i> Batalkan
                </a>
            </div>
        </form>
    </div>
</div>
@endsection