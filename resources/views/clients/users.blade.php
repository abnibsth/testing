@extends('layouts.admin')
@section('title', 'User Client')
@section('page-title', 'Kelola Client > Reset Password User')

@section('content')

<style>
    .vortex-card-index {
        background: var(--bg-card, #ffffff); border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        border: 1px solid var(--border-main, #e9ecef); overflow: hidden; width: 100%; margin-top: 10px;
    }
    .toolbar-vortex { display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; justify-content: space-between; align-items: center; }

    .table-vortex { width: 100% !important; border-collapse: collapse !important; margin: 0 !important; }
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

    .badge-vortex-green { background-color: rgba(114, 200, 101, 0.15); color: #2e7d32; padding: 4px 8px; border-radius: 6px; font-weight: 700; font-size: 0.75rem; }
    .badge-vortex-soft { background-color: rgba(231, 76, 60, 0.1); color: #c62828; padding: 4px 8px; border-radius: 6px; font-weight: 700; font-size: 0.75rem; }
    .badge-vortex-warn { background-color: rgba(243, 156, 18, 0.15); color: #b9770e; padding: 4px 8px; border-radius: 6px; font-weight: 700; font-size: 0.75rem; }

    .btn-reset-vortex {
        background: #f39c12; color: #fff; border: none; border-radius: 8px; padding: 8px 14px;
        font-weight: 800; font-size: 12px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-reset-vortex:hover { background: #d68910; }
    .btn-back-vortex {
        background: #b2bec3; color: #fff !important; border-radius: 8px; padding: 10px 18px;
        font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
    }

    .reset-box {
        margin-bottom: 18px; padding: 18px 20px; border-radius: 12px; border-left: 5px solid #f39c12;
        background: rgba(243, 156, 18, 0.08); color: var(--text-main, #2c3e50);
    }
    .reset-box .pw {
        display: inline-block; margin: 8px 10px 8px 0; padding: 10px 16px; border-radius: 8px;
        background: #2c3e50; color: #fff; font-family: 'Courier New', monospace; font-size: 18px;
        font-weight: 800; letter-spacing: 2px; user-select: all;
    }
    .reset-box button {
        background: #2c3e50; color: #fff; border: none; border-radius: 8px; padding: 10px 16px; font-weight: 700; cursor: pointer;
    }
</style>

<div class="toolbar-vortex">
    <div>
        <strong style="font-size: 1.05rem; color: var(--text-main);">{{ $client->nama }}</strong>
        @if($client->subdomain)
            <div style="font-size: 0.8rem; color: var(--text-muted, #7f8c8d); font-weight: 600;">{{ $client->url() }}</div>
        @endif
    </div>
    <a href="{{ route('clients.index') }}" class="btn-back-vortex"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

@if(session('success'))<div class="alert alert-success" style="margin-bottom:16px;">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger" style="margin-bottom:16px;">{{ session('error') }}</div>@endif

@if(session('reset_info'))
    @php($info = session('reset_info'))
    <div class="reset-box">
        <div><strong>Password {{ $info['nama'] }}</strong> (username: <strong>{{ $info['username'] }}</strong>) berhasil direset.</div>
        <span class="pw" id="pwSementara">{{ $info['password'] }}</span>
        <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('pwSementara').textContent).then(() => this.textContent = 'Tersalin')">Salin</button>
        <div style="margin-top: 6px; font-size: 0.82rem; font-weight: 600;">
            Password ini hanya ditampilkan sekali. Berikan ke user; ia wajib menggantinya saat login berikutnya.
        </div>
    </div>
@endif

<div class="vortex-card-index">
    <div class="table-responsive">
        <table class="table-vortex">
            <thead>
                <tr>
                    <th>Nama</th><th>Username</th><th>Email</th><th>Role</th><th>Status</th><th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td><strong>{{ $u->name }}</strong></td>
                    <td>{{ $u->username }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->role }}</td>
                    <td>
                        @if($u->aktif)<span class="badge-vortex-green">Aktif</span>@else<span class="badge-vortex-soft">Nonaktif</span>@endif
                        @if($u->must_change_password)<span class="badge-vortex-warn">Wajib ganti password</span>@endif
                    </td>
                    <td style="text-align:center;">
                        @if($u->id !== auth()->id())
                        <form action="{{ route('clients.users.reset', [$client->id, $u->id]) }}" method="POST"
                              onsubmit="return confirm('Reset password {{ $u->name }}? Password lama tidak akan berlaku lagi.')">
                            @csrf
                            <button type="submit" class="btn-reset-vortex"><i class="fas fa-key"></i> Reset Password</button>
                        </form>
                        @else
                            <small style="color: var(--text-muted);">akun Anda</small>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center; color: var(--text-muted);">Belum ada user untuk client ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
