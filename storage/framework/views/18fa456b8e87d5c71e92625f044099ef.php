<?php $__env->startSection('title', 'Tambah Client'); ?>
<?php $__env->startSection('page-title', 'Kelola Client > Tambah'); ?>

<?php $__env->startSection('content'); ?>

<style>
    .vortex-card-edit {
        background: var(--bg-card, #ffffff);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        border: 1px solid var(--border-main, #e9ecef);
        overflow: hidden;
        width: 100%;
        max-width: 800px; /* Lebar dibatasi agar proporsional dan tidak kepanjangan */
        margin-top: 10px;
    }

    .vortex-header-edit {
        padding: 24px 24px 16px 24px;
        border-bottom: 1px solid var(--border-main, #e9ecef);
        display: flex;
        align-items: center;
    }

    .vortex-header-edit h5 {
        margin: 0;
        font-weight: 800;
        color: var(--text-main, #2c3e50);
        font-size: 1.25rem;
        display: flex;
        align-items: center;
    }

    .icon-title-box {
        width: 42px; height: 42px; 
        background: rgba(54, 185, 204, 0.15); 
        color: #36b9cc; 
        border-radius: 10px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        margin-right: 16px;
    }

    .vortex-body-edit { padding: 30px 24px; }

    .vortex-label-locked {
        font-size: 12px;
        font-weight: 800;
        color: var(--text-muted, #7f8c8d);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 8px;
        display: block;
    }

    .vortex-input-locked {
        width: 100% !important;
        display: block !important;
        padding: 14px 16px;
        border: 1px solid var(--border-table, #cbd5e1);
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        font-family: inherit;
        background: var(--bg-input, #f8f9fa);
        color: var(--text-main, #2c3e50);
        transition: all 0.2s ease;
        box-sizing: border-box;
    }

    .vortex-input-locked:focus {
        outline: none;
        border-color: #36b9cc;
        box-shadow: 0 0 0 3px rgba(54, 185, 204, 0.15);
        background: var(--bg-card, #ffffff);
    }

    .vortex-btn-locked {
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 800;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s ease;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-save-v { background: #2c3e50; color: #ffffff; box-shadow: 0 4px 10px rgba(44, 62, 80, 0.2); }
    .btn-save-v:hover { background: #1a252f; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(44, 62, 80, 0.3); color: #ffffff; }

    .btn-cancel-v { background: #b2bec3; color: #ffffff; }
    .btn-cancel-v:hover { background: #95a5a6; transform: translateY(-2px); color: #ffffff; }

    .vortex-error-text { color: #e74c3c; font-size: 12px; font-weight: 700; margin-top: 6px; display: block; }
</style>

<div class="vortex-card-form">
    <div class="vortex-header-form">
        <h5><div class="icon-title-box"><i class="fas fa-building" style="font-size: 16px;"></i></div> Tambah Client Baru</h5>
    </div>
    <div class="vortex-body-form">
        <form method="POST" action="<?php echo e(route('clients.store')); ?>">
            <?php echo csrf_field(); ?>
            <div class="vortex-form-row">
            <div class="vortex-form-col">
                <label class="vortex-label-locked">Nama Client <span style="color: #e74c3c;">*</span></label>
                <input type="text" name="nama" class="vortex-input-locked" value="<?php echo e(old('nama')); ?>" placeholder="PT Contoh Retail" required>
                <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small style="color:#e74c3c;"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="vortex-form-col">
                <label class="vortex-label-locked">Kode Client <span style="color: #e74c3c;">*</span></label>
                <input type="text" name="kode" class="vortex-input-locked" value="<?php echo e(old('kode')); ?>" placeholder="contoh (huruf kecil, tanpa spasi)" required>
                <?php $__errorArgs = ['kode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small style="color:#e74c3c;"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            </div>
            <div class="vortex-form-row">
            <div class="vortex-form-col">
                <label class="vortex-label-locked">Subdomain</label>
                <input type="text" name="subdomain" id="subdomainInput" class="vortex-input-locked" value="<?php echo e(old('subdomain')); ?>" placeholder="kosongkan untuk otomatis dari kode" maxlength="30" pattern="[a-z0-9][a-z0-9\-]{1,28}[a-z0-9]" autocomplete="off">
                <small style="color: var(--text-muted, #7f8c8d); font-weight: 600;">Alamat dashboard: https://<span id="subdomainPreview">nama</span>.<?php echo e(parse_url(config('app.url'), PHP_URL_HOST)); ?></small>
                <?php $__errorArgs = ['subdomain'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="vortex-error-text"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            </div>
            <div style="margin: 8px 0 18px; font-weight: 800; color: var(--text-main);">Akun admin pertama untuk client ini</div>
            <div class="vortex-form-row">
            <div class="vortex-form-col">
                <label class="vortex-label-locked">Nama Admin <span style="color: #e74c3c;">*</span></label>
                <input type="text" name="admin_name" class="vortex-input-locked" value="<?php echo e(old('admin_name')); ?>" placeholder="" required>
                <?php $__errorArgs = ['admin_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small style="color:#e74c3c;"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="vortex-form-col">
                <label class="vortex-label-locked">Username <span style="color: #e74c3c;">*</span></label>
                <input type="text" name="admin_username" class="vortex-input-locked" value="<?php echo e(old('admin_username')); ?>" placeholder="" required>
                <?php $__errorArgs = ['admin_username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small style="color:#e74c3c;"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            </div>
            <div class="vortex-form-row">
            <div class="vortex-form-col">
                <label class="vortex-label-locked">Email <span style="color: #e74c3c;">*</span></label>
                <input type="email" name="admin_email" class="vortex-input-locked" value="<?php echo e(old('admin_email')); ?>" placeholder="" required>
                <?php $__errorArgs = ['admin_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small style="color:#e74c3c;"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="vortex-form-col">
                <label class="vortex-label-locked">Password (min. 8 karakter) <span style="color: #e74c3c;">*</span></label>
                <input type="password" name="admin_password" class="vortex-input-locked" autocomplete="new-password" placeholder="" required>
                <?php $__errorArgs = ['admin_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small style="color:#e74c3c;"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            </div>
            <div style="display: flex; gap: 12px; margin-top: 36px; padding-top: 24px; border-top: 1px solid var(--border-main, #e9ecef);">
                <button type="submit" class="vortex-btn-locked btn-save-v"><i class="fas fa-save"></i> Simpan Client</button>
                <a href="<?php echo e(route('clients.index')); ?>" class="vortex-btn-locked btn-cancel-v"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
</div>
<script>
    (function () {
        const kode = document.querySelector('input[name="kode"]');
        const sub = document.getElementById('subdomainInput');
        const preview = document.getElementById('subdomainPreview');
        const slug = v => v.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '').slice(0, 30);
        const update = () => { preview.textContent = sub.value.trim() ? slug(sub.value) : (slug(kode.value) || 'nama'); };
        kode.addEventListener('input', update);
        sub.addEventListener('input', update);
        update();
    })();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/novasgn/domains/vortexsign.web.id/signage/resources/views/clients/create.blade.php ENDPATH**/ ?>