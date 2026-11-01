<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Ganti Password | CMS Digital Signage</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Nunito', 'Segoe UI', sans-serif; }
        body { background-color: #1a252f; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }

        .box {
            background: #222d37; width: 100%; max-width: 410px; border-radius: 16px; overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.05);
        }
        .head { text-align: center; padding: 32px 20px 8px; color: #fff; }
        .head h1 { font-size: 24px; font-weight: 800; }
        .head h1 span { color: #72c865; }
        .head p { font-size: 12px; color: #7f8c8d; font-weight: 700; margin-top: 6px; }

        .body { padding: 22px 32px 30px; }
        .notice {
            padding: 12px 14px; border-radius: 10px; margin-bottom: 18px; font-size: 13px; font-weight: 600;
            background: #3b2f12; color: #f5c76b; border-left: 4px solid #f39c12;
        }
        .alert-danger { padding: 12px 14px; border-radius: 10px; margin-bottom: 18px; font-size: 13px; background: #3d1d1d; color: #ff8a80; border-left: 4px solid #e74c3c; }

        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: 13px; font-weight: 700; color: #ecf0f1; margin-bottom: 8px; text-transform: uppercase; }
        .form-control {
            width: 100%; padding: 13px 14px; border: 1px solid rgba(255,255,255,0.05); border-radius: 10px;
            font-size: 14px; background: #2c3e50; color: #fff; outline: none;
        }
        .form-control:focus { border-color: #72c865; box-shadow: 0 0 0 3px rgba(114,200,101,0.25); }

        .btn {
            width: 100%; padding: 14px; background: #72c865; color: #fff; border: none; border-radius: 10px;
            font-size: 15px; font-weight: 700; cursor: pointer;
        }
        .btn:hover { background: #5db051; }
        .out { text-align: center; margin-top: 16px; }
        .out button { background: none; border: none; color: #7f8c8d; font-size: 13px; font-weight: 700; cursor: pointer; }
        .out button:hover { color: #bdc3c7; }
    </style>
</head>
<body>
<div class="box">
    <div class="head">
        <h1>VORTEX<span>SIGN</span></h1>
        <p>Ganti Password</p>
    </div>

    <div class="body">
        <?php if(auth()->user()->must_change_password): ?>
            <div class="notice"><i class="fas fa-key"></i> Password Anda baru direset admin. Buat password baru untuk melanjutkan.</div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="alert-danger"><i class="fas fa-exclamation-triangle"></i> <?php echo e($errors->first()); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('password.ganti.simpan')); ?>">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label for="password">Password baru (min. 8 karakter)</label>
                <input type="password" name="password" id="password" class="form-control" autocomplete="new-password" required autofocus>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Ulangi password baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" autocomplete="new-password" required>
            </div>
            <button type="submit" class="btn"><i class="fas fa-save"></i> Simpan Password</button>
        </form>

        <form method="POST" action="<?php echo e(route('logout')); ?>" class="out">
            <?php echo csrf_field(); ?>
            <button type="submit">Keluar</button>
        </form>
    </div>
</div>
</body>
</html>
<?php /**PATH /home/novasgn/domains/vortexsign.web.id/signage/resources/views/auth/change-password.blade.php ENDPATH**/ ?>