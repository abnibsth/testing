<?php $__env->startSection('title', 'Edit Distribusi Jadwal Tayang'); ?>

<?php $__env->startSection('content'); ?>
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

    /* Section Judul Jadwal & Tombol Tambah */
    .dist-section-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 14px;
    }

    .dist-section-title {
        font-size: 0.98rem;
        font-weight: 700;
        color: var(--text-main, #1e293b);
        margin-bottom: 2px;
    }

    .dist-section-desc {
        font-size: 0.85rem;
        color: var(--text-muted, #64748b);
    }

    [data-theme="dark"] .dist-section-desc,
    body.dark .dist-section-desc {
        color: #94a3b8 !important;
    }

    .btn-add-row {
        padding: 7px 14px;
        border-radius: 8px;
        border: 1px solid #14b8a6;
        background-color: rgba(20, 184, 166, 0.12);
        color: #14b8a6;
        font-size: 0.82rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
    }

    .btn-add-row:hover {
        background-color: #14b8a6;
        color: #ffffff;
    }

    /* Baris Item Jadwal */
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

    [data-theme="dark"] .jadwal-search-input,
    body.dark .jadwal-search-input {
        background-color: #243447 !important;
        border-color: #384d66 !important;
        color: #f8fafc !important;
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

    .jadwal-dropdown-item:hover {
        background-color: rgba(56, 189, 248, 0.15);
        color: #0284c7;
    }

    [data-theme="dark"] .jadwal-dropdown-item:hover,
    body.dark .jadwal-dropdown-item:hover {
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

    /* Bagian Jam Tayang */
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

    /* Tombol Hapus Baris */
    .btn-clear-row {
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

    .btn-clear-row:hover {
        background: #ef4444;
        color: #ffffff;
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
    }

    [data-theme="dark"] .btn-batal,
    body.dark .btn-batal {
        border-color: #475569 !important;
        color: #cbd5e1 !important;
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
    }

    @media (max-width: 768px) {
        .jadwal-row-item { flex-wrap: wrap; }
        .jadwal-select-wrap { width: 100%; flex: none; }
        .jadwal-time-group { width: 100%; justify-content: space-between; }
    }
</style>

<div class="dist-breadcrumb">
    Iklan &gt; Distribusi &gt; Edit
</div>

<?php if($errors->any()): ?>
    <div style="background: rgba(239, 68, 68, 0.12); border: 1px solid #ef4444; color: #ef4444; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 0.9rem;">
        <ul style="margin: 0; padding-left: 18px;">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<?php
    $distItem = $distribusi ?? $distribusiJadwal ?? null;
    $daftarJadwal = $jadwal ?? $jadwals ?? $jadwalTayang ?? $jadwalTayangs ?? $jadwalList ?? null;

    if (empty($daftarJadwal) || (is_countable($daftarJadwal) && count($daftarJadwal) === 0)) {
        $daftarJadwal = \App\Models\JadwalTayang::withCount('fileIklan')->orderBy('nama_jadwal', 'asc')->get();
    }

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

    $oldJadwal  = old('jadwal_tayang_id', isset($distItem->jadwal_tayang_id) ? [$distItem->jadwal_tayang_id] : []);
    $oldMulai   = old('waktu_mulai', isset($distItem->waktu_mulai) ? [$distItem->waktu_mulai] : ['00:00']);
    $oldSelesai = old('waktu_selesai', isset($distItem->waktu_selesai) ? [$distItem->waktu_selesai] : ['23:59']);

    $oldJadwal  = is_array($oldJadwal) ? $oldJadwal : [$oldJadwal];
    $oldMulai   = is_array($oldMulai) ? $oldMulai : [$oldMulai];
    $oldSelesai = is_array($oldSelesai) ? $oldSelesai : [$oldSelesai];

    $rowCount = max(1, count($oldJadwal));
?>

<div class="dist-card">
    <div class="dist-header">
        <div class="dist-icon-box">
            <i class="fas fa-edit"></i>
        </div>
        <h2 class="dist-header-title">Edit Distribusi Jadwal Tayang</h2>
    </div>

    <form action="<?php echo e(route('distribusi.update', $distItem->id ?? 0)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="dist-date-grid">
            <div>
                <label class="dist-label">Tanggal Mulai <span style="color: #ef4444;">*</span></label>
                <input type="date" name="tanggal_mulai" class="dist-input"
                       value="<?php echo e(old('tanggal_mulai', isset($distItem->tanggal_mulai) ? \Carbon\Carbon::parse($distItem->tanggal_mulai)->format('Y-m-d') : date('Y-m-d'))); ?>" required>
            </div>
            <div>
                <label class="dist-label">Tanggal Selesai <span style="color: #ef4444;">*</span></label>
                <input type="date" name="tanggal_selesai" class="dist-input"
                       value="<?php echo e(old('tanggal_selesai', isset($distItem->tanggal_selesai) ? \Carbon\Carbon::parse($distItem->tanggal_selesai)->format('Y-m-d') : date('Y-m-d', strtotime('+1 year')))); ?>" required>
            </div>
        </div>

        <div>
            <div class="dist-section-top">
                <div>
                    <div class="dist-section-title">Nama Jadwal Tayang &amp; Jam Tayang</div>
                    <div class="dist-section-desc">Pilih atau ketik untuk mencari jadwal tayang dan tentukan jam tayangnya.</div>
                </div>
                <button type="button" class="btn-add-row" onclick="addNewJadwalRow()">
                    <i class="fas fa-plus"></i>
                    <span>TAMBAH JADWAL</span>
                </button>
            </div>

            <div id="jadwalRowsContainer">
                <?php for($i = 0; $i < $rowCount; $i++): ?>
                    <?php
                        $valJadwal = $oldJadwal[$i] ?? '';
                        $valMulai  = $oldMulai[$i] ?? '00:00';
                        $valSelesai = $oldSelesai[$i] ?? '23:59';
                        $selectedText = isset($jadwalMap[$valJadwal]) ? $jadwalMap[$valJadwal]['full_label'] : '';
                    ?>
                    <div class="jadwal-row-item" id="row-jadwal-<?php echo e($i); ?>" data-row-index="<?php echo e($i); ?>">
                        <span class="jadwal-row-number">#<?php echo e(str_pad($i + 1, 2, '0', STR_PAD_LEFT)); ?>:</span>

                        <div class="jadwal-select-wrap" id="wrap-jadwal-<?php echo e($i); ?>" data-row="<?php echo e($i); ?>">
                            <div class="jadwal-search-box">
                                <i class="fas fa-search jadwal-search-icon"></i>
                                <input type="text"
                                       id="search_input_<?php echo e($i); ?>"
                                       class="jadwal-search-input"
                                       value="<?php echo e($selectedText); ?>"
                                       placeholder="-- Ketik atau klik untuk mencari jadwal --"
                                       autocomplete="off"
                                       onfocus="openJadwalDropdown(<?php echo e($i); ?>)"
                                       oninput="filterJadwalDropdown(<?php echo e($i); ?>)"
                                       onkeydown="handleJadwalKeydown(event, <?php echo e($i); ?>)">
                                <i class="fas fa-chevron-down jadwal-search-chevron"></i>
                            </div>

                            <input type="hidden" name="jadwal_tayang_id[]" id="hidden_jadwal_<?php echo e($i); ?>" value="<?php echo e($valJadwal); ?>">

                            <div class="jadwal-dropdown-menu" id="dropdown_menu_<?php echo e($i); ?>">
                                <div class="jadwal-dropdown-item" data-id="" data-label="" onclick="selectJadwalOption(<?php echo e($i); ?>, '', '')">
                                    <span style="color: #94a3b8; font-style: italic;">-- Pilih Jadwal --</span>
                                </div>
                                <?php $__currentLoopData = $jadwalMap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="jadwal-dropdown-item <?php echo e((string)$valJadwal === (string)$j['id'] ? 'selected-item' : ''); ?>"
                                         data-id="<?php echo e($j['id']); ?>"
                                         data-label="<?php echo e($j['full_label']); ?>"
                                         data-search="<?php echo e(strtolower($j['full_label'])); ?>"
                                         onclick="selectJadwalOption(<?php echo e($i); ?>, '<?php echo e($j['id']); ?>', '<?php echo e(addslashes($j['full_label'])); ?>')">
                                        <span><?php echo e($j['nama']); ?></span>
                                        <?php if($j['video_count'] !== null): ?>
                                            <span class="jadwal-video-badge"><?php echo e($j['video_count']); ?> video</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <div class="jadwal-dropdown-empty" id="dropdown_empty_<?php echo e($i); ?>">
                                    Jadwal tidak ditemukan
                                </div>
                            </div>
                        </div>

                        <div class="jadwal-time-group">
                            <label class="jadwal-time-label">Jam <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="waktu_mulai[]" id="input_mulai_<?php echo e($i); ?>" value="<?php echo e($valMulai); ?>" class="jadwal-time-input" placeholder="00:00" maxlength="5">
                            <span class="jadwal-time-sep">-</span>
                            <input type="text" name="waktu_selesai[]" id="input_selesai_<?php echo e($i); ?>" value="<?php echo e($valSelesai); ?>" class="jadwal-time-input" placeholder="23:59" maxlength="5">
                        </div>

                        <button type="button" class="btn-clear-row" onclick="removeOrResetJadwalRow(<?php echo e($i); ?>)" title="Hapus baris">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        <?php
            $freqVal = old('frekuensi_hari', $distItem->frekuensi_hari ?? 'Setiap Hari');
        ?>
        <div class="freq-section">
            <label class="dist-label">Frekuensi Hari <span style="color: #ef4444;">*</span></label>
            <div class="freq-options">
                <?php $__currentLoopData = ['Setiap Hari', 'Senin-Jumat', 'Sabtu-Minggu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opsi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="freq-pill">
                        <input type="radio" name="frekuensi_hari" value="<?php echo e($opsi); ?>" <?php echo e($freqVal === $opsi ? 'checked' : ''); ?>>
                        <span><?php echo e($opsi); ?></span>
                    </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <div class="dist-actions">
            <a href="<?php echo e(route('distribusi.index')); ?>" class="btn-batal">Batal</a>
            <button type="submit" class="btn-simpan">
                <i class="fas fa-save"></i>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </form>
</div>

<script>
    const MASTER_JADWALS = <?php echo json_encode(array_values($jadwalMap), 15, 512) ?>;
    let nextRowIndex = <?php echo e($rowCount); ?>;

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

        if (input.value.trim() === '') {
            document.getElementById('hidden_jadwal_' + index).value = '';
        }

        let visibleCount = 0;
        menu.querySelectorAll('.jadwal-dropdown-item').forEach((item) => {
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
        const menu = document.getElementById('dropdown_menu_' + index);

        if (input) input.value = label;
        if (hiddenJadwal) hiddenJadwal.value = id;

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
        if (matched) input.value = matched.getAttribute('data-label') || '';
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
                selectJadwalOption(index, firstVisible.getAttribute('data-id'), firstVisible.getAttribute('data-label'));
            }
        }
    }

    function removeOrResetJadwalRow(index) {
        const allRows = document.querySelectorAll('#jadwalRowsContainer .jadwal-row-item');
        const row = document.getElementById('row-jadwal-' + index);
        if (!row) return;

        const hidden = document.getElementById('hidden_jadwal_' + index);
        if (hidden && hidden.value !== '') {
            selectJadwalOption(index, '', '');
            return;
        }
        if (allRows.length > 1) {
            row.remove();
            renumberJadwalRows();
        } else {
            selectJadwalOption(index, '', '');
        }
    }

    function renumberJadwalRows() {
        document.querySelectorAll('#jadwalRowsContainer .jadwal-row-item').forEach((row, idx) => {
            const numSpan = row.querySelector('.jadwal-row-number');
            if (numSpan) numSpan.textContent = '#' + String(idx + 1).padStart(2, '0') + ':';
        });
    }

    function escapeHtml(str) {
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    function addNewJadwalRow() {
        const container = document.getElementById('jadwalRowsContainer');
        const idx = nextRowIndex++;
        const currentCount = container.querySelectorAll('.jadwal-row-item').length + 1;

        let optionsHtml = `<div class="jadwal-dropdown-item" data-id="" data-label="" onclick="selectJadwalOption(${idx}, '', '')"><span style="color: #94a3b8; font-style: italic;">-- Pilih Jadwal --</span></div>`;
        MASTER_JADWALS.forEach((j) => {
            const safeLabelAttr = escapeHtml(j.full_label);
            const safeLabelJs = j.full_label.replace(/\\/g, '\\\\').replace(/'/g, "\\'");
            const badgeHtml = j.video_count !== null ? `<span class="jadwal-video-badge">${j.video_count} video</span>` : '';
            optionsHtml += `<div class="jadwal-dropdown-item" data-id="${j.id}" data-label="${safeLabelAttr}" data-search="${escapeHtml(String(j.full_label).toLowerCase())}" onclick="selectJadwalOption(${idx}, '${j.id}', '${safeLabelJs}')"><span>${escapeHtml(j.nama)}</span>${badgeHtml}</div>`;
        });

        const rowDiv = document.createElement('div');
        rowDiv.className = 'jadwal-row-item';
        rowDiv.id = 'row-jadwal-' + idx;
        rowDiv.setAttribute('data-row-index', idx);
        rowDiv.innerHTML = `
            <span class="jadwal-row-number">#${String(currentCount).padStart(2, '0')}:</span>
            <div class="jadwal-select-wrap" id="wrap-jadwal-${idx}" data-row="${idx}">
                <div class="jadwal-search-box">
                    <i class="fas fa-search jadwal-search-icon"></i>
                    <input type="text" id="search_input_${idx}" class="jadwal-search-input" value="" placeholder="-- Ketik atau klik untuk mencari jadwal --" autocomplete="off" onfocus="openJadwalDropdown(${idx})" oninput="filterJadwalDropdown(${idx})" onkeydown="handleJadwalKeydown(event, ${idx})">
                    <i class="fas fa-chevron-down jadwal-search-chevron"></i>
                </div>
                <input type="hidden" name="jadwal_tayang_id[]" id="hidden_jadwal_${idx}" value="">
                <div class="jadwal-dropdown-menu" id="dropdown_menu_${idx}">
                    ${optionsHtml}
                    <div class="jadwal-dropdown-empty" id="dropdown_empty_${idx}">Jadwal tidak ditemukan</div>
                </div>
            </div>
            <div class="jadwal-time-group">
                <label class="jadwal-time-label">Jam <span style="color: #ef4444;">*</span></label>
                <input type="text" name="waktu_mulai[]" id="input_mulai_${idx}" value="00:00" class="jadwal-time-input" placeholder="00:00" maxlength="5">
                <span class="jadwal-time-sep">-</span>
                <input type="text" name="waktu_selesai[]" id="input_selesai_${idx}" value="23:59" class="jadwal-time-input" placeholder="23:59" maxlength="5">
            </div>
            <button type="button" class="btn-clear-row" onclick="removeOrResetJadwalRow(${idx})" title="Hapus baris">
                <i class="fas fa-trash"></i>
            </button>
        `;
        container.appendChild(rowDiv);
    }

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.jadwal-select-wrap')) {
            closeAllJadwalDropdowns();
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/novasgn/domains/vortexsign.web.id/signage/resources/views/distribusi/edit.blade.php ENDPATH**/ ?>