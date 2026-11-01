<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CMS Digital Signage') | ADMINPAGE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        /* ==========================================================================
            🌗 1. MATRIKS TEMA WARNA (VIBRANT FLAT & CHARCOAL DESIGN)
           ========================================================================== */
        :root {
            /* ✨ Skema Mode Terang Modern ✨ */
            --bg-body: #f4f6f9;
            --bg-topbar: #ffffff;
            --bg-sidebar: #2c3e50; /* Sidebar dikunci Navy agar maskulin & tegas */
            --bg-card: #ffffff;
            --bg-table-th: #f8f9fa;
            --bg-input: #ffffff;
            
            --border-main: rgba(44, 62, 80, 0.06);
            --border-sidebar: rgba(255, 255, 255, 0.06);
            --border-table: #e9ecef;
            
            --text-main: #2c3e50;
            --text-muted: #7f8c8d;
            --text-section: #95a5a6;
            
            --color-primary: #2c3e50;   /* Navy Utama */
            --color-accent: #34495e;    /* Dark Navy Hover */
            --color-success: #72c865;   /* Hijau Vibrant */
            --color-warning: #f39c12;   /* Oranye Emas */
            --color-info: #b2bec3;      /* Abu Perak */
            --color-danger: #e74c3c;    /* Merah Tomat */
        }

        [data-theme="dark"] {
            /* ✨ Skema Mode Gelap Global ✨ */
            --bg-body: #1a252f;         
            --bg-topbar: #2c3e50;       
            --bg-sidebar: #2c3e50;      
            --bg-card: #222d37;         
            --bg-table-th: #2c3e50;      
            --bg-input: #2c3e50;        
            
            --border-main: rgba(255, 255, 255, 0.05);
            --border-sidebar: rgba(255, 255, 255, 0.05);
            --border-table: #2c3e50;
            
            --text-main: #ecf0f1;       
            --text-muted: #bdc3c7;      
            --text-section: #7f8c8d;    
        }

        /* ==========================================================================
           🎨 2. GAYA CSS UTAMA & FIX GLOBAL ADAPTIF
           ========================================================================== */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: 'Nunito', 'Segoe UI', sans-serif; 
            font-size: 14px; 
            background: var(--bg-body); 
            display: flex; 
            flex-direction: column; 
            min-height: 100vh; 
            color: var(--text-main); 
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .card, .pastel-card, .table-responsive {
            background-color: var(--bg-card) !important;
            border: 1px solid var(--border-main) !important;
            color: var(--text-main) !important;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.01) !important;
        }

        .card-header, .card-footer, .pastel-card-header {
            background-color: transparent !important;
            border-bottom: 1px solid var(--border-main) !important;
            color: var(--text-main) !important;
        }

        .table, .table-pastel {
            background-color: transparent !important;
            color: var(--text-main) !important;
        }

        .table th, .table-pastel th {
            background-color: var(--bg-table-th) !important;
            color: var(--text-main) !important;
            border-bottom: 2px solid var(--border-main) !important;
        }

        .table td, .table-pastel td {
            background-color: transparent !important;
            border-bottom: 1px solid var(--border-table) !important;
            color: var(--text-main) !important;
        }

        .table tr:hover td, .table-pastel tr:hover td {
            background-color: var(--bg-table-th) !important;
        }

        /* Top bar */
        .topbar {
            background: var(--bg-topbar);
            color: var(--text-main);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 24px; height: 60px; font-size: 13px;
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            border-bottom: 1px solid var(--border-main);
        }
        .topbar .brand { font-weight: 800; font-size: 18px; letter-spacing: 1px; color: var(--text-main); }
        .topbar .brand span { font-weight: 400; color: #72c865; } 
        .topbar .version { font-size: 12px; color: var(--text-muted); font-weight: 600; display: flex; align-items: center; gap: 15px; }

        .wrapper { display: flex; margin-top: 60px; min-height: calc(100vh - 60px); }

        /* Sidebar - Dikunci bertema Gelap untuk Keterbacaan Kontras Tinggi */
        .sidebar {
            width: 250px; min-width: 250px;
            background: var(--bg-sidebar);
            color: #ecf0f1;
            display: flex; flex-direction: column;
            position: fixed; top: 60px; left: 0; bottom: 0;
            overflow-y: auto; z-index: 90;
            border-right: 1px solid var(--border-sidebar);
        }
        .sidebar .user-info {
            padding: 24px 24px 16px; font-size: 14px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            display: flex; align-items: center; gap: 12px;
        }
        .sidebar .user-info::before {
            content: "\f007"; font-family: "Font Awesome 6 Free"; font-weight: 900;
            display: flex; align-items: center; justify-content: center;
            width: 36px; height: 36px; background: rgba(255,255,255,0.1); color: #72c865;
            border-radius: 50%; font-size: 16px;
        }
        .sidebar .user-info strong { color: #ffffff; font-size: 15px; }
        .sidebar .user-info span { font-size: 11px; color: #72c865; font-weight: 600; text-transform: uppercase; }

        .sidebar nav { flex: 1; padding: 16px 0; }
        .sidebar nav a {
            display: flex; align-items: center;
            padding: 12px 16px; margin: 4px 16px;
            color: #bdc3c7; text-decoration: none;
            font-size: 14px; font-weight: 600; border-radius: 8px;
            transition: all .2s;
        }
        .sidebar nav a i { width: 22px; font-size: 16px; text-align: center; margin-right: 10px; }
        .sidebar nav a:hover { background: rgba(255,255,255,0.05); color: #ffffff; }
        .sidebar nav a.active { color: #ffffff; background: #72c865; }
        
        .sidebar nav .nav-section {
            padding: 20px 24px 8px; font-size: 11px;
            text-transform: uppercase; letter-spacing: 1px;
            color: rgba(255,255,255,0.3); font-weight: 700;
        }
        .sidebar nav .nav-sub { padding-left: 16px; }

        .main-content { margin-left: 250px; flex: 1; display: flex; flex-direction: column; background: var(--bg-body); }
        .page-header { padding: 24px 24px 0 24px; font-size: 22px; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px; }
        .content { padding: 24px; flex: 1; }

        /* Buttons */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 18px; border-radius: 8px; font-size: 13px; font-weight: 700;
            cursor: pointer; border: 1px solid transparent; text-decoration: none; transition: all .2s;
        }
        .btn:hover { transform: translateY(-1px); opacity: .95; }
        .btn-primary   { background: var(--color-primary); color: #fff; border-color: var(--color-primary); }
        .btn-success   { background: #72c865; color: #fff; border-color: #72c865; }
        .btn-warning   { background: #f39c12; color: #fff; border-color: #f39c12; }
        .btn-danger    { background: #e74c3c; color: #fff; border-color: #e74c3c; }

        /* Forms */
        .form-control, .search-input {
            width: 100%; padding: 10px 14px; border: 1px solid var(--border-table); border-radius: 8px;
            font-size: 14px; color: var(--text-main) !important; background: var(--bg-input) !important;
        }
        .form-control:focus, .search-input:focus { outline: none; border-color: #72c865; box-shadow: 0 0 0 3px rgba(114, 200, 101, 0.15); }

        /* Alerts */
        .alert { padding: 12px 18px; border-radius: 10px; font-size: 13px; font-weight: 600; margin-bottom: 20px; border: none; }
        .alert-success { background: #e8f8e7; color: #2e6e25; border-left: 4px solid #72c865; }
        .alert-danger  { background: #fdedec; color: #922b21; border-left: 4px solid #e74c3c; }

        /* ==========================================================================
           🌙 3. PERBAIKAN MODE GELAP (dark mode) - berlaku untuk seluruh halaman
           ========================================================================== */
        [data-theme="dark"] {
            color-scheme: dark;                       /* select, date, scrollbar, checkbox ikut gelap */
            --border-table: rgba(255, 255, 255, 0.10);
            --color-primary: #8fb0cf;                 /* dipakai sbg warna teks di beberapa halaman */
        }
        [data-theme="dark"] .btn-primary   { background: #3d566e; border-color: #3d566e; color: #fff; }
        [data-theme="dark"] .btn-secondary { background: #4a5a66; border-color: #4a5a66; color: #fff; }
        [data-theme="dark"] ::placeholder  { color: var(--text-muted); opacity: .7; }
        [data-theme="dark"] select option  { background: #2c3e50; color: #ecf0f1; }
        [data-theme="dark"] input, [data-theme="dark"] select, [data-theme="dark"] textarea { color: var(--text-main); }

        /* Teks navy yang di-hardcode (jadi tak terbaca di latar gelap) */
        [data-theme="dark"] [style*="color: #2c3e50"], [data-theme="dark"] [style*="color:#2c3e50"],
        [data-theme="dark"] strong, [data-theme="dark"] h1, [data-theme="dark"] h2, [data-theme="dark"] h3,
        [data-theme="dark"] h4, [data-theme="dark"] h5, [data-theme="dark"] h6 { color: var(--text-main) !important; }
        [data-theme="dark"] .sidebar strong { color: #ffffff !important; }
        [data-theme="dark"] a[style*="color: inherit"] { color: var(--text-main) !important; }

        /* Latar terang yang di-hardcode (label slot "Video 1", "Jadwal 1", info box, dsb) */
        [data-theme="dark"] [style*="background:#f8f9fa"], [data-theme="dark"] [style*="background: #f8f9fa"],
        [data-theme="dark"] .file-info-alert {
            background: var(--bg-table-th) !important; color: var(--text-main) !important;
            border-color: var(--border-table) !important;
        }
        [data-theme="dark"] [style*="border: 1px solid #cbd5e1"] { border-color: var(--border-table) !important; }

        /* Kotak pesan: sukses / error / peringatan */
        [data-theme="dark"] .alert-success, [data-theme="dark"] [style*="#e8f8e7"] { background: rgba(114,200,101,.15) !important; color: #9be28f !important; }
        [data-theme="dark"] .alert-danger,  [data-theme="dark"] [style*="#fdecea"], [data-theme="dark"] [style*="#fdedec"] { background: rgba(231,76,60,.15) !important; color: #ff9e94 !important; }
        [data-theme="dark"] [style*="#fff8e1"] { background: rgba(243,156,18,.18) !important; color: #f5b041 !important; }

        /* Badge */
        [data-theme="dark"] .badge-vortex-soft  { background: rgba(255,255,255,.12) !important; color: #e0e6ea !important; }
        [data-theme="dark"] .badge-vortex-green { background: rgba(114,200,101,.18) !important; color: #8be07d !important; }
        [data-theme="dark"] .badge-vortex-cyan  { background: rgba(54,185,204,.18) !important;  color: #5fd0e0 !important; }
        [data-theme="dark"] .badge-ganti        { color: #f5b041 !important; }
        [data-theme="dark"] .badge-vortex-content { background: rgba(30,136,229,.2);  color: #7fc0ff; }
        [data-theme="dark"] .badge-vortex-bumper  { background: rgba(171,71,188,.22); color: #dc9bea; }
        [data-theme="dark"] .badge-vortex-promo   { background: rgba(114,200,101,.2); color: #8be07d; }

        /* Baris tabel & hover */
        [data-theme="dark"] .table-vortex tbody tr:hover td { background-color: rgba(255,255,255,.04) !important; }

        /* Pagination (view vendor/pagination/default) - tema terang & gelap */
        .pagination { display: flex; gap: 6px; flex-wrap: wrap; align-items: center; }
        .pagination a, .pagination span {
            padding: 6px 12px; border-radius: 8px; border: 1px solid var(--border-table);
            background: var(--bg-card); color: var(--text-main); text-decoration: none; font-weight: 700; font-size: 13px;
        }
        .pagination a:hover { background: var(--bg-table-th); }
        .pagination span:not(.current) { opacity: .5; }
        .pagination .current { background: #72c865; border-color: #72c865; color: #fff; opacity: 1; }

        /* Kalender / jam (flatpickr) */
        [data-theme="dark"] .flatpickr-calendar { background: #2c3e50; color: #ecf0f1; box-shadow: 0 8px 24px rgba(0,0,0,.5); border: 1px solid var(--border-table); }
        [data-theme="dark"] .flatpickr-calendar input, [data-theme="dark"] .flatpickr-time input,
        [data-theme="dark"] .flatpickr-time .flatpickr-time-separator, [data-theme="dark"] .flatpickr-time .flatpickr-am-pm { color: #ecf0f1; background: transparent; }
        [data-theme="dark"] .flatpickr-time input:hover, [data-theme="dark"] .flatpickr-time input:focus { background: rgba(255,255,255,.08); }
        [data-theme="dark"] .numInputWrapper span.arrowUp:after   { border-bottom-color: #ecf0f1; }
        [data-theme="dark"] .numInputWrapper span.arrowDown:after { border-top-color: #ecf0f1; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-250px); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>

<div class="topbar">
    <div>
        <span class="brand">ADMIN<span>PAGE</span></span>
        <span id="realtime-clock" style="margin-left:24px; font-size:13px; color: var(--text-muted); font-weight: 600;">
            </span>
    </div>
    
    <div style="display: flex; align-items: center; gap: 20px;">
        <button id="theme-toggle" type="button" style="background: transparent; border: none; font-size: 1.1rem; cursor: pointer; color: var(--text-muted); display: flex; align-items: center; justify-content: center; width: 35px; height: 35px; border-radius: 50%;">
            <i id="theme-toggle-icon" class="fas fa-moon"></i>
        </button>
        <div class="version" style="font-size: 12px; color: var(--text-muted); font-weight: 600;">
            <i class="far fa-question-circle" style="margin-right: 6px;"></i> Vortexsign CMS
        </div>
    </div>
</div>

<div class="wrapper">
    <aside class="sidebar">
        <div class="user-info">
            <div>
                <strong>{{ Auth::user()->name ?? 'Vortex Tamu' }}</strong>
                <span>adminisign</span>
            </div>
        </div>
        <nav>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            <div class="nav-section">Data Master</div>
            <a href="{{ route('model-signage.index') }}" class="nav-sub {{ request()->routeIs('model-signage.*') ? 'active' : '' }}">
                <i class="fas fa-tv"></i> Model Signage
            </a>
            <div class="nav-section">Iklan</div>
            <a href="{{ route('toko.index') }}" class="nav-sub {{ request()->routeIs('toko.*') ? 'active' : '' }}">
                <i class="fas fa-store"></i> Data Toko
            </a>
            <a href="{{ route('signage.index') }}" class="nav-sub {{ request()->routeIs('signage.*') ? 'active' : '' }}">
                <i class="fas fa-desktop"></i> Digital Signage
            </a>
            <a href="{{ route('file-iklan.index') }}" class="nav-sub {{ request()->routeIs('file-iklan.*') ? 'active' : '' }}">
                <i class="fas fa-film"></i> File Iklan
            </a>
            <a href="{{ route('jadwal.index') }}" class="nav-sub {{ request()->routeIs('jadwal.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i> Jadwal Tayang
            </a>
            <a href="{{ route('distribusi.index') }}" class="nav-sub {{ request()->routeIs('distribusi.*') ? 'active' : '' }}">
                <i class="fas fa-broadcast-tower"></i> Distribusi Jadwal
            </a>
            <a href="#" onclick="document.getElementById('logout-form').submit()" style="margin-top: 20px; color: #e74c3c;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </aside>

    <div class="main-content">
        <div class="page-header">@yield('page-title', 'Dashboard')</div>
        <div class="content">
            @if(session('success'))
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
            @endif
            @yield('content')
        </div>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const clockEl = document.getElementById('realtime-clock');
    if (clockEl) {
        // ✨ SCRIPT UPDATE CLOCK (FORMAT 24 JAM) ✨
        function updateClock() {
            const now = new Date();
            
            // Format Tanggal (Contoh: Jumat, 22 Mei 2026)
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateStr = now.toLocaleDateString('id-ID', options);
            
            // Format Jam (24-Hour Format: 15:30:45)
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            // Menyatukan Tanggal dan Jam
            clockEl.textContent = `${dateStr} • ${hours}:${minutes}:${seconds} WIB`;
        }
        updateClock(); 
        setInterval(updateClock, 1000);
    }

    const themeToggleBtn = document.getElementById('theme-toggle');
    const themeToggleIcon = document.getElementById('theme-toggle-icon');
    const currentTheme = localStorage.getItem('theme') || null;
    
    if (currentTheme) {
        document.documentElement.setAttribute('data-theme', currentTheme);
        if (currentTheme === 'dark') themeToggleIcon.classList.replace('fa-moon', 'fa-sun');
    }
    
    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            let theme = document.documentElement.getAttribute('data-theme');
            if (theme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'light');
                localStorage.setItem('theme', 'light');
                themeToggleIcon.classList.replace('fa-sun', 'fa-moon');
            } else {
                document.documentElement.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
                themeToggleIcon.classList.replace('fa-moon', 'fa-sun');
            }
        });
    }
});
</script>
@stack('scripts')
</body>
</html>