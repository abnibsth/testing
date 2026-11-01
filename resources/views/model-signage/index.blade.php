@extends('layouts.app')
@section('title', 'Model Signage')
@section('page-title', 'Data Master > Model Signage')

@section('content')

<style>
    /* 1. Paksa wrapper template selebar layar */
    .content, .content-wrapper, .container-fluid {
        max-width: 100% !important;
        padding-left: 20px !important;
        padding-right: 20px !important;
    }

    /* 2. Kartu Vortex Full Width */
    .vortex-card {
        background: var(--bg-card);
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        border: 1px solid var(--border-main);
        width: 100% !important; 
        margin-top: 10px;
        overflow: hidden;
    }

    /* 3. Tabel melar sampai ujung */
    .table-responsive {
        width: 100% !important;
        display: block;
        overflow-x: auto;
    }

    .table-vortex {
        width: 100% !important; 
        min-width: 100% !important;
        border-collapse: collapse !important;
        table-layout: auto !important; /* Biarkan browser melar */
    }

    .table-vortex th, .table-vortex td {
        padding: 16px 24px !important;
        text-align: left;
    }

    /* Atur lebar kolom aksi agar tidak melar berlebihan */
    .col-aksi { width: 120px !important; text-align: center; }

    /* Warna & Badge */
    .btn-icon-square { width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; color: white !important; transition: all 0.2s; }
    .btn-edit-vortex { background-color: #f39c12; }
    .btn-delete-vortex { background-color: #e74c3c; }
    .badge-vortex-soft { background-color: rgba(44, 62, 80, 0.1); color: #2c3e50; padding: 6px 12px; border-radius: 6px; font-weight: 700; }
    .badge-vortex-cyan { background-color: rgba(54, 185, 204, 0.1); color: #36b9cc; padding: 6px 12px; border-radius: 6px; font-weight: 700; }
</style>

<div class="toolbar" style="display: flex; gap: 12px; margin-bottom: 24px; justify-content: space-between;">
    <a href="{{ route('model-signage.create') }}" class="btn btn-primary" style="background-color: #2c3e50; border-radius: 8px;">
        <i class="fas fa-plus mr-2"></i> Tambah Model
    </a>
    <form method="GET" style="display: flex; gap: 8px; flex: 1; max-width: 400px;">
        <input type="text" name="search" class="form-control" placeholder="Cari model TV..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-secondary" style="border-radius: 8px;"><i class="fas fa-search"></i></button>
    </form>
</div>

<div class="vortex-card">
    <div class="table-responsive">
        <table class="table-vortex">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Model Perangkat</th>
                    <th>Merk</th>
                    <th>Resolusi & Orientasi</th>
                    <th>Keterangan</th>
                    <th class="col-aksi">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($models as $index => $item)
                <tr>
                    <td style="color: var(--text-muted);">{{ $models->firstItem() + $index }}</td>
                    <td><strong style="color: var(--color-primary);">{{ $item->nama_model }}</strong></td>
                    <td><span class="badge-vortex-soft">{{ $item->merk ?? '-' }}</span></td>
                    <td>
                        <div style="display: flex; flex-direction: column; gap: 6px; align-items: flex-start;">
                            <span class="badge-vortex-cyan">
                                <i class="fas fa-tv mr-1"></i> {{ $item->resolusi ?? '1920x1080' }}
                            </span>
                            <span class="badge-vortex-cyan" style="background-color: rgba(54, 185, 204, 0.06); color: #5bc0be;">
                                <i class="fas fa-retweet mr-1"></i> {{ $item->orientasi ?? 'landscape' }}
                            </span>
                        </div>
                    </td>
                    <td style="color: var(--text-muted);">{{ $item->keterangan ?? '-' }}</td>
                    <td class="col-aksi">
                        <div style="display: flex; gap: 8px; justify-content: center;">
                            <a href="{{ route('model-signage.edit', $item) }}" class="btn-icon-square btn-edit-vortex"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('model-signage.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon-square btn-delete-vortex"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center" style="padding: 40px;">Data tidak ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($models->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid var(--border-main);">
        {{ $models->links() }}
    </div>
    @endif
</div>
@endsection