<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Administrasi Pembayaran Sekolah | SMK Muhammadiyah Margasari</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/png">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --brand-green-primary: #0b4d2a;
            --brand-green-hover: #07381e;
            --brand-green-light: #16a34a;
            --accent-mint: #a7f3d0;
            --text-dark: #0f172a;
            --text-secondary: #475569;
            --text-muted: #64748b;
            --border-color: #cbd5e1;
            --bg-neutral: #f8fafc;
        }

        html, body {
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-neutral);
            color: var(--text-dark);
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        /* ----------------------------------------------------
           SPLIT-SCREEN 50:50 LAYOUT (EDGE-TO-EDGE)
        ---------------------------------------------------- */
        .login-layout-container {
            width: 100vw;
            min-height: 100vh;
            display: flex;
            margin: 0;
            padding: 0;
        }

        /* ----------------------------------------------------
           PANEL KIRI: BRANDING (HIJAU MUHAMMADIYAH SOLID)
        ---------------------------------------------------- */
        .brand-panel {
            flex: 0 0 50%;
            background-color: var(--brand-green-primary);
            position: relative;
            padding: 64px 60px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #ffffff;
            overflow: hidden;
        }

        /* Watermark Lambang Surya 12 Muhammadiyah Resmi */
        .watermark-logo {
            position: absolute;
            right: -155px;
            bottom: -135px;
            width: 410px;
            height: 410px;
            opacity: 0.12;
            pointer-events: none;
            user-select: none;
            z-index: 1;
        }

        /* Subtle Geometric Dot Pattern (Minimal) */
        .dots-ornament {
            position: absolute;
            top: 36px;
            right: 36px;
            display: grid;
            grid-template-columns: repeat(4, 4px);
            gap: 8px;
            opacity: 0.12;
            pointer-events: none;
        }

        .dots-ornament span {
            width: 4px;
            height: 4px;
            background-color: #ffffff;
            border-radius: 50%;
        }

        .brand-content {
            position: relative;
            z-index: 2;
            max-width: 460px;
        }

        /* Logo Badge Bulat */
        .brand-logo-wrap {
            width: 64px;
            height: 64px;
            background: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
            padding: 6px;
        }

        .brand-logo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .school-title {
            font-size: 26px;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.25;
            letter-spacing: -0.01em;
            margin-bottom: 4px;
        }

        .school-subtitle {
            font-size: 14px;
            font-weight: 500;
            color: var(--accent-mint);
            margin-bottom: 16px;
            line-height: 1.4;
        }

        .school-description {
            font-size: 13.5px;
            color: #e2e8f0;
            line-height: 1.6;
            margin-bottom: 32px;
            opacity: 0.92;
        }

        /* 3 Informasi Singkat (Feature List) */
        .brand-features-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-bottom: 32px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .feature-icon-box {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent-mint);
            font-size: 13px;
            flex-shrink: 0;
        }

        .feature-text {
            font-size: 13px;
            font-weight: 500;
            color: #f1f5f9;
        }

        /* Elemen Keamanan di Bawah Panel Kiri */
        .security-badge-bottom {
            position: relative;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 8px;
            font-size: 12px;
            color: #d1fae5;
            width: fit-content;
        }

        .security-badge-bottom i {
            color: var(--accent-mint);
            font-size: 13px;
        }

        /* ----------------------------------------------------
           PANEL KANAN: LOGIN FORM
        ---------------------------------------------------- */
        .form-panel {
            flex: 0 0 50%;
            background-color: var(--bg-neutral);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 32px;
            min-height: 100vh;
        }

        /* Card Form Login */
        .login-card {
            width: 100%;
            max-width: 390px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 34px 30px 28px;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04), 0 2px 4px -1px rgba(15, 23, 42, 0.02);
        }

        /* Avatar Header */
        .login-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .avatar-badge {
            width: 46px;
            height: 46px;
            border: 1.5px solid var(--brand-green-light);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--brand-green-light);
            font-size: 18px;
            background: #ffffff;
            margin-bottom: 12px;
        }

        .login-title {
            font-size: 21px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
            letter-spacing: -0.01em;
        }

        .login-subtitle {
            font-size: 13px;
            color: var(--text-muted);
        }

        /* Alert Error Box */
        .alert-error {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 12.5px;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .input-group-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon-left {
            position: absolute;
            left: 13px;
            color: #94a3b8;
            font-size: 13.5px;
            pointer-events: none;
            transition: color 0.15s ease;
        }

        .form-control-input {
            width: 100%;
            height: 42px;
            padding: 8px 12px 8px 38px;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 13px;
            color: var(--text-dark);
            outline: none;
            transition: all 0.15s ease;
            font-family: inherit;
        }

        .form-control-input::placeholder {
            color: #94a3b8;
            font-size: 12.5px;
        }

        .form-control-input:focus {
            border-color: var(--brand-green-light);
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.12);
        }

        .form-control-input:focus + .input-icon-left,
        .input-group-wrap:focus-within .input-icon-left {
            color: var(--brand-green-primary);
        }

        .btn-toggle-eye {
            position: absolute;
            right: 8px;
            background: none;
            border: none;
            color: #94a3b8;
            font-size: 13px;
            cursor: pointer;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.15s ease;
        }

        .btn-toggle-eye:hover {
            color: #475569;
        }

        /* Checkbox & Options */
        .form-options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 4px;
            margin-bottom: 20px;
            font-size: 12.5px;
        }

        .checkbox-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            user-select: none;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .checkbox-label input {
            cursor: pointer;
            accent-color: var(--brand-green-primary);
            width: 15px;
            height: 15px;
            border-radius: 4px;
        }

        .forgot-link {
            color: var(--brand-green-primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.15s ease;
        }

        .forgot-link:hover {
            color: var(--brand-green-hover);
            text-decoration: underline;
        }

        /* Submit Button */
        .btn-masuk {
            width: 100%;
            height: 42px;
            background: var(--brand-green-primary);
            border: 1px solid var(--brand-green-hover);
            color: #ffffff;
            font-size: 13.5px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.15s ease;
            box-shadow: 0 1px 3px rgba(11, 77, 42, 0.2);
        }

        .btn-masuk:hover {
            background: var(--brand-green-hover);
            border-color: var(--brand-green-hover);
            box-shadow: 0 3px 8px rgba(7, 56, 30, 0.25);
            transform: translateY(-1px);
        }

        .btn-masuk:active {
            transform: translateY(0);
        }

        /* Label di bawah tombol */
        .sub-button-text {
            text-align: center;
            font-size: 11.5px;
            font-weight: 500;
            color: #94a3b8;
            margin-top: 18px;
        }

        /* Copyright Footer */
        .page-footer-copy {
            margin-top: 24px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
        }

        /* ----------------------------------------------------
           RESPONSIVE MOBILE / TABLET
        ---------------------------------------------------- */
        @media (max-width: 960px) {
            .login-layout-container {
                flex-direction: column;
                min-height: 100vh;
            }

            .brand-panel {
                flex: none;
                width: 100%;
                padding: 40px 24px 32px;
            }

            .watermark-logo {
                width: 240px;
                height: 240px;
                right: -35px;
                bottom: -35px;
                opacity: 0.06;
            }

            .dots-ornament {
                display: none;
            }

            .school-title {
                font-size: 22px;
            }

            .brand-features-list {
                margin-bottom: 24px;
            }

            .form-panel {
                flex: 1;
                width: 100%;
                padding: 32px 18px 40px;
                min-height: auto;
            }

            .login-card {
                padding: 26px 20px 22px;
                border: 1px solid #e2e8f0;
            }
        }
    </style>
</head>
<body>

    <!-- Main 50:50 Split-Screen Container -->
    <div class="login-layout-container">

        <!-- ====================================================
             PANEL KIRI: BRANDING (HIJAU MUHAMMADIYAH SOLID)
        ==================================================== -->
        <div class="brand-panel">
            <!-- Watermark Lambang Surya 12 Muhammadiyah Resmi -->
            <img src="{{ asset('images/muhammadiyah-emblem-white.png') }}" alt="Watermark Muhammadiyah" class="watermark-logo">

            <!-- Minimal Dots Ornament -->
            <div class="dots-ornament">
                <span></span><span></span><span></span><span></span>
                <span></span><span></span><span></span><span></span>
                <span></span><span></span><span></span><span></span>
            </div>

            <!-- Konten Utama Branding -->
            <div class="brand-content">
                <!-- Logo Badge Bulat -->
                <div class="brand-logo-wrap">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo SMK Muhammadiyah Margasari">
                </div>

                <h1 class="school-title">
                    SMK Muhammadiyah Margasari
                </h1>

                <div class="school-subtitle">
                    Sistem Administrasi Pembayaran Sekolah
                </div>

                <p class="school-description">
                    Kelola pembayaran siswa dengan mudah, cepat, dan akurat.
                </p>

                <!-- 3 Informasi Singkat -->
                <div class="brand-features-list">
                    <div class="feature-item">
                        <div class="feature-icon-box">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <span class="feature-text">Pembayaran IPP &amp; Daftar Ulang</span>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon-box">
                            <i class="fas fa-building-columns"></i>
                        </div>
                        <span class="feature-text">Sarana &amp; Prasarana dan Asesmen</span>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon-box">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <span class="feature-text">Rekap &amp; Laporan Otomatis</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ====================================================
             PANEL KANAN: FORM LOGIN
        ==================================================== -->
        <div class="form-panel">

            <!-- Card Login -->
            <div class="login-card">

                <div class="login-header">
                    <div class="avatar-badge">
                        <i class="far fa-user"></i>
                    </div>
                    <h2 class="login-title">Selamat Datang</h2>
                    <p class="login-subtitle">Masuk ke akun administrator</p>
                </div>

                @if($errors->any())
                    <div class="alert-error">
                        <i class="fas fa-circle-exclamation"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('login.process') }}" method="POST" autocomplete="on">
                    @csrf

                    <!-- Field: Username -->
                    <div class="form-group">
                        <label class="form-label" for="username">Username</label>
                        <div class="input-group-wrap">
                            <i class="far fa-user input-icon-left"></i>
                            <input
                                type="text"
                                name="username"
                                id="username"
                                class="form-control-input"
                                placeholder="Masukkan username"
                                value="{{ old('username') }}"
                                required
                                autofocus
                                autocomplete="username"
                            >
                        </div>
                    </div>

                    <!-- Field: Password -->
                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <div class="input-group-wrap">
                            <i class="fas fa-lock input-icon-left"></i>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control-input"
                                placeholder="Masukkan password"
                                required
                                autocomplete="current-password"
                            >
                            <button type="button" class="btn-toggle-eye" id="togglePasswordBtn" title="Tampilkan / Sembunyikan Password" tabindex="-1">
                                <i class="far fa-eye" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Options: Remember & Forgot -->
                    <div class="form-options-row">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span>Ingat saya</span>
                        </label>
                        <a href="#" class="forgot-link" onclick="alert('Silakan hubungi Administrator untuk mereset kata sandi Anda.'); return false;">
                            Lupa password?
                        </a>
                    </div>

                    <!-- Tombol Masuk -->
                    <button type="submit" class="btn-masuk">
                        <span>Masuk</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>

                    <!-- Text di bawah tombol -->
                    <div class="sub-button-text">
                        Sistem Administrasi Pembayaran Sekolah
                    </div>
                </form>

            </div>

            <!-- Footer Hak Cipta -->
            <div class="page-footer-copy">
                &copy; {{ date('Y') }} SMK Muhammadiyah Margasari
            </div>

        </div>

    </div>

    <!-- Toggle Password Visibility Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('password');
            const toggleBtn = document.getElementById('togglePasswordBtn');
            const toggleIcon = document.getElementById('togglePasswordIcon');

            if (toggleBtn && passwordInput && toggleIcon) {
                toggleBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        toggleIcon.classList.remove('fa-eye');
                        toggleIcon.classList.add('fa-eye-slash');
                    } else {
                        passwordInput.type = 'password';
                        toggleIcon.classList.remove('fa-eye-slash');
                        toggleIcon.classList.add('fa-eye');
                    }
                });
            }
        });
    </script>
</body>
</html>