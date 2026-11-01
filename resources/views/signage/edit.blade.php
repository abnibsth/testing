@extends('layouts.app')
@section('title', 'Edit Digital Signage')
@section('page-title', 'Iklan > Digital Signage > Edit Signage')

@section('content')

{{-- 💡 CSS DIKUNCI MATI AGAR KEMBAR DENGAN HALAMAN LAIN --}}
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
    .vortex-input-error { border-color: #e74c3c !important; background: #fff5f5 !important; }
    .vortex-input-error:focus { box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.18) !important; }
    .vortex-error-text { color: #e74c3c; font-size: 12px; font-weight: 700; margin-top: 6px; display: flex; align-items: center; gap: 5px; }
    .vortex-btn-locked { padding: 12px 24px; border-radius: 8px; font-weight: 800; font-size: 13px; text-transform: uppercase; border: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s ease; cursor: pointer; text-decoration: none; }
    .btn-save-v { background: #2c3e50; color: #ffffff; box-shadow: 0 4px 10px rgba(44, 62, 80, 0.2); }
    .btn-cancel-v { background: #b2bec3; color: #ffffff; }
    .vortex-form-row { display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 24px; width: 100%; }
    .vortex-form-col { flex: 1; min-width: 250px; }
</style>

<div class="vortex-card-form">
    <div class="vortex-header-form">
        <h5>
            <div class="icon-title-box"><i class="fas fa-edit" style="font-size: 16px;"></i></div>
            Edit Signage: {{ $signage->nama_signage }}
        </h5>
    </div>
    
    <div class="vortex-body-form">
        {{-- 🚨 NOTIFIKASI ALERT ERROR 🚨 --}}
        @if($errors->any())
            <div style="background: #fdecea; color: #c62828; border-left: 4px solid #e74c3c; padding: 14px 18px; border-radius: 10px; font-weight: 700; font-size: 13px; margin-bottom: 24px; display: flex; align-items: flex-start; gap: 12px; box-shadow: 0 4px 12px rgba(231,76,60,0.08);">
                <i class="fas fa-exclamation-triangle" style="font-size: 20px; color: #e74c3c; margin-top: 1px; flex-shrink: 0;"></i>
                <div>
                    <div style="font-weight: 800; font-size: 14px; margin-bottom: 4px;">Peringatan: Gagal Memperbarui Digital Signage</div>
                    @foreach($errors->all() as $e)
                        <div>• {{ $e }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('signage.update', $signage->id) }}">
            @csrf @method('PUT')

            <div class="vortex-form-row">
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Toko Penempatan <span style="color: #e74c3c;">*</span></label>
                    <select name="toko_id" class="vortex-input-locked" required>
                        @foreach($toko as $t)
                            <option value="{{ $t->id }}" {{ old('toko_id', $signage->toko_id) == $t->id ? 'selected' : '' }}>
                                {{ $t->nama_toko }} ({{ $t->kode_toko }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Model Signage / TV <span style="color: #e74c3c;">*</span></label>
                    <select name="model_signage_id" class="vortex-input-locked" required>
                        @foreach($modelSignage as $m)
                            <option value="{{ $m->id }}" {{ old('model_signage_id', $signage->model_signage_id) == $m->id ? 'selected' : '' }}>
                                {{ $m->merk ?? 'No Brand' }} - {{ $m->nama_model ?? 'Unnamed' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="vortex-form-row">
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Nama Signage <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="nama_signage" class="vortex-input-locked" value="{{ old('nama_signage', $signage->nama_signage) }}" required>
                </div>
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Device ID <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="device_id" 
                           class="vortex-input-locked @error('device_id') vortex-input-error @enderror" 
                           value="{{ old('device_id', $signage->device_id) }}" 
                           required>
                    @error('device_id')
                        <span class="vortex-error-text">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>

            <div class="vortex-form-row">
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Serial Number <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="serial_number" class="vortex-input-locked" value="{{ old('serial_number', $signage->serial_number) }}" required>
                </div>
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Resolusi (W x H) <span style="color: #e74c3c;">*</span></label>
                    <div style="display:flex; gap: 8px;">
                        <input type="number" name="resolusi_width" class="vortex-input-locked" value="{{ old('resolusi_width', $signage->resolusi_width) }}" required>
                        <input type="number" name="resolusi_height" class="vortex-input-locked" value="{{ old('resolusi_height', $signage->resolusi_height) }}" required>
                    </div>
                </div>
            </div>

            <div class="vortex-form-row">
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Orientasi <span style="color: #e74c3c;">*</span></label>
                    <select name="orientasi" class="vortex-input-locked">
                        @foreach(['Landscape Kiri','Landscape Kanan','Portrait'] as $o)
                            <option value="{{ $o }}" {{ old('orientasi', $signage->orientasi) === $o ? 'selected' : '' }}>{{ $o }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Tanggal Aktivasi</label>
                    <input type="date" name="tanggal_aktivasi" class="vortex-input-locked" value="{{ old('tanggal_aktivasi', $signage->tanggal_aktivasi?->format('Y-m-d')) }}">
                </div>
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Status <span style="color: #e74c3c;">*</span></label>
                    <select name="status" class="vortex-input-locked">
                        @foreach(['Aktif','Nonaktif','Maintenance'] as $s)
                            <option value="{{ $s }}" {{ old('status', $signage->status) === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 12px;">
                <label class="vortex-label-locked">Keterangan</label>
                <textarea name="keterangan" class="vortex-input-locked" rows="2">{{ old('keterangan', $signage->keterangan) }}</textarea>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 36px; padding-top: 24px; border-top: 1px solid var(--border-main, #e9ecef);">
                <button type="submit" class="vortex-btn-locked btn-save-v"><i class="fas fa-save"></i> Update Data</button>
                <a href="{{ route('signage.index') }}" class="vortex-btn-locked btn-cancel-v"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection