<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'WELCOME TO VORTEXSIGN DASHBOARD'); ?>

<?php $__env->startSection('content'); ?>

<style>
    /* 🎨 VORTEXSIGN VIBRANT THEME CORE */
    .pastel-card {
        background: var(--bg-card); border-radius: 12px; padding: 20px;
        border: 1px solid var(--border-main); transition: transform 0.2s ease;
        text-decoration: none; display: block;
    }
    .pastel-card:hover { transform: translateY(-2px); text-decoration: none; }
    .pastel-card-header { font-weight: 700; color: var(--text-main); font-size: 1.1rem; padding-bottom: 15px; display: flex; align-items: center; gap: 8px; }

    .stat-wrap { display: flex; align-items: center; }
    .stat-icon-box {
        width: 56px; height: 56px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; margin-right: 16px; flex-shrink: 0;
    }
    .stat-text .num { font-size: 1.8rem; font-weight: 800; color: var(--text-main); line-height: 1.2; }
    .stat-text .label { font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; }

    /* Hover Table & Clickable Links */
    .tr-hover-effect:hover td { background-color: rgba(114, 200, 101, 0.04) !important; }
    .rekap-link {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 5px 12px; border-radius: 6px; text-decoration: none !important;
        transition: all 0.15s ease; cursor: pointer;
    }
    .rekap-link:hover {
        transform: translateY(-1px);
        filter: brightness(0.92);
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }
    .rekap-link-green { color: #72c865 !important; background: rgba(114, 200, 101, 0.12); font-weight: 800; }
    .rekap-link-orange { color: #f39c12 !important; background: rgba(243, 156, 18, 0.12); font-weight: 800; }
    .rekap-link-silver { color: #7f8c8d !important; background: rgba(178, 190, 195, 0.15); font-weight: 800; }
    .rekap-link-toko { background: var(--bg-table-th); color: var(--text-main) !important; font-weight: 800; font-size: 13px; }
    .rekap-link-area { color: var(--color-primary) !important; font-weight: 800; text-decoration: none !important; }
    .rekap-link-area:hover { text-decoration: underline !important; }

    /* Palette */
    .bg-vortex-navy { background: rgba(44, 62, 80, 0.1); color: #2c3e50; }
    .bg-vortex-orange { background: rgba(243, 156, 18, 0.1); color: #f39c12; }
    .bg-vortex-green { background: rgba(114, 200, 101, 0.1); color: #72c865; }
    .bg-vortex-silver { background: rgba(178, 190, 195, 0.1); color: #b2bec3; }
    .bg-vortex-red { background: rgba(231, 76, 60, 0.1); color: #e74c3c; }
    [data-theme="dark"] .bg-vortex-navy { background: rgba(236, 240, 241, 0.1); color: #ecf0f1; }
</style>

<div id="vortex-data-bridge" style="display: none;"
     data-toko-labels="<?php echo e(json_encode($chartData->pluck('area'))); ?>"
     data-toko-values="<?php echo e(json_encode($chartData->pluck('total'))); ?>"
     data-sudah-update="<?php echo e((int)$sudahUpdate); ?>"
     data-belum-update="<?php echo e((int)$belumUpdate); ?>"
     data-tidak-diketahui="<?php echo e((int)$tidakDiketahui); ?>"
     data-online="<?php echo e((int)$signageOnline); ?>"
     data-offline="<?php echo e((int)$signageOffline); ?>">
</div>


<div style="display:flex; gap:20px; flex-wrap:wrap; margin-bottom:24px; width:100%;">
    <a href="<?php echo e(route('toko.index')); ?>" class="pastel-card" style="flex:1; min-width:200px;" title="Lihat Semua Toko">
        <div class="stat-wrap">
            <div class="stat-icon-box bg-vortex-navy"><i class="fas fa-store"></i></div>
            <div class="stat-text"><div class="label">Total Toko</div><div class="num"><?php echo e($totalToko); ?></div></div>
        </div>
    </a>
    <a href="<?php echo e(route('signage.index')); ?>" class="pastel-card" style="flex:1; min-width:200px;" title="Lihat Semua Signage">
        <div class="stat-wrap">
            <div class="stat-icon-box bg-vortex-silver"><i class="fas fa-desktop"></i></div>
            <div class="stat-text"><div class="label">Total Signage</div><div class="num"><?php echo e($totalSignage); ?></div></div>
        </div>
    </a>
    <a href="<?php echo e(route('signage.index', ['status_konten' => 'Sudah Terbaru'])); ?>" class="pastel-card" style="flex:1; min-width:200px;" title="Lihat Signage yang Sudah Update">
        <div class="stat-wrap">
            <div class="stat-icon-box bg-vortex-green"><i class="fas fa-sync-alt"></i></div>
            <div class="stat-text"><div class="label">Sudah Update</div><div class="num" style="color: #72c865 !important;"><?php echo e($sudahUpdate); ?></div></div>
        </div>
    </a>
    <a href="<?php echo e(route('signage.index', ['status_konten' => 'Belum Terbaru'])); ?>" class="pastel-card" style="flex:1; min-width:200px;" title="Lihat Signage yang Belum Update">
        <div class="stat-wrap">
            <div class="stat-icon-box bg-vortex-orange"><i class="fas fa-history"></i></div>
            <div class="stat-text"><div class="label">Belum Update</div><div class="num" style="color: #f39c12 !important;"><?php echo e($belumUpdate); ?></div></div>
        </div>
    </a>
    <a href="<?php echo e(route('signage.index', ['status_konten' => 'Tidak Diketahui'])); ?>" class="pastel-card" style="flex:1; min-width:200px;" title="Lihat Signage dengan Status Tidak Diketahui">
        <div class="stat-wrap">
            <div class="stat-icon-box bg-vortex-red"><i class="fas fa-question-circle"></i></div>
            <div class="stat-text"><div class="label">Tidak Diketahui</div><div class="num" style="color: #e74c3c !important;"><?php echo e($tidakDiketahui ?? 0); ?></div></div>
        </div>
    </a>
</div>


<div style="display:flex;gap:20px;margin-bottom:24px;flex-wrap:wrap; width:100%;">
    <div class="pastel-card" style="flex:1;min-width:300px; display:flex; flex-direction:column;">
        <div class="pastel-card-header"><i class="fas fa-map-marked-alt text-muted"></i> Distribusi Toko Area</div>
        <div style="flex:1; display:flex; align-items:center; justify-content:center; min-height:280px; position: relative;"><canvas id="areaChart"></canvas></div>
    </div>
    <div class="pastel-card" style="flex:1;min-width:300px; display:flex; flex-direction:column;">
        <div class="pastel-card-header"><i class="fas fa-photo-video text-success"></i> Status Update Konten</div>
        <div style="flex:1; display:flex; align-items:center; justify-content:center; min-height:280px; position: relative;"><canvas id="statusChart"></canvas></div>
    </div>
    <div class="pastel-card" style="flex:1;min-width:300px; display:flex; flex-direction:column;">
        <div class="pastel-card-header"><i class="fas fa-wifi text-warning"></i> Status Koneksi STB</div>
        <div style="flex:1; display:flex; align-items:center; justify-content:center; min-height:280px; position: relative;"><canvas id="koneksiChart"></canvas></div>
    </div>
</div>


<div class="pastel-card" style="padding: 0; overflow: hidden; width: 100%; display: block; clear: both;">
    <div style="padding: 24px 24px 10px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-main);">
        <div style="font-weight: 800; color: var(--text-main); font-size: 1.1rem;">
            <i class="fas fa-table text-primary" style="margin-right: 8px;"></i> Rekap Status per Area
        </div>
        <span style="background: var(--bg-table-th); color: var(--text-muted); padding: 6px 12px; font-weight: 800; border-radius: 6px; font-size: 11px;">
            MONITOR: LIVE SYNC
        </span>
    </div>
    
    <div style="width: 100%; display: block; padding-bottom: 24px;">
        <table width="100%" style="width: 100% !important; table-layout: fixed !important; border-collapse: collapse !important; margin: 0;">
            <thead>
                <tr>
                    <th style="width: 25%; text-align: left; padding: 16px 24px; background: var(--bg-table-th); color: var(--text-main); font-weight: 800; font-size: 11px; text-transform: uppercase; border-bottom: 2px solid var(--border-main);">Nama Area</th>
                    <th style="width: 15%; text-align: center; padding: 16px 10px; background: var(--bg-table-th); color: var(--text-main); font-weight: 800; font-size: 11px; text-transform: uppercase; border-bottom: 2px solid var(--border-main);">Update Berhasil</th>
                    <th style="width: 15%; text-align: center; padding: 16px 10px; background: var(--bg-table-th); color: var(--text-main); font-weight: 800; font-size: 11px; text-transform: uppercase; border-bottom: 2px solid var(--border-main);">Total Toko</th>
                    <th style="width: 15%; text-align: center; padding: 16px 10px; background: var(--bg-table-th); color: var(--text-main); font-weight: 800; font-size: 11px; text-transform: uppercase; border-bottom: 2px solid var(--border-main);">Belum Terbaru</th>
                    <th style="width: 15%; text-align: center; padding: 16px 10px; background: var(--bg-table-th); color: var(--text-main); font-weight: 800; font-size: 11px; text-transform: uppercase; border-bottom: 2px solid var(--border-main);">Tidak Diketahui</th>
                    <th style="width: 15%; text-align: right; padding: 16px 24px; background: var(--bg-table-th); color: var(--text-main); font-weight: 800; font-size: 11px; text-transform: uppercase; border-bottom: 2px solid var(--border-main);">Baru Aktivasi</th>
                </tr>
            </thead>
            <tbody>
                <?php $totalBaruAktivasi = 0; ?>
                <?php if (isset($statsPerArea) && count($statsPerArea) > 0) : ?>
                    <?php foreach ($statsPerArea as $stat) : ?>
                        <?php 
                            $row = (array) $stat; 
                            $area = $row['area'] ?? '-';
                            $sudah = (int) ($row['sudah_update'] ?? 0);
                            $totalRow = (int) ($row['total_toko'] ?? 0);
                            $belum = (int) ($row['belum_update'] ?? 0);
                            $unknown = (int) ($row['tidak_diketahui'] ?? 0);
                            $baru = (int) ($row['baru_aktivasi'] ?? 0);
                            $totalBaruAktivasi += $baru;
                        ?>
                        <tr class="tr-hover-effect">
                            <td style="text-align: left; padding: 16px 24px; border-bottom: 1px solid var(--border-table); font-size: 14px;">
                                <a href="<?php echo e(route('signage.index', ['area' => $area])); ?>" class="rekap-link-area" title="Lihat semua signage di area <?php echo e($area); ?>">
                                    <?php echo e($area); ?>
                                </a>
                            </td>
                            <td style="text-align: center; padding: 16px 10px; border-bottom: 1px solid var(--border-table); font-size: 14px;">
                                <a href="<?php echo e(route('signage.index', ['area' => $area, 'status_konten' => 'Sudah Terbaru'])); ?>" class="rekap-link rekap-link-green" title="Lihat signage Sudah Terbaru di area <?php echo e($area); ?>">
                                    <i class="fas fa-check-circle" style="margin-right: 5px;"></i><?php echo $sudah; ?>
                                </a>
                            </td>
                            <td style="text-align: center; padding: 16px 10px; border-bottom: 1px solid var(--border-table);">
                                <a href="<?php echo e(route('toko.index', ['area' => $area])); ?>" class="rekap-link rekap-link-toko" title="Lihat daftar toko di area <?php echo e($area); ?>">
                                    <?php echo $totalRow; ?>
                                </a>
                            </td>
                            <td style="text-align: center; padding: 16px 10px; border-bottom: 1px solid var(--border-table); font-size: 14px;">
                                <a href="<?php echo e(route('signage.index', ['area' => $area, 'status_konten' => 'Belum Terbaru'])); ?>" class="rekap-link rekap-link-orange" title="Lihat signage Belum Terbaru di area <?php echo e($area); ?>">
                                    <?php echo $belum; ?>
                                </a>
                            </td>
                            <td style="text-align: center; padding: 16px 10px; border-bottom: 1px solid var(--border-table); font-size: 14px;">
                                <a href="<?php echo e(route('signage.index', ['area' => $area, 'status_konten' => 'Tidak Diketahui'])); ?>" class="rekap-link rekap-link-silver" title="Lihat signage Status Tidak Diketahui di area <?php echo e($area); ?>">
                                    <?php echo $unknown; ?>
                                </a>
                            </td>
                            <td style="text-align: right; padding: 16px 24px; border-bottom: 1px solid var(--border-table);">
                                <a href="<?php echo e(route('signage.index', ['area' => $area, 'baru_aktivasi' => 1])); ?>" class="rekap-link rekap-link-green" style="font-size: 12px;" title="Lihat signage Baru Aktivasi (30 hari terakhir) di area <?php echo e($area); ?>">
                                    <?php echo $baru; ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr><td colspan="6" style="text-align:center;color:var(--text-muted);padding:30px; border-bottom: 1px solid var(--border-table);">Belum ada data area</td></tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td style="text-align: left; padding: 16px 24px; background: var(--bg-table-th); color: var(--text-main); font-weight: 800; border-top: 2px solid var(--border-main);">
                        <a href="<?php echo e(route('signage.index')); ?>" class="rekap-link-area" title="Lihat Semua Signage">GLOBAL TOTAL</a>
                    </td>
                    <td style="text-align: center; padding: 16px 10px; background: var(--bg-table-th); font-weight: 800; border-top: 2px solid var(--border-main);">
                        <a href="<?php echo e(route('signage.index', ['status_konten' => 'Sudah Terbaru'])); ?>" class="rekap-link rekap-link-green" title="Lihat Semua Signage Sudah Terbaru">
                            <?php echo e($sudahUpdate); ?>

                        </a>
                    </td>
                    <td style="text-align: center; padding: 16px 10px; background: var(--bg-table-th); font-weight: 800; border-top: 2px solid var(--border-main);">
                        <a href="<?php echo e(route('toko.index')); ?>" class="rekap-link rekap-link-toko" title="Lihat Semua Toko">
                            <?php echo e($totalToko); ?>

                        </a>
                    </td>
                    <td style="text-align: center; padding: 16px 10px; background: var(--bg-table-th); font-weight: 800; border-top: 2px solid var(--border-main);">
                        <a href="<?php echo e(route('signage.index', ['status_konten' => 'Belum Terbaru'])); ?>" class="rekap-link rekap-link-orange" title="Lihat Semua Signage Belum Terbaru">
                            <?php echo e($belumUpdate); ?>

                        </a>
                    </td>
                    <td style="text-align: center; padding: 16px 10px; background: var(--bg-table-th); font-weight: 800; border-top: 2px solid var(--border-main);">
                        <a href="<?php echo e(route('signage.index', ['status_konten' => 'Tidak Diketahui'])); ?>" class="rekap-link rekap-link-silver" title="Lihat Semua Signage Status Tidak Diketahui">
                            <?php echo e($tidakDiketahui ?? 0); ?>

                        </a>
                    </td>
                    <td style="text-align: right; padding: 16px 24px; background: var(--bg-table-th); font-weight: 800; border-top: 2px solid var(--border-main);">
                        <a href="<?php echo e(route('signage.index', ['baru_aktivasi' => 1])); ?>" class="rekap-link rekap-link-green" style="font-size: 12px;" title="Lihat Semua Signage Baru Aktivasi (30 hari terakhir)">
                            <?php echo e($totalBaruAktivasi); ?>

                        </a>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function() {
    const bridge = document.getElementById('vortex-data-bridge');
    if (!bridge) return;

    const areaLabels = JSON.parse(bridge.getAttribute('data-toko-labels') || '[]');
    const areaValues = JSON.parse(bridge.getAttribute('data-toko-values') || '[]');
    const sudahUpdate = parseInt(bridge.getAttribute('data-sudah-update') || 0);
    const belumUpdate = parseInt(bridge.getAttribute('data-belum-update') || 0);
    const tidakDiketahui = parseInt(bridge.getAttribute('data-tidak-diketahui') || 0);
    const signageOnline = parseInt(bridge.getAttribute('data-online') || 0);
    const signageOffline = parseInt(bridge.getAttribute('data-offline') || 0);

    const vColors = { green: '#72c865', orange: '#f39c12', silver: '#b2bec3', navy: '#2c3e50', cyan: '#36b9cc' };

    // Tema dibaca dari atribut; fallback ke localStorage karena layout baru memasang atribut setelah DOM siap
    function isDark() {
        const t = document.documentElement.getAttribute('data-theme') || localStorage.getItem('theme');
        return t === 'dark';
    }
    function getThemeTextColor()   { return isDark() ? '#ecf0f1' : '#2c3e50'; }
    function getThemeBorderColor() { return isDark() ? '#222d37' : '#ffffff'; }
    // Navy pada mode gelap menyatu dengan latar, pakai biru yang lebih terang
    function areaColors() { return [isDark() ? '#5d7a96' : vColors.navy, vColors.orange, vColors.green, vColors.cyan, vColors.silver]; }

    const commonOptions = {
        responsive: true, maintainAspectRatio: false, cutout: '72%',
        plugins: {
            legend: { position: 'bottom', labels: { padding: 15, usePointStyle: true, pointStyle: 'circle', font: { size: 11, family: "'Nunito', sans-serif", weight: '700' }, color: getThemeTextColor() } },
            tooltip: { backgroundColor: '#2c3e50', titleColor: '#ffffff', bodyColor: '#ecf0f1', padding: 12, usePointStyle: true }
        }
    };

    let chart1, chart2, chart3;

    if(document.getElementById('areaChart')) {
        chart1 = new Chart(document.getElementById('areaChart'), {
            type: 'doughnut', data: { labels: areaLabels, datasets: [{ data: areaValues, backgroundColor: areaColors(), borderWidth: 4, borderColor: getThemeBorderColor() }] }, options: commonOptions
        });
    }

    if(document.getElementById('statusChart')) {
        chart2 = new Chart(document.getElementById('statusChart'), {
            type: 'doughnut', data: { labels: ['Sudah Update', 'Belum Update', 'Tidak Diketahui'], datasets: [{ data: [sudahUpdate, belumUpdate, tidakDiketahui], backgroundColor: [vColors.green, vColors.orange, vColors.silver], borderWidth: 4, borderColor: getThemeBorderColor() }] }, options: commonOptions
        });
    }

    if(document.getElementById('koneksiChart')) {
        chart3 = new Chart(document.getElementById('koneksiChart'), {
            type: 'doughnut', data: { labels: ['Online', 'Offline'], datasets: [{ data: [signageOnline, signageOffline], backgroundColor: [vColors.green, vColors.silver], borderWidth: 4, borderColor: getThemeBorderColor() }] }, options: commonOptions
        });
    }

    function applyChartTheme() {
        const txt = getThemeTextColor(); const bdr = getThemeBorderColor();
        [chart1, chart2, chart3].forEach(c => {
            if (!c) return;
            c.options.plugins.legend.labels.color = txt;
            c.data.datasets[0].borderColor = bdr;
            if (c === chart1) c.data.datasets[0].backgroundColor = areaColors();
            c.update();
        });
    }

    // Sesuaikan warna saat halaman siap & setiap kali tema berganti
    applyChartTheme();
    document.addEventListener('DOMContentLoaded', applyChartTheme);
    window.addEventListener('load', applyChartTheme);
    new MutationObserver(applyChartTheme).observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
})();
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/novasgn/domains/vortexsign.web.id/signage/resources/views/dashboard/index.blade.php ENDPATH**/ ?>