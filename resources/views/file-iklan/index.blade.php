@extends('layouts.app')
@section('title', 'File Iklan')
@section('page-title', 'Iklan > File Iklan')

@section('content')

{{-- 💡 CSS DIKUNCI MATI DI DALAM KONTEN AGAR 100% KEMBAR DENGAN MODUL LAIN --}}
<style>
    .vortex-card-index {
        background: var(--bg-card, #ffffff); border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        border: 1px solid var(--border-main, #e9ecef); overflow: hidden; width: 100%; margin-top: 10px;
    }
    .toolbar-vortex { display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; justify-content: space-between; align-items: center; }
    
    .table-vortex {
        width: 100% !important; min-width: 100% !important; border-collapse: collapse !important;
        table-layout: fixed !important; margin: 0 !important;
    }
    .table-vortex th {
        background-color: var(--bg-table-th, #f8f9fa) !important; color: var(--text-main, #2c3e50) !important;
        font-weight: 800 !important; border-bottom: 2px solid var(--border-main, #e9ecef) !important;
        text-transform: uppercase !important; font-size: 0.75rem !important; letter-spacing: 0.8px !important;
        padding: 16px 16px !important; vertical-align: middle !important;
    }
    .table-vortex td {
        padding: 14px 16px !important; border-bottom: 1px solid var(--border-table, #cbd5e1) !important;
        color: var(--text-main, #2c3e50) !important; font-weight: 600 !important; font-size: 0.85rem !important;
    }
    .table-vortex tbody tr:hover td { background-color: rgba(114, 200, 101, 0.03) !important; }

    .btn-icon-square {
        width: 36px; height: 36px; padding: 0; display: inline-flex; align-items: center; justify-content: center;
        border-radius: 8px; color: white !important; font-size: 14px; transition: all 0.2s; border: none; text-decoration: none;
    }
    .btn-edit-vortex { background-color: #f39c12; }
    .btn-delete-vortex { background-color: #e74c3c; }
    
    .badge-vortex-content { background: #e3f2fd; color: #1e88e5; padding: 4px 8px; border-radius: 6px; font-weight: 700; font-size: 0.75rem; }
    .badge-vortex-bumper { background: #f3e5f5; color: #ab47bc; padding: 4px 8px; border-radius: 6px; font-weight: 700; font-size: 0.75rem; }
    .badge-vortex-promo { background: #e8f5e9; color: #2e7d32; padding: 4px 8px; border-radius: 6px; font-weight: 700; font-size: 0.75rem; }
</style>

<div class="toolbar-vortex">
    <a href="{{ route('file-iklan.create') }}" class="btn btn-primary" style="background-color: #2c3e50; border-color: #2c3e50; padding: 10px 18px; border-radius: 8px;">
        <i class="fas fa-plus mr-2"></i> Tambah File Iklan
    </a>
    <form method="GET" style="display: flex; gap: 8px; flex: 1; max-width: 500px;">
        <select name="tipe" class="form-control" style="width: auto; border-radius: 8px;">
            <option value="">Semua Tipe</option>
            <option value="CONTENT" {{ request('tipe') == 'CONTENT' ? 'selected' : '' }}>CONTENT</option>
            <option value="BUMPER" {{ request('tipe') == 'BUMPER' ? 'selected' : '' }}>BUMPER</option>
            <option value="PROMO" {{ request('tipe') == 'PROMO' ? 'selected' : '' }}>PROMO</option>
        </select>
        <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-secondary" style="background-color: #b2bec3; border-color: #b2bec3; border-radius: 8px;">
            <i class="fas fa-search"></i>
        </button>
    </form>
</div>

<div class="vortex-card-index">
    <div class="table-responsive">
        <table class="table-vortex">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">Pelanggan</th>
                    <th style="width: 10%;">Tipe</th>
                    <th style="width: 25%;">Nama Iklan</th>
                    <th style="width: 10%; text-align: center;">Preview</th>
                    <th style="width: 10%;">Detail</th>
                    <th style="width: 15%;">Catatan</th>
                    <th style="width: 10%; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($fileIklan as $index => $item)
                <tr>
                    <td>{{ $fileIklan->firstItem() + $index }}</td>
                    <td style="font-weight: 800; color: #f06292;">{{ $item->pelanggan }}</td>
                    <td>
                        @if($item->tipe == 'CONTENT') <span class="badge-vortex-content">CONTENT</span>
                        @elseif($item->tipe == 'BUMPER') <span class="badge-vortex-bumper">BUMPER</span>
                        @else <span class="badge-vortex-promo">PROMO</span> @endif
                    </td>
                    <td>{{ $item->nama_iklan }}</td>
                    <td class="text-center">
                        @if($item->file_path)
                            <a href="{{ asset('media/' . $item->file_path) }}" target="_blank" style="color: #36b9cc; font-size: 1.2rem;">
                                <i class="fas fa-play-circle"></i>
                            </a>
                        @else - @endif
                    </td>
                    <td>
                        <small style="display: block;">{{ $item->ukuran_bytes ? round($item->ukuran_bytes / 1024 / 1024, 2) . ' MB' : '-' }}</small>
                        <small style="display: block; color: var(--text-muted);">{{ $item->durasi_detik ? $item->durasi_detik . ' Detik' : '-' }}</small>
                    </td>
                    <td style="color: var(--text-muted);">{{ $item->catatan ?? '-' }}</td>
                    <td class="text-center">
                        <div style="display: flex; gap: 8px; justify-content: center;">
                            <a href="{{ route('file-iklan.edit', $item->id) }}" class="btn-icon-square btn-edit-vortex"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('file-iklan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus file ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon-square btn-delete-vortex"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center" style="padding: 40px;">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding: 16px 24px;">{{ $fileIklan->links() }}</div>
</div>
@endsection