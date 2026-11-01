<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | CMS Digital Signage</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-primary: #2c3e50;
            --color-success: #72c865;
            --color-bg-input: #f8f9fa;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Nunito', 'Segoe UI', sans-serif; }
        
        body {
            background-color: #1a252f;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center; padding: 20px;
            overflow: hidden; position: relative;
        }

        body::before, body::after {
            content: ""; position: absolute; border-radius: 50%; z-index: 1; filter: blur(100px); opacity: 0.12;
        }
        body::before { width: 350px; height: 350px; background: #72c865; top: -50px; right: -50px; }
        body::after { width: 400px; height: 400px; background: #f39c12; bottom: -80px; left: -80px; }

        .login-container {
            position: relative; z-index: 2; background: #222d37; width: 100%; max-width: 410px;
            border-radius: 16px; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            border: 1px solid rgba(255, 255, 255, 0.05); animation: slideUp 0.4s ease-out;
        }
        @keyframes slideUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }

        .login-header { text-align: center; padding: 35px 20px 10px 20px; color: #ffffff; }
        .login-header h1 { font-size: 26px; font-weight: 800; }
        .login-header h1 span { color: #72c865; }
        .login-header p { font-size: 11px; color: #7f8c8d; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }

        .login-body { padding: 25px 32px 35px 32px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 13px; font-weight: 700; color: #ecf0f1; margin-bottom: 8px; text-transform: uppercase; }
        
        .input-wrapper { position: relative; }
        .input-wrapper i.input-icon {
            position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #7f8c8d; z-index: 3;
        }
        .form-control {
            width: 100%; padding: 13px 14px 13px 44px; border: 1px solid rgba(255,255,255,0.05); border-radius: 10px;
            font-size: 14px; background: #2c3e50; color: #ffffff; outline: none;
        }
        .form-control:focus { border-color: #72c865; box-shadow: 0 0 0 3px rgba(114, 200, 101, 0.25); }
        .form-control:focus ~ i.input-icon { color: #72c865; }

        .password-toggle {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: #7f8c8d; cursor: pointer; z-index: 4;
        }

        .remember-row { display: flex; align-items: center; gap: 8px; margin-bottom: 24px; font-size: 13px; color: #bdc3c7; font-weight: 600; }
        .remember-row input[type="checkbox"] { accent-color: #72c865; width: 16px; height: 16px; }

        /* Lupa password */
        .forgot-link {
            margin-left: auto; color: #72c865; font-size: 13px; font-weight: 700;
            text-decoration: none; background: none; border: none; cursor: pointer;
        }
        .forgot-link:hover { text-decoration: underline; color: #5db051; }
        .forgot-info {
            display: none; margin-bottom: 20px; padding: 12px 14px; border-radius: 10px;
            background: #1f3a2a; color: #a5e6a0; border-left: 4px solid #72c865;
            font-size: 13px; font-weight: 600; align-items: center; gap: 8px;
        }
        .forgot-info.show { display: flex; animation: slideUp 0.25s ease-out; }
        .remember-row.has-info { margin-bottom: 14px; }

        .btn-login {
            width: 100%; padding: 14px; background: #72c865; color: #fff; border: none; border-radius: 10px;
            font-size: 15px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 12px rgba(114, 200, 101, 0.2);
        }
        .btn-login:hover { background: #5db051; transform: translateY(-1px); }

        .login-footer { text-align: center; padding: 16px; font-size: 11px; color: #7f8c8d; background: #1a252f; border-top: 1px solid rgba(255,255,255,0.05); }
        .alert { padding: 12px 14px; border-radius: 10px; margin-bottom: 18px; font-size: 13px; display: flex; align-items: center; gap: 8px; }
        .alert-danger { background: #3d1d1d; color: #ff8a80; border-left: 4px solid #e74c3c; }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-header">
        <h1>VORTEX<span>SIGN</span></h1>
        <p>Content Management & Monitoring Dashboard</p>
    </div>

    <div class="login-body">
        @if($errors->any())
            <div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" id="loginForm">
            @csrf
            <div class="form-group">
                <label for="username">Username / Email</label>
                <div class="input-wrapper">
                    <input type="text" name="username" id="username" class="form-control" value="{{ old('username') }}" placeholder="Masukkan username" required autofocus>
                    <i class="fas fa-user input-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                    <i class="fas fa-lock input-icon"></i>
                    <button type="button" class="password-toggle" id="togglePassword"><i class="fas fa-eye"></i></button>
                </div>
            </div>

            <div class="remember-row" id="rememberRow">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember" style="cursor:pointer;">Ingat saya</label>
                <button type="button" class="forgot-link" id="forgotLink">Lupa password?</button>
            </div>

            <div class="forgot-info" id="forgotInfo" role="status">
                <i class="fas fa-info-circle"></i>
                <span>Silahkan hubungi admin untuk reset password.</span>
            </div>

            <button type="submit" class="btn-login" id="loginBtn"><i class="fas fa-sign-in-alt"></i> Masuk</button>
        </form>
    </div>
    <div class="login-footer">Version 2026 - 2.2.1 © CMS Digital Signage</div>
</div>

<script>
    const toggleBtn = document.getElementById('togglePassword');
    const passInput = document.getElementById('password');
    const toggleIcon = toggleBtn.querySelector('i');
    toggleBtn.addEventListener('click', () => {
        const isPassword = passInput.type === 'password';
        passInput.type = isPassword ? 'text' : 'password';
        toggleIcon.classList.toggle('fa-eye');
        toggleIcon.classList.toggle('fa-eye-slash');
    });

    // Lupa password: tampilkan pesan, klik lagi untuk menyembunyikan
    const forgotLink = document.getElementById('forgotLink');
    const forgotInfo = document.getElementById('forgotInfo');
    const rememberRow = document.getElementById('rememberRow');
    forgotLink.addEventListener('click', () => {
        const show = forgotInfo.classList.toggle('show');
        rememberRow.classList.toggle('has-info', show);
    });
</script>
</body>
</html>
