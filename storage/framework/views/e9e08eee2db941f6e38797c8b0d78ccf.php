<?php $__env->startSection('title', 'Tambah Toko'); ?>
<?php $__env->startSection('page-title', 'Iklan > Data Toko > Tambah Toko'); ?>

<?php $__env->startSection('content'); ?>

<style>
    .vortex-card-form {
        background: var(--bg-card, #ffffff); border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        border: 1px solid var(--border-main, #e9ecef); overflow: hidden; width: 100%; max-width: 900px; margin-top: 10px;
    }
    .vortex-header-form {
        padding: 24px 24px 16px 24px; border-bottom: 1px solid var(--border-main, #e9ecef); display: flex; align-items: center;
    }
    .vortex-header-form h5 {
        margin: 0; font-weight: 800; color: var(--text-main, #2c3e50); font-size: 1.25rem; display: flex; align-items: center;
    }
    .icon-title-box {
        width: 42px; height: 42px; background: rgba(54, 185, 204, 0.15); color: #36b9cc; 
        border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 16px;
    }
    .vortex-body-form { padding: 30px 24px; }
    .vortex-label-locked {
        font-size: 12px; font-weight: 800; color: var(--text-muted, #7f8c8d);
        text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 8px; display: block;
    }
    .vortex-input-locked {
        width: 100% !important; display: block !important; padding: 14px 16px;
        border: 1px solid var(--border-table, #cbd5e1); border-radius: 10px;
        font-size: 14px; font-weight: 600; font-family: inherit;
        background: var(--bg-input, #f8f9fa); color: var(--text-main, #2c3e50);
        transition: all 0.2s ease; box-sizing: border-box;
    }
    .vortex-input-locked:focus {
        outline: none; border-color: #36b9cc; box-shadow: 0 0 0 3px rgba(54, 185, 204, 0.15); background: var(--bg-card, #ffffff);
    }
    .vortex-error-text { color: #e74c3c; font-size: 12px; font-weight: 700; margin-top: 6px; display: block; }
    
    .vortex-btn-locked {
        padding: 12px 24px; border-radius: 8px; font-weight: 800; font-size: 13px; text-transform: uppercase;
        letter-spacing: 0.5px; border: none; display: inline-flex; align-items: center; justify-content: center;
        gap: 8px; transition: all 0.2s ease; cursor: pointer; text-decoration: none;
    }
    .btn-save-v { background: #2c3e50; color: #ffffff; box-shadow: 0 4px 10px rgba(44, 62, 80, 0.2); }
    .btn-save-v:hover { background: #1a252f; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(44, 62, 80, 0.3); color: #ffffff; }
    .btn-cancel-v { background: #b2bec3; color: #ffffff; }
    .btn-cancel-v:hover { background: #95a5a6; transform: translateY(-2px); color: #ffffff; }
    
    .vortex-form-row { display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 24px; width: 100%; }
    .vortex-form-col { flex: 1; min-width: 200px; }
</style>

<div class="vortex-card-form">
    <div class="vortex-header-form">
        <h5>
            <div class="icon-title-box"><i class="fas fa-store" style="font-size: 16px;"></i></div>
            Tambah Data Toko Baru
        </h5>
    </div>
    
    <div class="vortex-body-form">
        <form method="POST" action="<?php echo e(route('toko.store')); ?>">
            <?php echo csrf_field(); ?>

            <div class="vortex-form-row">
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Nama Toko <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="nama_toko" class="vortex-input-locked" value="<?php echo e(old('nama_toko')); ?>" required>
                </div>
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Kode Toko <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="kode_toko" class="vortex-input-locked" value="<?php echo e(old('kode_toko')); ?>" required>
                </div>
            </div>

            <div class="vortex-form-row">
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Channel <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="channel" class="vortex-input-locked" value="<?php echo e(old('channel')); ?>" required>
                </div>
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">SIM Card</label>
                    <input type="text" name="sim_card" class="vortex-input-locked" value="<?php echo e(old('sim_card')); ?>">
                </div>
            </div>

            <div style="margin-bottom: 24px;">
                <label class="vortex-label-locked">Alamat Lengkap <span style="color: #e74c3c;">*</span></label>
                <textarea name="alamat" class="vortex-input-locked" rows="2" required><?php echo e(old('alamat')); ?></textarea>
            </div>
            
            <div class="vortex-form-row">
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Provinsi <span style="color: #e74c3c;">*</span></label>
                    <select name="provinsi" id="provinsi" class="vortex-input-locked" onchange="updateKota()" required>
                        <option value="">-- Pilih Provinsi --</option>
                        <option value="DKI Jakarta" <?php echo e(old('provinsi') == 'DKI Jakarta' ? 'selected' : ''); ?>>DKI Jakarta</option>
                        <option value="Jawa Barat" <?php echo e(old('provinsi') == 'Jawa Barat' ? 'selected' : ''); ?>>Jawa Barat</option>
                    </select>
                </div>
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Kota <span style="color: #e74c3c;">*</span></label>
                    <select name="kota" id="kota" class="vortex-input-locked" onchange="updateArea()" required>
                        <option value="">-- Pilih Kota --</option>
                    </select>
                </div>
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Area Regional <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="area" id="area" class="vortex-input-locked" value="<?php echo e(old('area')); ?>" readonly required>
                </div>
            </div>

            <div class="vortex-form-row">
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Telepon</label>
                    <input type="text" name="telepon" class="vortex-input-locked" value="<?php echo e(old('telepon')); ?>">
                </div>
               <div class="vortex-form-col">
    <label class="vortex-label-locked">Jam Buka <span style="color: #e74c3c;">*</span></label>
    <input type="text" name="jam_buka" class="vortex-input-locked flatpickr-time" 
           value="<?php echo e(old('jam_buka', isset($toko) ? \Carbon\Carbon::parse($toko->jam_buka)->format('H:i') : '10:00')); ?>" required>
</div>
<div class="vortex-form-col">
    <label class="vortex-label-locked">Jam Tutup <span style="color: #e74c3c;">*</span></label>
    <input type="text" name="jam_tutup" class="vortex-input-locked flatpickr-time" 
           value="<?php echo e(old('jam_tutup', isset($toko) ? \Carbon\Carbon::parse($toko->jam_tutup)->format('H:i') : '22:00')); ?>" required>
</div>
                <div class="vortex-form-col">
                    <label class="vortex-label-locked">Area Teknisi <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="area_teknisi" class="vortex-input-locked" value="<?php echo e(old('area_teknisi', 'Jakarta')); ?>" required>
                </div>
            </div>

            <div style="margin-bottom: 12px;">
                <label class="vortex-label-locked">Keterangan Tambahan</label>
                <textarea name="keterangan" class="vortex-input-locked" rows="2"><?php echo e(old('keterangan')); ?></textarea>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 36px; padding-top: 24px; border-top: 1px solid var(--border-main, #e9ecef);">
                <button type="submit" class="vortex-btn-locked btn-save-v">
                    <i class="fas fa-save"></i> Simpan Data Toko
                </button>
                <a href="<?php echo e(route('toko.index')); ?>" class="vortex-btn-locked btn-cancel-v">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    const kotaData = {
        'DKI Jakarta': {'Jakarta Pusat': 'JAKARTA PUSAT', 'Jakarta Utara': 'JAKARTA UTARA', 'Jakarta Selatan': 'JAKARTA SELATAN', 'Jakarta Timur': 'JAKARTA TIMUR', 'Jakarta Barat': 'JAKARTA BARAT', 'Kepulauan Seribu': 'KEPULAUAN SERIBU'},
        'Jawa Barat': {'Kota Bekasi': 'BEKASI', 'Kabupaten Bekasi': 'BEKASI', 'Bandung': 'BANDUNG', 'Bogor': 'BOGOR', 'Depok': 'DEPOK', 'Cimahi': 'CIMAHI', 'Cirebon': 'CIREBON', 'Sukabumi': 'SUKABUMI', 'Tasikmalaya': 'TASIKMALAYA', 'Karawang': 'KARAWANG', 'Purwakarta': 'PURWAKARTA', 'Subang': 'SUBANG', 'Garut': 'GARUT', 'Indramayu': 'INDRAMAYU'},
    };
    function updateKota() {
        const p = document.getElementById('provinsi').value;
        const k = document.getElementById('kota');
        k.innerHTML = '<option value="">-- Pilih Kota --</option>';
        if (!kotaData[p]) return;
        for (const v in kotaData[p]) {
            let opt = document.createElement('option'); opt.value = v; opt.text = v;
            if(v == '<?php echo e(old('kota')); ?>') opt.selected = true;
            k.appendChild(opt);
        }
    }
    function updateArea() {
        const p = document.getElementById('provinsi').value;
        const k = document.getElementById('kota').value;
        document.getElementById('area').value = (p && k && kotaData[p][k]) ? kotaData[p][k] : '';
    }
    window.onload = updateKota;
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr(".flatpickr-time", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true, // INI KUNCI 24 JAM
            defaultHour: 10
        });
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/novasgn/domains/vortexsign.web.id/signage/resources/views/toko/create.blade.php ENDPATH**/ ?>