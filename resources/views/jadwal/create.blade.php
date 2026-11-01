@extends('layouts.app')

@section('title', 'Tambah Jadwal Tayang Baru')

@section('content')
<style>
    /* Breadcrumb */
    .jadwal-breadcrumb {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--text-main, #1e293b);
        margin-bottom: 20px;
    }

    /* Card Utama */
    .jadwal-card {
        background-color: var(--bg-card, #ffffff);
        border: 1px solid var(--border-table, #e2e8f0);
        border-radius: 14px;
        padding: 28px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
    }

    [data-theme="dark"] .jadwal-card,
    body.dark .jadwal-card {
        background-color: #16202c !important;
        border-color: #29384a !important;
    }

    /* Header Card */
    .jadwal-header {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 22px;
    }

    .jadwal-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background-color: rgba(56, 189, 248, 0.14);
        color: #38bdf8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .jadwal-header-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text-main, #1e293b);
        margin: 0;
    }

    /* Info Box */
    .jadwal-info-banner {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        border-radius: 10px;
        background-color: rgba(20, 184, 166, 0.1);
        border: 1px solid rgba(20, 184, 166, 0.35);
        color: var(--text-main, #0f766e);
        font-size: 0.86rem;
        margin-bottom: 24px;
    }

    [data-theme="dark"] .jadwal-info-banner,
    body.dark .jadwal-info-banner {
        background-color: rgba(20, 184, 166, 0.12) !important;
        border-color: rgba(45, 212, 191, 0.3) !important;
        color: #99f6e4 !important;
    }

    /* Form Input Umum */
    .jadwal-form-group {
        margin-bottom: 20px;
    }

    .jadwal-label {
        display: block;
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--text-main, #334155);
        margin-bottom: 8px;
    }

    [data-theme="dark"] .jadwal-label,
    body.dark .jadwal-label {
        color: #cbd5e1 !important;
    }

    .jadwal-input,
    .jadwal-textarea {
        width: 100%;
        padding: 11px 14px;
        border-radius: 8px;
        border: 1px solid var(--border-table, #cbd5e1);
        background-color: var(--bg-body, #f8fafc);
        color: var(--text-main, #1e293b);
        font-size: 0.92rem;
        outline: none;
        transition: border-color 0.2s ease, background-color 0.2s ease;
    }

    .jadwal-input:focus,
    .jadwal-textarea:focus {
        border-color: #38bdf8 !important;
    }

    [data-theme="dark"] .jadwal-input,
    [data-theme="dark"] .jadwal-textarea,
    body.dark .jadwal-input,
    body.dark .jadwal-textarea {
        background-color: #243447 !important;
        border-color: #384d66 !important;
        color: #f8fafc !important;
    }

    /* Header Urutan Video */
    .video-section-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 26px;
        margin-bottom: 14px;
    }

    .video-section-title {
        font-size: 0.9rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--text-main, #1e293b);
        margin-bottom: 3px;
    }

    .video-section-desc {
        font-size: 0.84rem;
        color: var(--text-muted, #64748b);
    }

    [data-theme="dark"] .video-section-desc,
    body.dark .video-section-desc {
        color: #94a3b8 !important;
    }

    .btn-add-slot {
        padding: 8px 16px;
        border-radius: 8px;
        border: 1px solid #14b8a6;
        background-color: rgba(20, 184, 166, 0.12);
        color: #14b8a6;
        font-size: 0.84rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.15s ease;
    }

    .btn-add-slot:hover {
        background-color: #14b8a6;
        color: #ffffff;
    }

    /* Baris Slot Video (Sama seperti Distribusi) */
    .video-row-item {
        background-color: var(--bg-body, #f8fafc);
        border: 1px solid var(--border-table, #e2e8f0);
        border-radius: 10px;
        padding: 10px 16px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 14px;
        transition: background-color 0.2s ease, border-color 0.2s ease;
        position: relative;
    }

    [data-theme="dark"] .video-row-item,
    body.dark .video-row-item {
        background-color: #1e293b !important;
        border-color: #334155 !important;
    }

    .video-row-number {
        font-weight: 700;
        font-size: 0.88rem;
        color: #64748b;
        min-width: 68px;
        white-space: nowrap;
    }

    [data-theme="dark"] .video-row-number,
    body.dark .video-row-number {
        color: #94a3b8 !important;
    }

    /* Searchable Combobox Wrapper */
    .video-select-wrap {
        flex: 1;
        min-width: 240px;
        position: relative;
    }

    .video-search-box {
        position: relative;
        display: flex;
        align-items: center;
    }

    .video-search-icon {
        position: absolute;
        left: 13px;
        color: #64748b;
        font-size: 0.85rem;
        pointer-events: none;
    }

    [data-theme="dark"] .video-search-icon,
    body.dark .video-search-icon {
        color: #94a3b8;
    }

    .video-search-chevron {
        position: absolute;
        right: 13px;
        color: #64748b;
        font-size: 0.8rem;
        pointer-events: none;
        transition: transform 0.2s ease;
    }

    .video-select-wrap.open .video-search-chevron {
        transform: rotate(180deg);
    }

    .video-search-input {
        width: 100%;
        padding: 9px 34px 9px 36px;
        border-radius: 8px;
        border: 1px solid var(--border-table, #cbd5e1);
        background-color: var(--bg-card, #ffffff);
        color: var(--text-main, #1e293b);
        font-size: 0.9rem;
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .video-search-input:focus {
        border-color: #38bdf8 !important;
        box-shadow: 0 0 0 2px rgba(56, 189, 248, 0.18);
    }

    .video-search-input::placeholder {
        color: #64748b;
    }

    [data-theme="dark"] .video-search-input,
    body.dark .video-search-input {
        background-color: #243447 !important;
        border-color: #384d66 !important;
        color: #f8fafc !important;
    }

    [data-theme="dark"] .video-search-input::placeholder,
    body.dark .video-search-input::placeholder {
        color: #94a3b8 !important;
    }

    /* Dropdown Menu Hasil Pencarian Video */
    .video-dropdown-menu {
        display: none;
        position: absolute;
        top: calc(100% + 5px);
        left: 0;
        right: 0;
        max-height: 250px;
        overflow-y: auto;
        background-color: var(--bg-card, #ffffff);
        border: 1px solid var(--border-table, #cbd5e1);
        border-radius: 9px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.22);
        z-index: 999;
        padding: 5px;
    }

    .video-select-wrap.open .video-dropdown-menu {
        display: block;
    }

    [data-theme="dark"] .video-dropdown-menu,
    body.dark .video-dropdown-menu {
        background-color: #162231 !important;
        border-color: #384d66 !important;
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.5);
    }

    .video-dropdown-item {
        padding: 9px 12px;
        border-radius: 6px;
        font-size: 0.88rem;
        color: var(--text-main, #1e293b);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        transition: background-color 0.12s ease;
    }

    [data-theme="dark"] .video-dropdown-item,
    body.dark .video-dropdown-item {
        color: #f1f5f9 !important;
    }

    .video-dropdown-item:hover {
        background-color: rgba(56, 189, 248, 0.15);
        color: #0284c7;
    }

    [data-theme="dark"] .video-dropdown-item:hover,
    body.dark .video-dropdown-item:hover {
        background-color: #243b55 !important;
        color: #38bdf8 !important;
    }

    .video-dropdown-item.selected-item {
        background-color: rgba(34, 197, 94, 0.14);
        font-weight: 600;
    }

    .video-type-badge {
        font-size: 0.74rem;
        padding: 2px 8px;
        border-radius: 999px;
        background-color: rgba(56, 189, 248, 0.15);
        color: #0284c7;
        font-weight: 600;
        white-space: nowrap;
    }

    [data-theme="dark"] .video-type-badge,
    body.dark .video-type-badge {
        background-color: rgba(56, 189, 248, 0.18);
        color: #38bdf8;
    }

    .video-dropdown-empty {
        padding: 12px;
        text-align: center;
        font-size: 0.85rem;
        color: #94a3b8;
        display: none;
    }

    /* Tombol Clear / Hapus Slot di Kanan */
    .btn-clear-slot {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.25);
        color: #ef4444;
        border-radius: 8px;
        width: 36px;
        height: 36px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        transition: all 0.15s ease;
        flex-shrink: 0;
    }

    .btn-clear-slot:hover {
        background: #ef4444;
        color: #ffffff;
    }

    /* Tombol Aksi Bawah */
    .jadwal-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid var(--border-table, #e2e8f0);
    }

    [data-theme="dark"] .jadwal-actions,
    body.dark .jadwal-actions {
        border-top-color: #29384a !important;
    }

    .btn-batal {
        padding: 10px 20px;
        border-radius: 8px;
        border: 1px solid var(--border-table, #cbd5e1);
        background-color: transparent;
        color: var(--text-main, #475569);
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    [data-theme="dark"] .btn-batal,
    body.dark .btn-batal {
        border-color: #475569 !important;
        color: #cbd5e1 !important;
    }

    .btn-batal:hover {
        background-color: rgba(148, 163, 184, 0.12);
    }

    .btn-simpan {
        padding: 10px 24px;
        border-radius: 8px;
        border: none;
        background: linear-gradient(135deg, #0ea5e9, #2563eb);
        color: #ffffff;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }

    .btn-simpan:hover {
        opacity: 0.95;
    }
</style>

<div class="jadwal-breadcrumb">
    Iklan &gt; Jadwal Tayang &gt; Tambah
</div>

@if ($errors->any())
    <div style="background: rgba(239, 68, 68, 0.12); border: 1px solid #ef4444; color: #ef4444; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 0.9rem;">
        <ul style="margin: 0; padding-left: 18px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
    // Ambil daftar video iklan dari variabel controller atau fallback langsung ke Model FileIklan
    $daftarVideo = $fileIklan ?? $fileIklans ?? $videos ?? $iklan ?? $dataIklan ?? null;
    if (empty($daftarVideo) || (is_countable($daftarVideo) && count($daftarVideo) === 0)) {
        $daftarVideo = \App\Models\FileIklan::where('aktif', true)->orderBy('nama_iklan', 'asc')->get();
    }

    $videoMap = [];
    foreach ($daftarVideo as $v) {
        $namaV = $v->nama_iklan ?? ('Video #' . $v->id);
        $pelangganV = $v->pelanggan ?? '-';
        $tipeV = $v->tipe ?? 'CONTENT';
        $fullLabel = $namaV . ' -- ' . $pelangganV . ' (' . $tipeV . ')';
        $videoMap[$v->id] = [
            'id' => $v->id,
            'nama' => $namaV,
            'pelanggan' => $pelangganV,
            'tipe' => $tipeV,
            'full_label' => $fullLabel,
            'search' => strtolower($fullLabel),
        ];
    }

    $oldVideos = old('file_iklan_id', old('videos', []));
    $initialSlotCount = max(10, count($oldVideos));
@endphp

<div class="jadwal-card">
    <div class="jadwal-header">
        <div class="jadwal-icon-box">
            <i class="fas fa-plus-circle"></i>
        </div>
        <h2 class="jadwal-header-title">Tambah Jadwal Tayang Baru</h2>
    </div>

    <div class="jadwal-info-banner">
        <i class="fas fa-info-circle"></i>
        <span>Jadwal yang dibuat di sini akan muncul sebagai pilihan saat melakukan <strong>Distribusi Jadwal</strong> ke perangkat TV.</span>
    </div>

    <form action="{{ route('jadwal.store') }}" method="POST" id="formJadwal">
        @csrf

        <div class="jadwal-form-group">
            <label class="jadwal-label">Nama Jadwal Tayang <span style="color: #ef4444;">*</span></label>
            <input type="text" name="nama_jadwal" class="jadwal-input"
                   value="{{ old('nama_jadwal') }}"
                   placeholder="Contoh: PLAYLIST REGULER LAWSON OKTOBER" required>
        </div>

        <div class="jadwal-form-group">
            <label class="jadwal-label">Keterangan</label>
            <textarea name="keterangan" rows="3" class="jadwal-textarea"
                      placeholder="Keterangan opsional mengenai jadwal tayang ini...">{{ old('keterangan') }}</textarea>
        </div>

        <div class="video-section-top">
            <div>
                <div class="video-section-title">Urutan Video Iklan</div>
                <div class="video-section-desc">Ketik untuk mencari video iklan. Slot kosong akan diabaikan secara otomatis saat disimpan.</div>
            </div>
            <button type="button" class="btn-add-slot" onclick="addNewVideoSlot()">
                <i class="fas fa-plus"></i>
                <span>TAMBAH SLOT VIDEO</span>
            </button>
        </div>

        <div id="videoSlotsContainer">
            @for ($i = 0; $i < $initialSlotCount; $i++)
                @php
                    $valVideo = $oldVideos[$i] ?? '';
                    $selectedLabel = isset($videoMap[$valVideo]) ? $videoMap[$valVideo]['full_label'] : '';
                @endphp
                <div class="video-row-item" id="row-video-{{ $i }}" data-slot-index="{{ $i }}">
                    <span class="video-row-number">#{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}:</span>

                    <div class="video-select-wrap" id="wrap-video-{{ $i }}" data-row="{{ $i }}">
                        <div class="video-search-box">
                            <i class="fas fa-search video-search-icon"></i>
                            <input type="text"
                                   id="video_search_{{ $i }}"
                                   class="video-search-input"
                                   value="{{ $selectedLabel }}"
                                   placeholder="-- Ketik atau klik untuk memilih video --"
                                   autocomplete="off"
                                   onfocus="openVideoDropdown({{ $i }})"
                                   oninput="filterVideoDropdown({{ $i }})"
                                   onkeydown="handleVideoKeydown(event, {{ $i }})">
                            <i class="fas fa-chevron-down video-search-chevron"></i>
                        </div>

                        <!-- Kirim kedua kemungkinan nama field agar kompatibel dengan JadwalController -->
                        <input type="hidden" name="file_iklan_id[]" id="hidden_video_{{ $i }}" value="{{ $valVideo }}">
                        <input type="hidden" name="videos[]" id="mirror_video_{{ $i }}" value="{{ $valVideo }}">

                        <div class="video-dropdown-menu" id="video_menu_{{ $i }}">
                            <div class="video-dropdown-item" data-id="" data-label="" onclick="selectVideoOption({{ $i }}, '', '')">
                                <span style="color: #94a3b8; font-style: italic;">-- Kosong --</span>
                            </div>
                            @foreach ($videoMap as $v)
                                <div class="video-dropdown-item {{ (string)$valVideo === (string)$v['id'] ? 'selected-item' : '' }}"
                                     data-id="{{ $v['id'] }}"
                                     data-label="{{ $v['full_label'] }}"
                                     data-search="{{ $v['search'] }}"
                                     onclick="selectVideoOption({{ $i }}, '{{ $v['id'] }}', '{{ addslashes($v['full_label']) }}')">
                                    <span>{{ $v['nama'] }} <small style="opacity: 0.75;">-- {{ $v['pelanggan'] }}</small></span>
                                    <span class="video-type-badge">{{ $v['tipe'] }}</span>
                                </div>
                            @endforeach
                            <div class="video-dropdown-empty" id="video_empty_{{ $i }}">
                                Video iklan tidak ditemukan
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn-clear-slot" onclick="removeOrResetVideoRow({{ $i }})" title="Kosongkan / Hapus slot">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            @endfor
        </div>

        <div class="jadwal-actions">
            <a href="{{ route('jadwal.index') }}" class="btn-batal">Batal</a>
            <button type="submit" class="btn-simpan">
                <i class="fas fa-save"></i>
                <span>Simpan Jadwal Tayang</span>
            </button>
        </div>
    </form>
</div>

<script>
    const MASTER_VIDEOS = @json(array_values($videoMap));
    let nextSlotIndex = {{ $initialSlotCount }};

    function closeAllVideoDropdowns(exceptIndex = null) {
        document.querySelectorAll('.video-select-wrap').forEach((wrap) => {
            const idx = parseInt(wrap.getAttribute('data-row'), 10);
            if (idx !== exceptIndex) {
                wrap.classList.remove('open');
                restoreValidVideoText(idx);
            }
        });
    }

    function openVideoDropdown(index) {
        closeAllVideoDropdowns(index);
        const wrap = document.getElementById('wrap-video-' + index);
        const input = document.getElementById('video_search_' + index);
        if (!wrap || !input) return;

        wrap.classList.add('open');
        input.select();
        filterVideoDropdown(index, true);
    }

    function filterVideoDropdown(index, showAll = false) {
        const wrap = document.getElementById('wrap-video-' + index);
        const input = document.getElementById('video_search_' + index);
        const menu = document.getElementById('video_menu_' + index);
        const emptyMsg = document.getElementById('video_empty_' + index);
        if (!wrap || !input || !menu) return;

        wrap.classList.add('open');
        const keyword = showAll ? '' : input.value.trim().toLowerCase();

        if (input.value.trim() === '') {
            document.getElementById('hidden_video_' + index).value = '';
            document.getElementById('mirror_video_' + index).value = '';
        }

        let visibleCount = 0;
        menu.querySelectorAll('.video-dropdown-item').forEach((item) => {
            const id = item.getAttribute('data-id');
            if (id === '') {
                item.style.display = keyword === '' ? 'flex' : 'none';
                return;
            }
            const searchStr = item.getAttribute('data-search') || '';
            if (keyword === '' || searchStr.includes(keyword)) {
                item.style.display = 'flex';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        if (emptyMsg) {
            emptyMsg.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    }

    function selectVideoOption(index, id, label) {
        const wrap = document.getElementById('wrap-video-' + index);
        const input = document.getElementById('video_search_' + index);
        const hidden = document.getElementById('hidden_video_' + index);
        const mirror = document.getElementById('mirror_video_' + index);
        const menu = document.getElementById('video_menu_' + index);

        if (input) input.value = label;
        if (hidden) hidden.value = id;
        if (mirror) mirror.value = id;

        if (menu) {
            menu.querySelectorAll('.video-dropdown-item').forEach((el) => {
                if (el.getAttribute('data-id') === String(id) && id !== '') {
                    el.classList.add('selected-item');
                } else {
                    el.classList.remove('selected-item');
                }
            });
        }

        if (wrap) wrap.classList.remove('open');
    }

    function restoreValidVideoText(index) {
        const input = document.getElementById('video_search_' + index);
        const hidden = document.getElementById('hidden_video_' + index);
        const menu = document.getElementById('video_menu_' + index);
        if (!input || !hidden || !menu) return;

        const currentId = hidden.value;
        if (!currentId) {
            input.value = '';
            return;
        }

        const matched = menu.querySelector('.video-dropdown-item[data-id="' + currentId + '"]');
        if (matched) {
            input.value = matched.getAttribute('data-label') || '';
        }
    }

    function handleVideoKeydown(e, index) {
        const wrap = document.getElementById('wrap-video-' + index);
        const menu = document.getElementById('video_menu_' + index);
        if (!wrap || !menu) return;

        if (e.key === 'Escape') {
            wrap.classList.remove('open');
            restoreValidVideoText(index);
            return;
        }

        if (e.key === 'Enter') {
            e.preventDefault();
            const firstVisible = Array.from(menu.querySelectorAll('.video-dropdown-item')).find(
                (el) => el.style.display !== 'none' && el.getAttribute('data-id') !== ''
            );
            if (firstVisible) {
                selectVideoOption(
                    index,
                    firstVisible.getAttribute('data-id'),
                    firstVisible.getAttribute('data-label')
                );
            }
        }
    }

    function removeOrResetVideoRow(index) {
        const allRows = document.querySelectorAll('#videoSlotsContainer .video-row-item');
        const row = document.getElementById('row-video-' + index);
        if (!row) return;

        const hidden = document.getElementById('hidden_video_' + index);
        // Jika baris sedang berisi pilihan video, kosongkan dulu. Jika sudah kosong & baris > 1, hapus baris.
        if (hidden && hidden.value !== '') {
            selectVideoOption(index, '', '');
            return;
        }

        if (allRows.length > 1) {
            row.remove();
            renumberVideoRows();
        } else {
            selectVideoOption(index, '', '');
        }
    }

    function renumberVideoRows() {
        document.querySelectorAll('#videoSlotsContainer .video-row-item').forEach((row, idx) => {
            const numSpan = row.querySelector('.video-row-number');
            if (numSpan) {
                numSpan.textContent = '#' + String(idx + 1).padStart(2, '0') + ':';
            }
        });
    }

    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function addNewVideoSlot() {
        const container = document.getElementById('videoSlotsContainer');
        const idx = nextSlotIndex++;
        const currentCount = container.querySelectorAll('.video-row-item').length + 1;

        let optionsHtml = `
            <div class="video-dropdown-item" data-id="" data-label="" onclick="selectVideoOption(${idx}, '', '')">
                <span style="color: #94a3b8; font-style: italic;">-- Kosong --</span>
            </div>
        `;

        MASTER_VIDEOS.forEach((v) => {
            const safeLabelAttr = escapeHtml(v.full_label);
            const safeLabelJs = v.full_label.replace(/\\/g, '\\\\').replace(/'/g, "\\'");
            optionsHtml += `
                <div class="video-dropdown-item"
                     data-id="${v.id}"
                     data-label="${safeLabelAttr}"
                     data-search="${escapeHtml(v.search)}"
                     onclick="selectVideoOption(${idx}, '${v.id}', '${safeLabelJs}')">
                    <span>${escapeHtml(v.nama)} <small style="opacity: 0.75;">-- ${escapeHtml(v.pelanggan)}</small></span>
                    <span class="video-type-badge">${escapeHtml(v.tipe)}</span>
                </div>
            `;
        });

        const rowDiv = document.createElement('div');
        rowDiv.className = 'video-row-item';
        rowDiv.id = 'row-video-' + idx;
        rowDiv.setAttribute('data-slot-index', idx);
        rowDiv.innerHTML = `
            <span class="video-row-number">#${String(currentCount).padStart(2, '0')}:</span>
            <div class="video-select-wrap" id="wrap-video-${idx}" data-row="${idx}">
                <div class="video-search-box">
                    <i class="fas fa-search video-search-icon"></i>
                    <input type="text"
                           id="video_search_${idx}"
                           class="video-search-input"
                           value=""
                           placeholder="-- Ketik atau klik untuk memilih video --"
                           autocomplete="off"
                           onfocus="openVideoDropdown(${idx})"
                           oninput="filterVideoDropdown(${idx})"
                           onkeydown="handleVideoKeydown(event, ${idx})">
                    <i class="fas fa-chevron-down video-search-chevron"></i>
                </div>
                <input type="hidden" name="file_iklan_id[]" id="hidden_video_${idx}" value="">
                <input type="hidden" name="videos[]" id="mirror_video_${idx}" value="">
                <div class="video-dropdown-menu" id="video_menu_${idx}">
                    ${optionsHtml}
                    <div class="video-dropdown-empty" id="video_empty_${idx}">
                        Video iklan tidak ditemukan
                    </div>
                </div>
            </div>
            <button type="button" class="btn-clear-slot" onclick="removeOrResetVideoRow(${idx})" title="Kosongkan / Hapus slot">
                <i class="fas fa-trash"></i>
            </button>
        `;

        container.appendChild(rowDiv);
    }

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.video-select-wrap')) {
            closeAllVideoDropdowns();
        }
    });
</script>
@endsection