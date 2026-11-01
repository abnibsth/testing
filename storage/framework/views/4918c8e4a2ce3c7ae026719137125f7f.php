<?php $__env->startSection('title', 'Distribusi Jadwal Tayang'); ?>
<?php $__env->startSection('page-title', 'Iklan > Distribusi Jadwal Tayang'); ?>

<?php $__env->startSection('content'); ?>


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
    
    .badge-vortex-green { background-color: rgba(114, 200, 101, 0.15); color: #2e7d32; padding: 4px 8px; border-radius: 6px; font-weight: 700; font-size: 0.75rem; }
    .badge-vortex-soft { background-color: rgba(231, 76, 60, 0.1); color: #c62828; padding: 4px 8px; border-radius: 6px; font-weight: 700; font-size: 0.75rem; }
</style>

<div class="toolbar-vortex">
    <a href="<?php echo e(route('distribusi.create')); ?>" class="btn btn-primary" style="background-color: #2c3e50; border-color: #2c3e50; padding: 10px 18px; border-radius: 8px;">
        <i class="fas fa-plus mr-2"></i> Tambah Distribusi
    </a>
    <form method="GET" style="display: flex; gap: 8px; flex: 1; max-width: 400px;">
        <input type="text" name="search" class="form-control" placeholder="Cari area, toko, signage..." value="<?php echo e(request('search')); ?>">
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
                    <th style="width: 10%;">Nama Area</th>
                    <th style="width: 14%;">Nama Toko</th>
                    <th style="width: 18%;">Digital Signage</th>
                    <th style="width: 9%;">Mulai</th>
                    <th style="width: 9%;">Selesai</th>
                    <th style="width: 12%;">Waktu Tayang</th>
                    <th style="width: 20%;">Jadwal Tayang</th>
                    <th style="width: 8%; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $distribusi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $first   = $group->first();
                    $signage = $first->digitalSignage;
                    $toko    = $signage?->toko;
                    $perJam  = $group->sortBy('id')->groupBy(fn ($d) => substr($d->waktu_mulai, 0, 5) . ' - ' . substr($d->waktu_selesai, 0, 5))->sortKeys();
                    $no = 0;
                ?>
                <tr>
                    <td><?php echo e(strtoupper($toko?->area ?? '-')); ?></td>
                    <td><?php echo e(strtoupper($toko?->nama_toko ?? '-')); ?></td>
                    <td>
                        <div style="font-weight: 800; color: #2c3e50;"><?php echo e(strtoupper($signage?->nama_signage ?? '-')); ?></div>
                        <small style="color: var(--text-muted);"><?php echo e($signage?->device_id); ?></small>
                    </td>
                    <td><?php echo e(\Carbon\Carbon::parse($first->tanggal_mulai)->format('d-m-Y')); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($first->tanggal_selesai)->format('d-m-Y')); ?></td>
                    <td>
                        <div><?php echo e($first->frekuensi_hari ?? 'Setiap Hari'); ?></div>
                        <div><?php echo e($first->frekuensi_tanggal ?? 'Semua Tanggal'); ?></div>
                        <div><?php echo e($first->frekuensi_bulan ?? 'Setiap Bulan'); ?></div>
                    </td>
                    <td>
                        <?php $__currentLoopData = $perJam; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jam => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div style="font-weight: 700; margin-top: <?php echo e($loop->first ? 0 : 8); ?>px;">&bull; <?php echo e($jam); ?></div>
                            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $no++; ?>
                                <div>#<?php echo e($no); ?>: <?php echo e($d->jadwalTayang?->nama_jadwal ?? '-'); ?>

                                    <?php if(!$d->aktif): ?> <span class="badge-vortex-soft">Nonaktif</span> <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 8px; justify-content: center;">
                            <a href="<?php echo e(route('distribusi.edit', $first->id)); ?>" class="btn-icon-square btn-edit-vortex"><i class="fas fa-edit"></i></a>
                            <form action="<?php echo e(route('distribusi.destroy', $first->id)); ?>" method="POST" onsubmit="return confirm('Hapus distribusi ini beserta semua jadwal tayangnya?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn-icon-square btn-delete-vortex"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="8" class="text-center" style="padding: 40px; color: var(--text-muted);">Belum ada data distribusi.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div style="padding: 16px 24px;"><?php echo e($distribusi->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/novasgn/domains/vortexsign.web.id/signage/resources/views/distribusi/index.blade.php ENDPATH**/ ?>