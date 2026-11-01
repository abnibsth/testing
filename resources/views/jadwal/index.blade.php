@extends('layouts.app')
@section('title', 'Jadwal Tayang Iklan')
@section('page-title', 'Iklan > Jadwal Tayang Iklan')

@section('content')

{{-- 💡 CSS DIKUNCI MATI AGAR 100% KONSISTEN DENGAN MODUL LAIN --}}
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

    /* Modal Styling */
    .custom-modal { display: none; position: fixed; z-index: 1050; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); align-items: center; justify-content: center; backdrop-filter: blur(4px); }
    .custom-modal.show { display: flex; }
    .modal-pastel-content { background-color: var(--bg-card); padding: 24px; border-radius: 16px; width: 90%; max-width: 550px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
    .video-item-list { list-style: none; padding: 0; margin-top: 15px; max-height: 300px; overflow-y: auto; }
    .video-item-list li { padding: 12px; border-bottom: 1px solid var(--border-table); display: flex; align-items: center; gap: 12px; }
    
    .modal-pastel-content { max-width: 640px !important; max-height: 92vh; overflow-y: auto; }
    .video-item-list li.video-clickable { cursor: pointer; border-radius: 8px; transition: background .15s; }
    .video-item-list li.video-clickable:hover { background: rgba(54, 185, 204, 0.08); }
    .video-item-list li.video-clickable.playing { background: rgba(114, 200, 101, 0.15); }
    .video-player-box { display: none; margin-top: 14px; background: #000; border-radius: 10px; overflow: hidden; }
    .video-player-box video, .video-player-box img { width: 100%; max-height: 340px; display: block; object-fit: contain; }
    .video-player-title { display: none; margin-top: 10px; font-weight: 800; font-size: 13px; color: var(--text-main); }
    .video-count-badge { background: rgba(114, 200, 101, 0.15); color: #2e7d32; padding: 6px 14px; border-radius: 30px; font-weight: 800; font-size: 0.8rem; cursor: pointer; border: none; }
</style>

<div class="toolbar-vortex">
    <a href="{{ route('jadwal.create') }}" class="btn btn-primary" style="background-color: #2c3e50; border-color: #2c3e50; padding: 10px 18px; border-radius: 8px;">
        <i class="fas fa-plus mr-2"></i> Tambah Jadwal
    </a>
    <form method="GET" style="display: flex; gap: 8px; flex: 1; max-width: 400px;">
        <input type="text" name="search" class="form-control" placeholder="Cari nama jadwal..." value="{{ request('search') }}">
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
                    <th style="width: 8%;">No</th>
                    <th style="width: 45%;">Nama Jadwal Tayang</th>
                    <th style="width: 25%;">Video Iklan</th>
                    <th style="width: 22%; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jadwal as $index => $item)
                <tr>
                    <td>{{ $jadwal->firstItem() + $index }}</td>
                    <td style="font-weight: 800; font-size: 15px; color: var(--color-primary);">{{ $item->nama_jadwal }}</td>
                    <td>
                        <button type="button" class="video-count-badge" onclick="openVideoModal('modal-{{ $item->id }}')">
                            <i class="fas fa-film mr-1"></i> {{ $item->fileIklan->count() }} File
                        </button>

                        <div id="modal-{{ $item->id }}" class="custom-modal" onclick="if(event.target==this) closeVideoModal('modal-{{ $item->id }}')">
                            <div class="modal-pastel-content">
                                <div style="display:flex; justify-content:space-between; align-items:center; border-bottom: 1px solid var(--border-table); padding-bottom: 10px;">
                                    <h3 style="margin:0; font-size:16px; font-weight:800;">Daftar Video: {{ $item->nama_jadwal }}</h3>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="closeVideoModal('modal-{{ $item->id }}')"><i class="fas fa-times"></i></button>
                                </div>
                                <div class="video-player-title" id="title-{{ $item->id }}"></div>
                                <div class="video-player-box" id="player-{{ $item->id }}"></div>
                                <ul class="video-item-list">
                                    @forelse($item->fileIklan as $video)
                                        <li class="{{ $video->file_path ? 'video-clickable' : '' }}"
                                            @if($video->file_path)
                                                data-src="{{ asset('media/' . $video->file_path) }}"
                                                data-name="{{ $video->nama_iklan }}"
                                                onclick="playVideo({{ $item->id }}, this)"
                                                title="Klik untuk memutar"
                                            @endif>
                                            <i class="fas fa-play-circle" style="color: #36b9cc;"></i>
                                            <div>
                                                <div style="font-weight:700;">{{ $video->nama_iklan }}</div>
                                                <small style="color: var(--text-muted);">{{ $video->pelanggan }} | {{ $video->tipe }}@if(!$video->file_path) | file tidak tersedia @endif</small>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="text-center">Tidak ada video.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 8px; justify-content: center;">
                            <a href="{{ route('jadwal.edit', $item->id) }}" class="btn-icon-square btn-edit-vortex"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('jadwal.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon-square btn-delete-vortex"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center" style="padding: 40px;">Belum ada data jadwal.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding: 16px 24px;">{{ $jadwal->links() }}</div>
</div>

<script>
    function openVideoModal(id) { document.getElementById(id).classList.add('show'); }

    function stopPlayer(modalId) {
        const modal = document.getElementById(modalId);
        modal.querySelectorAll('video').forEach(v => { v.pause(); v.removeAttribute('src'); v.load(); });
        const box = modal.querySelector('.video-player-box');
        const ttl = modal.querySelector('.video-player-title');
        box.innerHTML = ''; box.style.display = 'none'; ttl.style.display = 'none';
        modal.querySelectorAll('.video-clickable.playing').forEach(li => li.classList.remove('playing'));
    }
    function closeVideoModal(id) {
        stopPlayer(id);
        document.getElementById(id).classList.remove('show');
    }

    // Klik item video -> putar di dalam modal (untuk crosscheck isi jadwal)
    function playVideo(jadwalId, li) {
        const box = document.getElementById('player-' + jadwalId);
        const ttl = document.getElementById('title-' + jadwalId);
        const src = li.dataset.src;
        const isImage = /\.(jpe?g|png|gif|webp|bmp)(\?.*)?$/i.test(src);

        li.parentElement.querySelectorAll('.playing').forEach(x => x.classList.remove('playing'));
        li.classList.add('playing');

        box.innerHTML = '';
        const media = document.createElement(isImage ? 'img' : 'video');
        media.src = src;
        if (!isImage) { media.controls = true; media.autoplay = true; media.playsInline = true; }
        box.appendChild(media);
        box.style.display = 'block';
        ttl.textContent = 'Memutar: ' + li.dataset.name;
        ttl.style.display = 'block';
        box.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
</script>
@endsection