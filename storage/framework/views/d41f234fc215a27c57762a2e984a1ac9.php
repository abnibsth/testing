<?php $__env->startSection('title', 'Data Toko'); ?>
<?php $__env->startSection('page-title', 'Iklan > Data Toko'); ?>

<?php $__env->startSection('content'); ?>


<style>
    /* ==========================================================================
       ✨ VORTEXSIGN PREMIUM CLEAN TABLE LAYOUT ✨
       ========================================================================== */
    .vortex-card-index {
        background: var(--bg-card, #ffffff);
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        border: 1px solid var(--border-main, #e9ecef);
        overflow: hidden;
        width: 100%;
        display: block;
        margin-top: 10px;
    }

    .toolbar-vortex {
        display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; justify-content: space-between; align-items: center;
    }

    /* Memaksa Tabel Melebar Penuh & Simetris */
    .table-vortex {
        width: 100% !important;
        min-width: 100% !important;
        display: table !important;
        border-collapse: collapse !important;
        table-layout: fixed !important; /* Kunci mati proporsi persentase kolom */
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
        padding: 16px 16px !important;
        border-bottom: 1px solid var(--border-table, #cbd5e1) !important;
        color: var(--text-main, #2c3e50) !important;
        font-weight: 600 !important;
        font-size: 0.9rem !important;
        vertical-align: middle !important;
    }

    .table-vortex tbody tr:hover td {
        background-color: rgba(114, 200, 101, 0.03) !important; /* Highlight Hijau Vibrant */
    }

    /* Penyelarasan Kiri Tengah Kanan */
    .table-vortex th.text-left, .table-vortex td.text-left { text-align: left !important; }
    .table-vortex th.text-center, .table-vortex td.text-center { text-align: center !important; }
    
    .table-vortex th:first-child, .table-vortex td:first-child { padding-left: 24px !important; }
    .table-vortex th:last-child, .table-vortex td:last-child { padding-right: 24px !important; }

    /* Tombol Aksi Kustom */
    .btn-icon-square {
        width: 36px; height: 36px; padding: 0;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 8px; color: white !important; font-size: 14px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05); transition: all 0.2s; border: none; text-decoration: none;
    }
    .btn-icon-square:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.1); color: white; }
    
    .btn-edit-vortex { background-color: #f39c12; }
    .btn-edit-vortex:hover { background-color: #d68910; }
    
    .btn-delete-vortex { background-color: #e74c3c; }
    .btn-delete-vortex:hover { background-color: #c0392b; }

    /* Lencana Modern */
    .text-vortex-highlight { color: var(--color-primary, #2c3e50); font-weight: 800; font-size: 15px; }
    [data-theme="dark"] .text-vortex-highlight { color: #ecf0f1; }

    .badge-vortex-soft {
        background-color: rgba(44, 62, 80, 0.1); color: #2c3e50;
        padding: 4px 10px; border-radius: 6px; font-weight: 700; font-size: 0.75rem; display: inline-block;
    }
    [data-theme="dark"] .badge-vortex-soft { background-color: rgba(236, 240, 241, 0.1); color: #ecf0f1; }

    .badge-vortex-cyan {
        background-color: rgba(54, 185, 204, 0.12); color: #0e7490;
        padding: 4px 10px; border-radius: 6px; font-weight: 700; font-size: 0.75rem; display: inline-block;
    }

    .badge-vortex-green {
        background-color: rgba(114, 200, 101, 0.15); color: #72c865;
        padding: 4px 10px; border-radius: 6px; font-weight: 800; font-size: 0.75rem; display: inline-block;
    }

    .pagination-container {
        padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; 
        font-size: 13px; color: var(--text-muted, #7f8c8d); border-top: 1px solid var(--border-main, #e9ecef);
        background: var(--bg-card, #ffffff);
    }
</style>


<div class="toolbar-vortex">
    <a href="<?php echo e(route('toko.create')); ?>" class="btn btn-primary" style="background-color: #2c3e50; border-color: #2c3e50; padding: 10px 18px; border-radius: 8px;">
        <i class="fas fa-plus mr-2"></i> Tambah Toko
    </a>
    <form class="search-form" method="GET" style="display: flex; gap: 8px; flex: 1; max-width: 400px;">
        <?php if(request('area')): ?>
            <input type="hidden" name="area" value="<?php echo e(request('area')); ?>">
        <?php endif; ?>
        <input type="text" name="search" class="search-input form-control" 
               placeholder="Cari nama atau kode toko..." value="<?php echo e(request('search')); ?>">
        <button type="submit" class="btn btn-secondary" style="padding: 8px 16px; border-radius: 8px; background-color: #b2bec3; border-color: #b2bec3;">
            <i class="fas fa-search"></i>
        </button>
    </form>
</div>

<?php if(request('area')): ?>
<div style="display: flex; gap: 8px; align-items: center; margin-bottom: 16px;">
    <span style="font-size: 12px; font-weight: 700; color: var(--text-muted);">Filter Area:</span>
    <span class="badge-vortex-cyan" style="font-size: 12px; font-weight: 800;"><?php echo e(request('area')); ?></span>
    <a href="<?php echo e(route('toko.index')); ?>" style="font-size: 12px; font-weight: 800; color: #e74c3c; text-decoration: none; margin-left: 6px;">
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
    <div class="table-responsive" style="overflow-x: auto; width: 100%;">
        <table class="table-vortex">
            <thead>
                <tr>
                    <th class="text-left" style="width: 5%;">No</th>
                    <th class="text-left" style="width: 22%;">Info Toko</th>
                    <th class="text-left" style="width: 13%;">Kode Toko</th>
                    <th class="text-left" style="width: 25%;">Lokasi & Kontak</th>
                    <th class="text-center" style="width: 14%;">Operasional</th>
                    <th class="text-left" style="width: 11%;">Keterangan</th>
                    <th class="text-center" style="width: 10%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $toko; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="text-left" style="color: var(--text-muted, #7f8c8d); font-weight: 700;">
                        <?php echo e($toko->firstItem() + $index); ?>

                    </td>
                    
                    
                    <td class="text-left">
                        <span class="text-vortex-highlight" style="display: block; margin-bottom: 4px;">
                            <?php echo e($item->nama_toko); ?>

                        </span>
                        <span class="badge-vortex-soft" title="Channel">
                            <i class="fas fa-tag mr-1" style="font-size: 10px;"></i> <?php echo e($item->channel ?? '-'); ?>

                        </span>
                    </td>

                    
                    <td class="text-left">
                        <span class="badge-vortex-cyan" style="font-size: 13px; font-weight: 800; padding: 6px 12px; letter-spacing: 0.5px;" title="Kode Toko">
                            <i class="fas fa-store-alt mr-1"></i> <?php echo e($item->kode_toko ?? '-'); ?>

                        </span>
                    </td>
                    
                    
                    <td class="text-left">
                        <div style="font-weight: 800; color: var(--text-main, #2c3e50); margin-bottom: 4px;">
                            <?php echo e($item->kota ?? '-'); ?>, <?php echo e($item->provinsi ?? '-'); ?>

                        </div>
                        <div style="font-size: 12px; color: var(--text-muted, #7f8c8d); font-weight: 600; line-height: 1.4; margin-bottom: 6px; white-space: normal;">
                            <?php echo e($item->alamat ?? '-'); ?>

                        </div>
                        <div style="font-size: 11px; color: var(--text-muted, #7f8c8d); display: flex; flex-direction: column; gap: 3px;">
                            <span><i class="fas fa-map-marker-alt text-danger mr-1"></i> Area: <strong><?php echo e($item->area ?? '-'); ?></strong> (Teknisi: <?php echo e($item->area_teknisi ?? '-'); ?>)</span>
                            <?php if($item->telepon): ?>
                                <span><i class="fas fa-phone-alt text-success mr-1"></i> <?php echo e($item->telepon); ?></span>
                            <?php endif; ?>
                        </div>
                    </td>

                    
                    <td class="text-center">
                        <div style="font-weight: 800; color: var(--text-main, #2c3e50); margin-bottom: 6px; font-size: 13px;" title="SIM Card">
                            <i class="fas fa-sim-card text-muted mr-1"></i> <?php echo e($item->sim_card ?? '-'); ?>

                        </div>
                        <span class="badge-vortex-green" title="Jam Operasional">
                            <i class="far fa-clock mr-1"></i> <?php echo e(substr($item->jam_buka, 0, 5)); ?> - <?php echo e(substr($item->jam_tutup, 0, 5)); ?>

                        </span>
                    </td>
                    
                    
                    <td class="text-left">
                        <?php if($item->keterangan): ?>
                            <div style="font-size: 12px; line-height: 1.4; color: var(--text-muted, #7f8c8d); display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;" title="<?php echo e($item->keterangan); ?>">
                                <?php echo e($item->keterangan); ?>

                            </div>
                        <?php else: ?>
                            <span style="color: var(--text-muted, #7f8c8d);">-</span>
                        <?php endif; ?>
                    </td>
                    
                    
                    <td class="text-center">
                        <div style="display: flex; gap: 8px; justify-content: center;">
                            <a href="<?php echo e(route('toko.edit', $item->id)); ?>" 
                               class="btn-icon-square btn-edit-vortex" 
                               title="Edit Data">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?php echo e(route('toko.destroy', $item->id)); ?>" 
                                  method="POST" 
                                  style="display:inline;"
                                  onsubmit="return confirm('Peringatan: Apakah Anda yakin ingin menghapus data toko <?php echo e($item->nama_toko); ?> secara permanen?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" 
                                        class="btn-icon-square btn-delete-vortex" 
                                        title="Hapus Data">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 50px !important; color: var(--text-muted, #7f8c8d);">
                        <i class="fas fa-store-alt-slash" style="font-size: 48px; margin-bottom: 16px; display: block; opacity: 0.3;"></i>
                        <strong style="font-size: 15px;">Belum ada data toko yang tersimpan.</strong>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    
    <?php if(isset($toko) && $toko->hasPages()): ?>
    <div class="pagination-container">
        <span>Menampilkan <strong><?php echo e($toko->firstItem() ?? 0); ?></strong> - <strong><?php echo e($toko->lastItem() ?? 0); ?></strong> dari total <strong><?php echo e($toko->total()); ?></strong> data toko</span>
        <div><?php echo e($toko->links()); ?></div>
    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/novasgn/domains/vortexsign.web.id/signage/resources/views/toko/index.blade.php ENDPATH**/ ?>