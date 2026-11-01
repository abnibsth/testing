<?php $__env->startSection('title', 'Digital Signage'); ?>
<?php $__env->startSection('page-title', 'Iklan > Digital Signage'); ?>



<?php $__env->startSection('content'); ?>


<style>
    /* ==========================================================================
       ✨ VORTEXSIGN PREMIUM CLEAN TABLE LAYOUT ✨
       ========================================================================== */
    .vortex-card-index {
        background: var(--bg-card, #ffffff) !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02) !important;
        border: 1px solid var(--border-main, #e9ecef) !important;
        overflow: hidden !important;
        width: 100% !important;
        display: block !important;
        margin-top: 10px;
    }

    .toolbar-vortex {
        display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; justify-content: space-between; align-items: center;
    }

    /* Tabel Melebar Penuh & Simetris */
    .table-vortex {
        width: 100% !important;
        min-width: 100% !important;
        display: table !important;
        border-collapse: collapse !important;
        table-layout: fixed !important;
        margin: 0 !important;
    }

    .table-vortex th {
        background-color: var(--bg-table-th, #f8f9fa) !important;
        color: var(--text-main, #2c3e50) !important;
        font-weight: 800 !important;
        border-bottom: 2px solid var(--border-main, #e9ecef) !important;
        text-transform: uppercase !important;
        font-size: 0.75rem !important;
        letter-spacing: 0.8px !important;
        padding: 16px 16px !important;
        vertical-align: middle !important;
    }

    .table-vortex td {
        padding: 14px 16px !important;
        border-bottom: 1px solid var(--border-table, #cbd5e1) !important;
        color: var(--text-main, #2c3e50) !important;
        font-weight: 600 !important;
        font-size: 0.85rem !important;
        vertical-align: middle !important;
    }

    .table-vortex tbody tr:hover td { background-color: rgba(114, 200, 101, 0.03) !important; }

    /* Tombol Aksi */
    .btn-icon-square {
        width: 36px; height: 36px; padding: 0;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 8px; color: white !important; font-size: 14px;
        transition: all 0.2s; border: none; text-decoration: none;
    }
    .btn-edit-vortex { background-color: #f39c12; }
    .btn-edit-vortex:hover { background-color: #d68910; color: white; }
    .btn-delete-vortex { background-color: #e74c3c; }
    .btn-delete-vortex:hover { background-color: #c0392b; color: white; }

    /* Badges */
    .badge-vortex-soft { background-color: rgba(44, 62, 80, 0.1); color: #2c3e50; padding: 4px 8px; border-radius: 6px; font-weight: 700; font-size: 0.75rem; }
    .badge-vortex-green { background-color: rgba(114, 200, 101, 0.15); color: #2e7d32; padding: 4px 8px; border-radius: 6px; font-weight: 700; font-size: 0.75rem; }
    .badge-vortex-cyan { background-color: rgba(54, 185, 204, 0.1); color: #36b9cc; padding: 4px 8px; border-radius: 6px; font-weight: 700; font-size: 0.75rem; }
</style>

<div class="toolbar-vortex">
    <a href="<?php echo e(route('signage.create')); ?>" class="btn btn-primary" style="background-color: #2c3e50; border-color: #2c3e50; padding: 10px 18px; border-radius: 8px;">
        <i class="fas fa-plus mr-2"></i> Tambah Signage
    </a>
    <form class="search-form" method="GET" style="display: flex; gap: 8px; flex: 1; max-width: 650px; flex-wrap: wrap; justify-content: flex-end;">
        <?php if(request('area')): ?>
            <input type="hidden" name="area" value="<?php echo e(request('area')); ?>">
        <?php endif; ?>
        <?php if(request('baru_aktivasi')): ?>
            <input type="hidden" name="baru_aktivasi" value="<?php echo e(request('baru_aktivasi')); ?>">
        <?php endif; ?>
        <select name="status_konten" class="form-control" onchange="this.form.submit()" style="max-width: 200px;">
            <option value="">-- Semua Status Konten --</option>
            <?php $__currentLoopData = ['Sudah Terbaru', 'Belum Terbaru', 'Tidak Diketahui']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($sk); ?>" <?php echo e(request('status_konten') === $sk ? 'selected' : ''); ?>><?php echo e($sk); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <input type="text" name="search" class="search-input form-control" style="max-width: 260px;"
               placeholder="Cari ID, nama, atau area..." value="<?php echo e(request('search')); ?>">
        <button type="submit" class="btn btn-secondary" style="padding: 8px 16px; border-radius: 8px; background-color: #b2bec3; border-color: #b2bec3;">
            <i class="fas fa-search"></i>
        </button>
    </form>
</div>

<?php if(request()->anyFilled(['area', 'status_konten', 'baru_aktivasi', 'search'])): ?>
<div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap; margin-bottom: 16px;">
    <span style="font-size: 12px; font-weight: 700; color: var(--text-muted);">Filter Aktif:</span>
    <?php if(request('area')): ?>
        <span class="badge-vortex-cyan">Area: <?php echo e(request('area')); ?></span>
    <?php endif; ?>
    <?php if(request('status_konten')): ?>
        <span class="<?php echo e(request('status_konten') === 'Sudah Terbaru' ? 'badge-vortex-green' : 'badge-vortex-soft'); ?>" style="<?php echo e(request('status_konten') === 'Belum Terbaru' ? 'background:#fff8e1;color:#f59e0b;' : ''); ?>">
            Status: <?php echo e(request('status_konten')); ?>

        </span>
    <?php endif; ?>
    <?php if(request('baru_aktivasi')): ?>
        <span class="badge-vortex-green">Baru Aktivasi (30 Hari Terakhir)</span>
    <?php endif; ?>
    <?php if(request('search')): ?>
        <span class="badge-vortex-soft">Pencarian: "<?php echo e(request('search')); ?>"</span>
    <?php endif; ?>
    <a href="<?php echo e(route('signage.index')); ?>" style="font-size: 12px; font-weight: 800; color: #e74c3c; text-decoration: none; margin-left: 6px;">
        <i class="fas fa-times-circle"></i> Reset Filter
    </a>
</div>
<?php endif; ?>

<?php if(session('success')): ?>
<div class="alert alert-success" style="background: #e8f8e7; color: #2e6e25; border-left: 4px solid #72c865; padding: 12px 18px; border-radius: 10px; font-weight: 600; margin-bottom: 20px;">
    <i class="fas fa-check-circle mr-2"></i> <?php echo e(session('success')); ?>

</div>
<?php endif; ?>

<div class="vortex-card-index">
    <div class="table-responsive" style="width: 100%;">
        <table class="table-vortex">
            <thead>
                <tr>
                    <th style="width: 25%;">Info Perangkat</th>
                    <th style="width: 20%;">Toko Penempatan</th>
                    <th style="width: 15%;">Model TV</th>
                    <th style="width: 15%;">Status Konten</th>
                    <th style="width: 10%;">Aktivasi</th>
                    <th style="width: 15%; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $signages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <strong style="color: #2c3e50; font-size: 14px;"><?php echo e($item->nama_signage); ?></strong><br>
                        <small style="color: var(--text-muted);">ID: <?php echo e($item->device_id); ?></small><br>
                        <?php if($item->status_koneksi == 'Online'): ?>
                            <span class="badge-vortex-green"><i class="fas fa-wifi mr-1"></i> Online</span>
                        <?php else: ?>
                            <span class="badge-vortex-soft"><i class="fas fa-wifi mr-1"></i> Offline</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div style="font-weight: 700;"><?php echo e($item->toko->nama_toko ?? '-'); ?></div>
                        <small style="color: var(--text-muted);"><?php echo e($item->toko->kode_toko ?? ''); ?><?php echo e(!empty($item->toko->area) ? ' • ' . $item->toko->area : ''); ?></small>
                    </td>
                    <td><span class="badge-vortex-cyan"><?php echo e($item->modelSignage->nama_model_tv ?? '-'); ?></span></td>
                    <td>
                        <?php if(($item->status_konten ?? '') == 'Sudah Terbaru'): ?>
                            <span class="badge-vortex-green">Sudah Terbaru</span>
                        <?php else: ?>
                            <span class="badge-vortex-soft" style="background: #fff8e1; color: #f59e0b;"><?php echo e($item->status_konten ?? 'Belum Terbaru'); ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($item->tanggal_aktivasi ? $item->tanggal_aktivasi->format('d/m/Y') : '-'); ?></td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 8px; justify-content: center;">
                            <a href="<?php echo e(route('signage.edit', $item)); ?>" class="btn-icon-square btn-edit-vortex"><i class="fas fa-edit"></i></a>
                            <form action="<?php echo e(route('signage.destroy', $item)); ?>" method="POST" style="display:inline;" onsubmit="return confirm('Hapus signage ini?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn-icon-square btn-delete-vortex"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
                        <i class="fas fa-desktop" style="font-size: 30px; opacity: 0.3; display: block; margin-bottom: 10px;"></i>
                        Belum ada data signage.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div style="padding: 16px 24px;"><?php echo e($signages->links()); ?></div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/novasgn/domains/vortexsign.web.id/signage/resources/views/signage/index.blade.php ENDPATH**/ ?>