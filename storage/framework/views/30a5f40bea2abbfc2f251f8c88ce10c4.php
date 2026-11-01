<?php $__env->startSection('title', 'Edit Digital Signage'); ?>
<?php $__env->startSection('page-title', 'Iklan > Digital Signage > Edit Signage'); ?>

<?php $__env->startSection('content'); ?>


<style>
    .vortex-card-form {
        background: var(--bg-card, #ffffff); border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        border: 1px solid var(--border-main, #e9ecef); overflow: hidden; width: 100%; max-width: 900px; margin-top: 10px;
    }
    .vortex-header-form { padding: 24px 24px 16px 24px; border-bottom: 1px solid var(--border-main, #e9ecef); display: flex; align-items: center; }
    .vortex-header-form h5 { margin: 0; font-weight: 800; color: var(--text-main, #2c3e50); font-size: 1.25rem; display: flex; align-items: center; }
    .icon-title-box { width: 42px; height: 42px; background: rgba(54, 185, 204, 0.15); color: #36b9cc; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 16px; }
    .vortex-body-form { padding: 30px 24px; }
    .vortex-label-locked { font-size: 12px; font-weight: 800; color: var(--text-muted, #7f8c8d); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 8px; display: block; }
    .vortex-input-locked { width: 100% !important; display: block !important; padding: 14px 16px; border: 1px solid var(--border-table, #cbd5e1); border-radius: 10px; font-size: 14px; font-weight: 600; background: var(--bg-input, #f8f9fa); color: var(--text-main, #2c3e50); transition: all 0.2s ease; box-sizing: border-box; }
    .vortex-input-locked:focus { outline: none; border-color: #36b9cc; box-shadow: 0 0 0 3px rgba(54, 185, 204, 0.15); background: var(--bg-card, #ffffff); }
    .vortex-input-error { border-color: #e74c3c !important; background: #fff5f5 !important; }
    .vortex-input-error:focus { box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.18) !important; }
    .vortex-error-text { color: #e74c3c; font-size: 12px; font-weight: 700; margin-top: 6px; display: flex; align-items: center; gap: 5px; }
    .vortex-btn-locked { padding: 12px 24px; border-radius: 8px; font-weight: 800; font-size: 13px; text-transform: uppercase; border: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s ease; cursor: pointer; text-decoration: none; }
    .btn-save-v { background: #2c3e50; color: #ffffff; box-shadow: 0 4px 10px rgba(44, 62, 80, 0.2); }
    .btn-cancel-v { background: #b2bec3; color: #ffffff; }
    .vortex-form-row { display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 24px; width: 100%; }
    .vortex-form-col { flex: 1; min-width: 250px; }
</style>

<div class="vortex-card-form">
    <div class="vortex-header-form">
        <h5>
            <div class="icon-title-box"><i class="fas fa-edit" style="font-size: 16px;"></i></div>
            Edit Signage: <?php echo e($signage->nama_signage); ?>

        </h5>
    </div>
    
    <div class="vortex-body-form">
        
        <?php if($errors->any()): ?>
            <div style="background: #fdecea; color: #c62828; border-left: 4px solid #e74c3c; padding: 14px 18px; border-radius: 10px; font-weight: 700; font-size: 13px; margin-bottom: 24px; display: flex; align-items: flex-start; gap: 12px; box-shadow: 0 4px 12px rgba(231,76,60,0.08);">
                <i class="fas fa-exclamation-triangle" style="font-size: 20px; color: #e74c3c; margin-top: 1px; flex-shrink: 0;"></i>
                <div>
                    <div style="font-weight: 800; font-size: 14px; margin-bottom: 4px;">Peringatan: Gagal Memperbarui Digital Signage</div>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div>• <?php echo e($e); ?></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('signage.update', $signage->id)); ?>">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

            <div class="vortex-form-row">
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Toko Penempatan <span style="color: #e74c3c;">*</span></label>
                    <select name="toko_id" class="vortex-input-locked" required>
                        <?php $__currentLoopData = $toko; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($t->id); ?>" <?php echo e(old('toko_id', $signage->toko_id) == $t->id ? 'selected' : ''); ?>>
                                <?php echo e($t->nama_toko); ?> (<?php echo e($t->kode_toko); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Model Signage / TV <span style="color: #e74c3c;">*</span></label>
                    <select name="model_signage_id" class="vortex-input-locked" required>
                        <?php $__currentLoopData = $modelSignage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($m->id); ?>" <?php echo e(old('model_signage_id', $signage->model_signage_id) == $m->id ? 'selected' : ''); ?>>
                                <?php echo e($m->merk ?? 'No Brand'); ?> - <?php echo e($m->nama_model ?? 'Unnamed'); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <div class="vortex-form-row">
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Nama Signage <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="nama_signage" class="vortex-input-locked" value="<?php echo e(old('nama_signage', $signage->nama_signage)); ?>" required>
                </div>
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Device ID <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="device_id" 
                           class="vortex-input-locked <?php $__errorArgs = ['device_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> vortex-input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           value="<?php echo e(old('device_id', $signage->device_id)); ?>" 
                           required>
                    <?php $__errorArgs = ['device_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="vortex-error-text">
                            <i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?>

                        </span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="vortex-form-row">
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Serial Number <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="serial_number" class="vortex-input-locked" value="<?php echo e(old('serial_number', $signage->serial_number)); ?>" required>
                </div>
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Resolusi (W x H) <span style="color: #e74c3c;">*</span></label>
                    <div style="display:flex; gap: 8px;">
                        <input type="number" name="resolusi_width" class="vortex-input-locked" value="<?php echo e(old('resolusi_width', $signage->resolusi_width)); ?>" required>
                        <input type="number" name="resolusi_height" class="vortex-input-locked" value="<?php echo e(old('resolusi_height', $signage->resolusi_height)); ?>" required>
                    </div>
                </div>
            </div>

            <div class="vortex-form-row">
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Orientasi <span style="color: #e74c3c;">*</span></label>
                    <select name="orientasi" class="vortex-input-locked">
                        <?php $__currentLoopData = ['Landscape Kiri','Landscape Kanan','Portrait']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($o); ?>" <?php echo e(old('orientasi', $signage->orientasi) === $o ? 'selected' : ''); ?>><?php echo e($o); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Tanggal Aktivasi</label>
                    <input type="date" name="tanggal_aktivasi" class="vortex-input-locked" value="<?php echo e(old('tanggal_aktivasi', $signage->tanggal_aktivasi?->format('Y-m-d'))); ?>">
                </div>
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Status <span style="color: #e74c3c;">*</span></label>
                    <select name="status" class="vortex-input-locked">
                        <?php $__currentLoopData = ['Aktif','Nonaktif','Maintenance']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($s); ?>" <?php echo e(old('status', $signage->status) === $s ? 'selected' : ''); ?>><?php echo e($s); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 12px;">
                <label class="vortex-label-locked">Keterangan</label>
                <textarea name="keterangan" class="vortex-input-locked" rows="2"><?php echo e(old('keterangan', $signage->keterangan)); ?></textarea>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 36px; padding-top: 24px; border-top: 1px solid var(--border-main, #e9ecef);">
                <button type="submit" class="vortex-btn-locked btn-save-v"><i class="fas fa-save"></i> Update Data</button>
                <a href="<?php echo e(route('signage.index')); ?>" class="vortex-btn-locked btn-cancel-v"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/novasgn/domains/vortexsign.web.id/signage/resources/views/signage/edit.blade.php ENDPATH**/ ?>