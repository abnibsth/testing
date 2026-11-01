@extends('layouts.app')

@section('title', 'Tambah Distribusi Jadwal Tayang')

@section('content')
<style>
    /* Wrapper & Breadcrumb */
    .dist-breadcrumb {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--text-main, #1e293b);
        margin-bottom: 20px;
    }

    /* Card Utama */
    .dist-card {
        background-color: var(--bg-card, #ffffff);
        border: 1px solid var(--border-table, #e2e8f0);
        border-radius: 14px;
        padding: 28px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
    }

    [data-theme="dark"] .dist-card,
    body.dark .dist-card {
        background-color: #16202c !important;
        border-color: #29384a !important;
    }

    /* Header Card */
    .dist-header {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 26px;
    }

    .dist-icon-box {
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

    .dist-header-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text-main, #1e293b);
        margin: 0;
    }

    /* Grid Tanggal */
    .dist-date-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 280px));
        gap: 20px;
        margin-bottom: 28px;
    }

    .dist-label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-main, #334155);
        margin-bottom: 8px;
    }

    [data-theme="dark"] .dist-label,
    body.dark .dist-label {
        color: #e2e8f0 !important;
    }

    .dist-input {
        width: 100%;
        padding: 10px 14px;
        border-radius: 8px;
        border: 1px solid var(--border-table, #cbd5e1);
        background-color: var(--bg-body, #f8fafc);
        color: var(--text-main, #1e293b);
        font-size: 0.92rem;
        outline: none;
        transition: border-color 0.2s ease, background-color 0.2s ease;
    }

    .dist-input:focus {
        border-color: #38bdf8;
    }

    [data-theme="dark"] .dist-input,
    body.dark .dist-input {
        background-color: #243447 !important;
        border-color: #384d66 !important;
        color: #f8fafc !important;
        color-scheme: dark;
    }

    /* Section Judul Jadwal & Jam */
    .dist-section-title {
        font-size: 0.98rem;
        font-weight: 700;
        color: var(--text-main, #1e293b);
        margin-bottom: 4px;
    }

    .dist-section-desc {
        font-size: 0.85rem;
        color: var(--text-muted, #64748b);
        margin-bottom: 16px;
    }

    [data-theme="dark"] .dist-section-desc,
    body.dark .dist-section-desc {
        color: #94a3b8 !important;
    }

    .dist-section-desc strong {
        color: var(--text-main, #1e293b);
    }

    [data-theme="dark"] .dist-section-desc strong,
    body.dark .dist-section-desc strong {
        color: #f8fafc !important;
    }

    /* Baris Item Jadwal (#01 - #06) */
    .jadwal-row-item {
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

    [data-theme="dark"] .jadwal-row-item,
    body.dark .jadwal-row-item {
        background-color: #1e293b !important;
        border-color: #334155 !important;
    }

    .jadwal-row-number {
        font-weight: 700;
        font-size: 0.9rem;
        color: #64748b;
        min-width: 38px;
    }

    [data-theme="dark"] .jadwal-row-number,
    body.dark .jadwal-row-number {
        color: #94a3b8 !important;
    }

    /* Searchable Combobox Wrapper */
    .jadwal-select-wrap {
        flex: 1;
        min-width: 240px;
        position: relative;
    }

    .jadwal-search-box {
        position: relative;
        display: flex;
        align-items: center;
    }

    .jadwal-search-icon {
        position: absolute;
        left: 13px;
        color: #64748b;
        font-size: 0.85rem;
        pointer-events: none;
    }

    [data-theme="dark"] .jadwal-search-icon,
    body.dark .jadwal-search-icon {
        color: #94a3b8;
    }

    .jadwal-search-chevron {
        position: absolute;
        right: 13px;
        color: #64748b;
        font-size: 0.8rem;
        pointer-events: none;
        transition: transform 0.2s ease;
    }

    .jadwal-select-wrap.open .jadwal-search-chevron {
        transform: rotate(180deg);
    }

    .jadwal-search-input {
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

    .jadwal-search-input:focus {
        border-color: #38bdf8 !important;
        box-shadow: 0 0 0 2px rgba(56, 189, 248, 0.18);
    }

    .jadwal-search-input::placeholder {
        color: #64748b;
    }

    [data-theme="dark"] .jadwal-search-input,
    body.dark .jadwal-search-input {
        background-color: #243447 !important;
        border-color: #384d66 !important;
        color: #f8fafc !important;
    }

    [data-theme="dark"] .jadwal-search-input::placeholder,
    body.dark .jadwal-search-input::placeholder {
        color: #94a3b8 !important;
    }

    /* Dropdown Menu Hasil Pencarian */
    .jadwal-dropdown-menu {
        display: none;
        position: absolute;
        top: calc(100% + 5px);
        left: 0;
        right: 0;
        max-height: 240px;
        overflow-y: auto;
        background-color: var(--bg-card, #ffffff);
        border: 1px solid var(--border-table, #cbd5e1);
        border-radius: 9px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.22);
        z-index: 999;
        padding: 5px;
    }

    .jadwal-select-wrap.open .jadwal-dropdown-menu {
        display: block;
    }

    [data-theme="dark"] .jadwal-dropdown-menu,
    body.dark .jadwal-dropdown-menu {
        background-color: #162231 !important;
        border-color: #384d66 !important;
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.5);
    }

    .jadwal-dropdown-item {
        padding: 9px 12px;
        border-radius: 6px;
        font-size: 0.88rem;
        color: var(--text-main, #1e293b);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: background-color 0.12s ease;
    }

    [data-theme="dark"] .jadwal-dropdown-item,
    body.dark .jadwal-dropdown-item {
        color: #f1f5f9 !important;
    }

    .jadwal-dropdown-item:hover,
    .jadwal-dropdown-item.active-item {
        background-color: rgba(56, 189, 248, 0.15);
        color: #0284c7;
    }

    [data-theme="dark"] .jadwal-dropdown-item:hover,
    [data-theme="dark"] .jadwal-dropdown-item.active-item,
    body.dark .jadwal-dropdown-item:hover,
    body.dark .jadwal-dropdown-item.active-item {
        background-color: #243b55 !important;
        color: #38bdf8 !important;
    }

    .jadwal-dropdown-item.selected-item {
        background-color: rgba(34, 197, 94, 0.14);
        font-weight: 600;
    }

    .jadwal-video-badge {
        font-size: 0.76rem;
        padding: 2px 8px;
        border-radius: 999px;
        background-color: rgba(148, 163, 184, 0.16);
        color: var(--text-muted, #64748b);
        margin-left: 8px;
        white-space: nowrap;
    }

    [data-theme="dark"] .jadwal-video-badge,
    body.dark .jadwal-video-badge {
        background-color: rgba(148, 163, 184, 0.18);
        color: #cbd5e1;
    }

    .jadwal-dropdown-empty {
        padding: 12px;
        text-align: center;
        font-size: 0.85rem;
        color: #94a3b8;
        display: none;
    }

    /* Bagian Jam Tayang di dalam Baris */
    .jadwal-time-group {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: nowrap;
    }

    .jadwal-time-label {
        font-size: 0.84rem;
        font-weight: 600;
        color: #475569;
        white-space: nowrap;
        margin: 0;
    }

    [data-theme="dark"] .jadwal-time-label,
    body.dark .jadwal-time-label {
        color: #cbd5e1 !important;
    }

    .jadwal-time-input {
        width: 86px;
        padding: 8px 10px;
        border-radius: 8px;
        border: 1px solid var(--border-table, #cbd5e1);
        background-color: var(--bg-card, #ffffff);
        color: var(--text-main, #1e293b);
        font-size: 0.9rem;
        font-weight: 600;
        text-align: center;
        outline: none;
    }

    [data-theme="dark"] .jadwal-time-input,
    body.dark .jadwal-time-input {
        background-color: #0f172a !important;
        border-color: #475569 !important;
        color: #f8fafc !important;
        color-scheme: dark;
    }

    .jadwal-time-sep {
        font-weight: 700;
        color: #475569;
    }

    [data-theme="dark"] .jadwal-time-sep,
    body.dark .jadwal-time-sep {
        color: #cbd5e1 !important;
    }

    /* Tombol Clear Baris */
    .btn-clear-row {
        background: transparent;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        padding: 4px 6px;
        font-size: 1.05rem;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.15s ease;
    }

    .btn-clear-row:hover {
        color: #ef4444;
    }

    /* Frekuensi Hari */
    .freq-section {
        margin-top: 24px;
        margin-bottom: 24px;
    }

    .freq-options {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 8px;
    }

    .freq-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 8px;
        border: 1px solid var(--border-table, #cbd5e1);
        background-color: var(--bg-body, #f8fafc);
        color: var(--text-main, #1e293b);
        font-size: 0.88rem;
        font-weight: 500;
        cursor: pointer;
    }

    [data-theme="dark"] .freq-pill,
    body.dark .freq-pill {
        background-color: #1e293b !important;
        border-color: #334155 !important;
        color: #e2e8f0 !important;
    }

    /* Tombol Aksi Bawah */
    .dist-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid var(--border-table, #e2e8f0);
    }

    [data-theme="dark"] .dist-actions,
    body.dark .dist-actions {
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

    .btn-lanjut {
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

    .btn-lanjut:hover {
        opacity: 0.95;
    }

    @media (max-width: 768px) {
        .jadwal-row-item {
            flex-wrap: wrap;
        }
        .jadwal-select-wrap {
            width: 100%;
            flex: none;
        }
        .jadwal-time-group {
            width: 100%;
            justify-content: space-between;
        }
    }
</style>

<div class="dist-breadcrumb">
    Iklan &gt; Distribusi &gt; Tambah
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

<div class="dist-card">
    <div class="dist-header">
        <div class="dist-icon-box">
            <i class="fas fa-layer-group"></i>
        </div>
        <h2 class="dist-header-title">Tambah Distribusi Jadwal Tayang (Multi-Jam Sekaligus)</h2>
    </div>

    <form action="{{ route('distribusi.lanjut') }}" method="POST" id="formDistribusi">
        @csrf

        <!-- Input Tanggal Mulai & Selesai -->
        <div class="dist-date-grid">
            <div>
                <label class="dist-label">Tanggal Mulai <span style="color: #ef4444;">*</span></label>
                <input type="date" name="tanggal_mulai" class="dist-input"
                       value="{{ old('tanggal_mulai', session('distribusi_step1.tanggal_mulai', date('Y-m-d'))) }}" required>
            </div>
            <div>
                <label class="dist-label">Tanggal Selesai <span style="color: #ef4444;">*</span></label>
                <input type="date" name="tanggal_selesai" class="dist-input"
                       value="{{ old('tanggal_selesai', session('distribusi_step1.tanggal_selesai', date('Y-m-d', strtotime('+1 year')))) }}" required>
            </div>
        </div>

        <!-- Daftar Multi-Jadwal & Jam Tayang -->
        <div>
            <div class="dist-section-title">Nama Jadwal Tayang &amp; Jam Tayang</div>
            <div class="dist-section-desc">
                Pilih atau ketik untuk mencari jadwal tayang dan tentukan jam tayangnya. Format harian utama (<strong>00:00 - 23:59</strong>) dan promo di jam lain (misal: <strong>11:00 - 12:00</strong>) didistribusikan sekalian.
            </div>

            @php
                $daftarJadwal = $jadwal ?? $jadwals ?? $jadwalTayang ?? $jadwalTayangs ?? $jadwalList ?? $dataJadwal ?? null;

                if (empty($daftarJadwal) || (is_countable($daftarJadwal) && count($daftarJadwal) === 0)) {
                    $daftarJadwal = \App\Models\JadwalTayang::withCount('fileIklan')->orderBy('nama_jadwal', 'asc')->get();
                }

                // Petakan jadwal untuk pencarian cepat
                $jadwalMap = [];
                foreach ($daftarJadwal as $jItem) {
                    $namaJ = $jItem->nama_jadwal ?? $jItem->nama ?? ('Jadwal #' . $jItem->id);
                    $vidCount = $jItem->file_iklan_count ?? ($jItem->relationLoaded('fileIklan') ? $jItem->fileIklan->count() : null);
                    $jadwalMap[$jItem->id] = [
                        'id' => $jItem->id,
                        'nama' => $namaJ,
                        'video_count' => $vidCount,
                        'full_label' => $namaJ . ($vidCount !== null ? ' (' . $vidCount . ' video)' : ''),
                    ];
                }

                $oldJadwal  = old('jadwal_tayang_id', session('distribusi_step1.jadwal_tayang_id', []));
                $oldMulai   = old('waktu_mulai', session('distribusi_step1.waktu_mulai', []));
                $oldSelesai = old('waktu_selesai', session('distribusi_step1.waktu_selesai', []));
                $oldSlots   = old('slots', session('distribusi_step1.slots', session('distribusi_draft.slots', [])));
            @endphp

            @for ($i = 0; $i < 6; $i++)
                @php
                    $defaultMulai   = ($i === 1) ? '11:00' : '00:00';
                    $defaultSelesai = ($i === 1) ? '12:00' : '23:59';

                    $valJadwal  = $oldJadwal[$i] ?? ($oldSlots[$i]['jadwal_tayang_id'] ?? '');
                    $valMulai   = $oldMulai[$i] ?? ($oldSlots[$i]['waktu_mulai'] ?? $defaultMulai);
                    $valSelesai = $oldSelesai[$i] ?? ($oldSlots[$i]['waktu_selesai'] ?? $defaultSelesai);
                    $selectedText = isset($jadwalMap[$valJadwal]) ? $jadwalMap[$valJadwal]['full_label'] : '';
                @endphp
                <div class="jadwal-row-item" id="row-jadwal-{{ $i }}">
                    <span class="jadwal-row-number">#{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}:</span>

                    <!-- Searchable Select Box -->
                    <div class="jadwal-select-wrap" id="wrap-jadwal-{{ $i }}" data-row="{{ $i }}">
                        <div class="jadwal-search-box">
                            <i class="fas fa-search jadwal-search-icon"></i>
                            <input type="text"
                                   id="search_input_{{ $i }}"
                                   class="jadwal-search-input"
                                   value="{{ $selectedText }}"
                                   placeholder="-- Ketik atau klik untuk mencari jadwal --"
                                   autocomplete="off"
                                   onfocus="openJadwalDropdown({{ $i }})"
                                   oninput="filterJadwalDropdown({{ $i }})"
                                   onkeydown="handleJadwalKeydown(event, {{ $i }})">
                            <i class="fas fa-chevron-down jadwal-search-chevron"></i>
                        </div>

                        <!-- Hidden Inputs untuk dikirim ke Controller -->
                        <input type="hidden" name="jadwal_tayang_id[{{ $i }}]" id="hidden_jadwal_{{ $i }}" value="{{ $valJadwal }}">
                        <input type="hidden" name="slots[{{ $i }}][jadwal_tayang_id]" id="slot_jadwal_{{ $i }}" value="{{ $valJadwal }}">

                        <!-- Dropdown Daftar Pilihan -->
                        <div class="jadwal-dropdown-menu" id="dropdown_menu_{{ $i }}">
                            <div class="jadwal-dropdown-item"
                                 data-id=""
                                 data-label=""
                                 onclick="selectJadwalOption({{ $i }}, '', '')">
                                <span style="color: #94a3b8; font-style: italic;">-- Kosongkan pilihan --</span>
                            </div>
                            @foreach ($jadwalMap as $j)
                                <div class="jadwal-dropdown-item {{ (string)$valJadwal === (string)$j['id'] ? 'selected-item' : '' }}"
                                     data-id="{{ $j['id'] }}"
                                     data-label="{{ $j['full_label'] }}"
                                     data-search="{{ strtolower($j['full_label']) }}"
                                     onclick="selectJadwalOption({{ $i }}, '{{ $j['id'] }}', '{{ addslashes($j['full_label']) }}')">
                                    <span>{{ $j['nama'] }}</span>
                                    @if ($j['video_count'] !== null)
                                        <span class="jadwal-video-badge">{{ $j['video_count'] }} video</span>
                                    @endif
                                </div>
                            @endforeach
                            <div class="jadwal-dropdown-empty" id="dropdown_empty_{{ $i }}">
                                Jadwal tidak ditemukan
                            </div>
                        </div>
                    </div>

                    <div class="jadwal-time-group">
                        <label class="jadwal-time-label">
                            Jam Tayang <span style="color: #ef4444;">**</span>
                        </label>
                        <input type="text"
                               name="waktu_mulai[{{ $i }}]"
                               id="input_mulai_{{ $i }}"
                               value="{{ $valMulai }}"
                               class="jadwal-time-input"
                               placeholder="00:00"
                               maxlength="5"
                               oninput="syncSlotInputs({{ $i }})">
                        <input type="hidden" name="slots[{{ $i }}][waktu_mulai]" id="slot_mulai_{{ $i }}" value="{{ $valMulai }}">

                        <span class="jadwal-time-sep">-</span>

                        <input type="text"
                               name="waktu_selesai[{{ $i }}]"
                               id="input_selesai_{{ $i }}"
                               value="{{ $valSelesai }}"
                               class="jadwal-time-input"
                               placeholder="23:59"
                               maxlength="5"
                               oninput="syncSlotInputs({{ $i }})">
                        <input type="hidden" name="slots[{{ $i }}][waktu_selesai]" id="slot_selesai_{{ $i }}" value="{{ $valSelesai }}">
                    </div>

                    <button type="button" class="btn-clear-row" onclick="resetJadwalRow({{ $i }})" title="Reset baris ini">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </div>
            @endfor
        </div>

        <!-- Frekuensi Hari -->
        @php
            $freqVal = old('frekuensi_hari', session('distribusi_step1.frekuensi_hari', 'Setiap Hari'));
        @endphp
        <div class="freq-section">
            <label class="dist-label">Frekuensi Hari <span style="color: #ef4444;">*</span></label>
            <div class="freq-options">
                @foreach (['Setiap Hari', 'Senin-Jumat', 'Sabtu-Minggu'] as $opsi)
                    <label class="freq-pill">
                        <input type="radio" name="frekuensi_hari" value="{{ $opsi }}" {{ $freqVal === $opsi ? 'checked' : '' }}>
                        <span>{{ $opsi }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="dist-actions">
            <a href="{{ route('distribusi.index') }}" class="btn-batal">Batal</a>
            <button type="submit" class="btn-lanjut">
                <span>Pilih Lokasi Tayang</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </form>
</div>

<script>
    function closeAllJadwalDropdowns(exceptIndex = null) {
        document.querySelectorAll('.jadwal-select-wrap').forEach((wrap) => {
            const idx = parseInt(wrap.getAttribute('data-row'), 10);
            if (idx !== exceptIndex) {
                wrap.classList.remove('open');
                restoreValidSelectionText(idx);
            }
        });
    }

    function openJadwalDropdown(index) {
        closeAllJadwalDropdowns(index);
        const wrap = document.getElementById('wrap-jadwal-' + index);
        const input = document.getElementById('search_input_' + index);
        if (!wrap || !input) return;

        wrap.classList.add('open');
        input.select();
        filterJadwalDropdown(index, true);
    }

    function filterJadwalDropdown(index, showAll = false) {
        const wrap = document.getElementById('wrap-jadwal-' + index);
        const input = document.getElementById('search_input_' + index);
        const menu = document.getElementById('dropdown_menu_' + index);
        const emptyMsg = document.getElementById('dropdown_empty_' + index);
        if (!wrap || !input || !menu) return;

        wrap.classList.add('open');
        const keyword = showAll ? '' : input.value.trim().toLowerCase();

        // Jika teks input dihapus oleh user, kosongkan juga ID tersembunyinya
        if (input.value.trim() === '') {
            document.getElementById('hidden_jadwal_' + index).value = '';
            document.getElementById('slot_jadwal_' + index).value = '';
        }

        let visibleCount = 0;
        const items = menu.querySelectorAll('.jadwal-dropdown-item');

        items.forEach((item) => {
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

    function selectJadwalOption(index, id, label) {
        const wrap = document.getElementById('wrap-jadwal-' + index);
        const input = document.getElementById('search_input_' + index);
        const hiddenJadwal = document.getElementById('hidden_jadwal_' + index);
        const slotJadwal = document.getElementById('slot_jadwal_' + index);
        const menu = document.getElementById('dropdown_menu_' + index);

        if (input) {
            input.value = label;
            input.setAttribute('data-selected-label', label);
        }
        if (hiddenJadwal) hiddenJadwal.value = id;
        if (slotJadwal) slotJadwal.value = id;

        if (menu) {
            menu.querySelectorAll('.jadwal-dropdown-item').forEach((el) => {
                if (el.getAttribute('data-id') === String(id) && id !== '') {
                    el.classList.add('selected-item');
                } else {
                    el.classList.remove('selected-item');
                }
            });
        }

        if (wrap) wrap.classList.remove('open');
        syncSlotInputs(index);
    }

    function restoreValidSelectionText(index) {
        const input = document.getElementById('search_input_' + index);
        const hiddenJadwal = document.getElementById('hidden_jadwal_' + index);
        const menu = document.getElementById('dropdown_menu_' + index);
        if (!input || !hiddenJadwal || !menu) return;

        const currentId = hiddenJadwal.value;
        if (!currentId) {
            input.value = '';
            return;
        }

        const matched = menu.querySelector('.jadwal-dropdown-item[data-id="' + currentId + '"]');
        if (matched) {
            input.value = matched.getAttribute('data-label') || '';
        }
    }

    function handleJadwalKeydown(e, index) {
        const wrap = document.getElementById('wrap-jadwal-' + index);
        const menu = document.getElementById('dropdown_menu_' + index);
        if (!wrap || !menu) return;

        if (e.key === 'Escape') {
            wrap.classList.remove('open');
            restoreValidSelectionText(index);
            return;
        }

        if (e.key === 'Enter') {
            e.preventDefault();
            const firstVisible = Array.from(menu.querySelectorAll('.jadwal-dropdown-item')).find(
                (el) => el.style.display !== 'none' && el.getAttribute('data-id') !== ''
            );
            if (firstVisible) {
                selectJadwalOption(
                    index,
                    firstVisible.getAttribute('data-id'),
                    firstVisible.getAttribute('data-label')
                );
            }
        }
    }

    function syncSlotInputs(index) {
        const hiddenJadwal = document.getElementById('hidden_jadwal_' + index);
        const mulai = document.getElementById('input_mulai_' + index);
        const selesai = document.getElementById('input_selesai_' + index);

        const slotJadwal = document.getElementById('slot_jadwal_' + index);
        const slotMulai = document.getElementById('slot_mulai_' + index);
        const slotSelesai = document.getElementById('slot_selesai_' + index);

        if (slotJadwal && hiddenJadwal) slotJadwal.value = hiddenJadwal.value;
        if (slotMulai && mulai) slotMulai.value = mulai.value;
        if (slotSelesai && selesai) slotSelesai.value = selesai.value;
    }

    function resetJadwalRow(index) {
        selectJadwalOption(index, '', '');

        const mulai = document.getElementById('input_mulai_' + index);
        const selesai = document.getElementById('input_selesai_' + index);
        if (mulai) mulai.value = index === 1 ? '11:00' : '00:00';
        if (selesai) selesai.value = index === 1 ? '12:00' : '23:59';

        syncSlotInputs(index);
    }

    // Tutup dropdown saat klik di luar area
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.jadwal-select-wrap')) {
            closeAllJadwalDropdowns();
        }
    });
</script>
@endsection