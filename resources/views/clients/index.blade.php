@extends('layouts.admin')
@section('title', 'Client')
@section('page-title', 'Kelola Client')

@section('content')

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
    .btn-user-vortex { background-color: #36b9cc; }
    .btn-hapus-vortex { background-color: #922b21; cursor: pointer; }
    .link-subdomain { color: #36b9cc; font-weight: 700; text-decoration: none; word-break: break-all; }
    
    .badge-vortex-green { background-color: rgba(114, 200, 101, 0.15); color: #2e7d32; padding: 4px 8px; border-radius: 6px; font-weight: 700; font-size: 0.75rem; }
    .badge-vortex-soft { background-color: rgba(231, 76, 60, 0.1); color: #c62828; padding: 4px 8px; border-radius: 6px; font-weight: 700; font-size: 0.75rem; }
</style>

<div class="toolbar-vortex">
    <a href="{{ route('clients.create') }}" class="btn btn-primary" style="background-color: #2c3e50; border-color: #2c3e50; padding: 10px 18px; border-radius: 8px; font-weight: 700;">
        <i class="fas fa-plus"></i> Tambah Client
    </a>
</div>

@if(session('success'))<div class="alert alert-success" style="margin-bottom:16px;">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger" style="margin-bottom:16px;">{{ session('error') }}</div>@endif

<div class="vortex-card-index">
    <div class="table-responsive">
        <table class="table-vortex">
            <thead>
                <tr>
                    <th>ID</th><th>Kode</th><th>Nama Client</th><th>Subdomain</th>
                    <th style="text-align:center;">User</th><th style="text-align:center;">Toko</th><th style="text-align:center;">Signage</th>
                    <th>Status</th><th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clients as $c)
                <tr>
                    <td>{{ $c->id }}</td>
                    <td>{{ $c->kode }}</td>
                    <td><strong>{{ $c->nama }}</strong></td>
                    <td>@if($c->subdomain)<a href="{{ $c->url() }}" target="_blank" rel="noopener" class="link-subdomain">{{ $c->subdomain }}</a>@else<span style="color: var(--text-muted);">-</span>@endif</td>
                    <td style="text-align:center;">{{ $users[$c->id] ?? 0 }}</td>
                    <td style="text-align:center;">{{ $toko[$c->id] ?? 0 }}</td>
                    <td style="text-align:center;">{{ $signage[$c->id] ?? 0 }}</td>
                    <td>@if($c->aktif)<span class="badge-vortex-green">Aktif</span>@else<span class="badge-vortex-soft">Nonaktif</span>@endif</td>
                    <td style="text-align:center;">
                        <div style="display:inline-flex; gap:6px; align-items:center;">
                        <a href="{{ route('clients.users', $c->id) }}" class="btn-icon-square btn-user-vortex" title="Kelola user &amp; reset password"><i class="fas fa-users-cog"></i></a>
                        @if($c->id != 1)
                        <form action="{{ route('clients.toggle', $c->id) }}" method="POST" onsubmit="return confirm('Ubah status client ini? Semua user client akan {{ $c->aktif ? 'tidak bisa login' : 'bisa login lagi' }}.')">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-icon-square {{ $c->aktif ? 'btn-delete-vortex' : 'btn-edit-vortex' }}" title="{{ $c->aktif ? 'Nonaktifkan' : 'Aktifkan' }}">
                                <i class="fas {{ $c->aktif ? 'fa-ban' : 'fa-check' }}"></i>
                            </button>
                        </form>
                        <form action="{{ route('clients.destroy', $c->id) }}" method="POST" data-kode="{{ $c->kode }}" data-nama="{{ $c->nama }}"
                              onsubmit="var k = prompt('PERINGATAN: client ' + this.dataset.nama + ' beserta SEMUA data (user, toko, signage, jadwal, distribusi, video) akan dihapus PERMANEN dan tidak bisa dikembalikan.\n\nKetik kode client \'' + this.dataset.kode + '\' untuk melanjutkan:'); if (k === null) { return false; } this.konfirmasi.value = k; return true;">
                            @csrf @method('DELETE')
                            <input type="hidden" name="konfirmasi" value="">
                            <button type="submit" class="btn-icon-square btn-hapus-vortex" title="Hapus client permanen"><i class="fas fa-trash"></i></button>
                        </form>
                        @else <small style="color: var(--text-muted);">default</small> @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
