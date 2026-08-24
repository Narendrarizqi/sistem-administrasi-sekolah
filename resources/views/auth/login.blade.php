<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Pembayaran Sekolah</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --primary-green: #1b7a43;
            --primary-green-hover: #156d3a;
            --dark-green-start: #1a7440;
            --dark-green-mid: #135b31;
            --dark-green-end: #0c4222;
            --accent-mint: #4ade80;
            --bg-page: #edf2f7;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --border-color: #cbd5e1;
        }

        html, body {
            height: 100%;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(135deg, #eef3f7 0%, #e2e8f0 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 16px 20px 12px;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* Container Card */
        .login-main-card {
            width: 100%;
            max-width: 980px;
            background: #ffffff;
            border-radius: 22px;
            box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.08), 0 1px 3px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(226, 232, 240, 0.8);
            display: flex;
            overflow: hidden;
            position: relative;
        }

        /* Left Side: Branding / Visual */
        .login-branding-panel {
            flex: 1 1 50%;
            background: linear-gradient(155deg, var(--dark-green-start) 0%, var(--dark-green-mid) 50%, var(--dark-green-end) 100%);
            position: relative;
            padding: 34px 34px 28px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #ffffff;
            overflow: hidden;
        }

        /* Decorative Dot Matrix */
        .dots-grid {
            position: absolute;
            display: grid;
            gap: 6px;
            pointer-events: none;
            opacity: 0.25;
            z-index: 1;
        }

        .dots-grid.top-right {
            grid-template-columns: repeat(4, 5px);
            top: 26px;
            right: 30px;
        }

        .dots-grid.bottom-left {
            grid-template-columns: repeat(4, 5px);
            bottom: 28px;
            left: 28px;
            opacity: 0.2;
        }

        .dot {
            width: 5px;
            height: 5px;
            background: #ffffff;
            border-radius: 50%;
        }

        /* Mosque / Minaret Silhouette */
        .mosque-silhouette {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            height: 110px;
            pointer-events: none;
            opacity: 0.09;
            z-index: 1;
        }

        .branding-content {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 350px;
        }

        .school-logo-wrapper {
            position: relative;
            margin-bottom: 12px;
        }

        .school-logo-wrapper::before {
            content: '';
            position: absolute;
            inset: -7px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.22) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            filter: blur(4px);
        }

        .school-logo {
            width: 74px;
            height: 74px;
            object-fit: contain;
            filter: drop-shadow(0 5px 14px rgba(0, 0, 0, 0.25));
            position: relative;
            z-index: 1;
        }

        .school-title {
            font-size: 21px;
            font-weight: 800;
            line-height: 1.22;
            letter-spacing: -0.02em;
            color: #ffffff;
            margin-bottom: 4px;
        }

        .school-subtitle {
            font-size: 14px;
            font-weight: 700;
            color: var(--accent-mint);
            letter-spacing: -0.01em;
            margin-bottom: 8px;
        }

        .diamond-separator {
            display: inline-block;
            font-size: 8px;
            color: var(--accent-mint);
            opacity: 0.8;
            margin-bottom: 8px;
        }

        .school-desc {
            font-size: 12.5px;
            color: rgba(255, 255, 255, 0.86);
            line-height: 1.5;
            max-width: 310px;
            margin-bottom: 18px;
        }

        /* Features List Box */
        .features-glass-box {
            width: 100%;
            background: rgba(0, 0, 0, 0.16);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 14px;
            padding: 13px 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            text-align: left;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .feature-icon-circle {
            width: 32px;
            height: 32px;
            flex-shrink: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 13px;
            transition: all 0.2s ease;
        }

        .feature-item:hover .feature-icon-circle {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.05);
        }

        .feature-text-group {
            display: flex;
            flex-direction: column;
        }

        .feature-head {
            font-size: 12px;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.25;
        }

        .feature-sub {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.72);
            line-height: 1.25;
            margin-top: 1px;
        }

        /* Right Side: Form Panel */
        .login-form-panel {
            flex: 1 1 50%;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px 24px;
        }

        /* Inner Floating Form Card */
        .form-floating-card {
            width: 100%;
            max-width: 360px;
            background: #ffffff;
            border-radius: 18px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 8px 26px rgba(15, 23, 42, 0.05), 0 1px 3px rgba(0, 0, 0, 0.02);
            padding: 26px 28px 22px;
            text-align: center;
        }

        .shield-badge-top {
            width: 38px;
            height: 38px;
            margin: 0 auto 10px;
            border-radius: 50%;
            background: #ecfdf5;
            border: 1px solid #d1fae5;
            color: #16a34a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
        }

        .card-title {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
            margin-bottom: 3px;
        }

        .card-subtitle {
            font-size: 12.5px;
            color: var(--text-muted);
            margin-bottom: 18px;
        }

        /* Alert Error */
        .alert-error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            font-size: 12px;
            font-weight: 500;
            padding: 8px 12px;
            border-radius: 8px;
            margin-bottom: 14px;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 6px;
            animation: shake 0.35s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-4px); }
            75% { transform: translateX(4px); }
        }

        /* Form Controls */
        .form-group {
            text-align: left;
            margin-bottom: 13px;
        }

        .form-label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 5px;
        }

        .input-group-custom {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon-left {
            position: absolute;
            left: 12px;
            color: #94a3b8;
            font-size: 13px;
            pointer-events: none;
            transition: color 0.15s ease;
        }

        .form-control-custom {
            width: 100%;
            height: 42px;
            padding: 0 38px 0 36px;
            background: #ffffff;
            border: 1.5px solid var(--border-color);
            border-radius: 9px;
            font-family: inherit;
            font-size: 13.5px;
            color: #0f172a;
            outline: none;
            transition: all 0.18s ease;
        }

        .form-control-custom:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(27, 122, 67, 0.14);
        }

        .input-group-custom:focus-within .input-icon-left {
            color: var(--primary-green);
        }

        .password-toggle-btn {
            position: absolute;
            right: 10px;
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.15s ease;
        }

        .password-toggle-btn:hover {
            color: #475569;
        }

        /* Checkbox & Forgot Link */
        .form-options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 2px 0 16px;
            font-size: 12px;
        }

        .remember-checkbox-label {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #475569;
            cursor: pointer;
            user-select: none;
        }

        .remember-checkbox-label input {
            width: 14px;
            height: 14px;
            accent-color: var(--primary-green);
            cursor: pointer;
        }

        .forgot-password-link {
            color: var(--primary-green);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.15s ease;
        }

        .forgot-password-link:hover {
            color: var(--primary-green-hover);
            text-decoration: underline;
        }

        /* Submit Button */
        .btn-submit-login {
            width: 100%;
            height: 43px;
            background: var(--primary-green);
            color: #ffffff;
            border: none;
            border-radius: 9px;
            font-family: inherit;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(27, 122, 67, 0.25);
            transition: all 0.18s ease;
        }

        .btn-submit-login:hover {
            background: var(--primary-green-hover);
            transform: translateY(-1px);
            box-shadow: 0 5px 16px rgba(27, 122, 67, 0.34);
        }

        .btn-submit-login:active {
            transform: translateY(0);
        }

        /* Divider text */
        .divider-admin-text {
            margin-top: 18px;
            position: relative;
            text-align: center;
        }

        .divider-admin-text::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: #e2e8f0;
            z-index: 1;
        }

        .divider-admin-text span {
            position: relative;
            z-index: 2;
            background: #ffffff;
            padding: 0 10px;
            color: #94a3b8;
            font-size: 11px;
            font-weight: 500;
        }

        /* Bottom Footer */
        .page-bottom-footer {
            margin-top: 12px;
            text-align: center;
            color: #94a3b8;
            font-size: 12px;
            line-height: 1.4;
        }

        /* Responsive Mobile Layout (small screens) */
        @media (max-width: 860px) {
            body {
                padding: 20px 16px;
                min-height: 100vh;
                height: auto;
            }

            .login-main-card {
                flex-direction: column;
                max-width: 440px;
                border-radius: 20px;
            }

            .login-branding-panel {
                padding: 30px 22px 24px;
            }

            .dots-grid {
                display: none;
            }

            .school-desc {
                margin-bottom: 14px;
            }

            .features-glass-box {
                padding: 10px 12px;
                gap: 8px;
            }

            .login-form-panel {
                padding: 22px 16px;
                background: #ffffff;
            }

            .form-floating-card {
                border: none;
                box-shadow: none;
                padding: 10px 4px;
            }
        }
    </style>
</head>
<body>

    <!-- Main Login Split Card -->
    <div class="login-main-card">

        <!-- Left Visual / Branding Panel -->
        <div class="login-branding-panel">
            
            <!-- Top Right Dot Matrix -->
            <div class="dots-grid top-right">
                <div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div>
                <div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div>
                <div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div>
                <div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div>
            </div>

            <!-- Bottom Left Dot Matrix -->
            <div class="dots-grid bottom-left">
                <div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div>
                <div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div>
                <div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div>
            </div>

            <!-- Mosque Silhouette SVG Background -->
            <svg class="mosque-silhouette" viewBox="0 0 500 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <path fill="#ffffff" d="M0,120 L0,85 Q15,85 20,80 L20,30 Q22,20 25,10 Q28,20 30,30 L30,80 Q35,85 50,85 L70,85 Q85,85 90,80 L90,55 Q95,45 100,35 Q105,45 110,55 L110,80 Q115,85 130,85 L150,85 Q160,85 165,75 Q180,40 210,35 Q220,15 250,5 Q280,15 290,35 Q320,40 335,75 Q340,85 350,85 L370,85 Q385,85 390,80 L390,55 Q395,45 400,35 Q405,45 410,55 L410,80 Q415,85 430,85 L450,85 Q465,85 470,80 L470,30 Q472,20 475,10 Q478,20 480,30 L480,80 Q485,85 500,85 L500,120 Z"/>
            </svg>

            <!-- Center Content -->
            <div class="branding-content">
                <div class="school-logo-wrapper">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo SMK Muhammadiyah Margasari" class="school-logo">
                </div>

                <h1 class="school-title">
                    SMK Muhammadiyah<br>Margasari
                </h1>

                <div class="school-subtitle">
                    Sistem Pembayaran Sekolah
                </div>

                <div class="diamond-separator">
                    ✦
                </div>

                <p class="school-desc">
                    Kelola seluruh pembayaran siswa dalam satu tempat, cepat, aman dan terstruktur.
                </p>

                <!-- 3 Features Pill Container -->
                <div class="features-glass-box">
                    <div class="feature-item">
                        <div class="feature-icon-circle">
                            <i class="far fa-credit-card"></i>
                        </div>
                        <div class="feature-text-group">
                            <span class="feature-head">Pembayaran IPP &amp; Daftar Ulang</span>
                            <span class="feature-sub">Kelola tagihan dengan mudah</span>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon-circle">
                            <i class="fas fa-landmark"></i>
                        </div>
                        <div class="feature-text-group">
                            <span class="feature-head">Sarana &amp; Prasarana &amp; KI</span>
                            <span class="feature-sub">Transaksi lebih rapi dan terorganisir</span>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon-circle">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="feature-text-group">
                            <span class="feature-head">Rekap &amp; Laporan Otomatis</span>
                            <span class="feature-sub">Laporan akurat kapan pun dibutuhkan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form Panel -->
        <div class="login-form-panel">
            <div class="form-floating-card">

                <!-- Shield Badge Icon -->
                <div class="shield-badge-top">
                    <i class="fas fa-shield-halved"></i>
                </div>

                <h2 class="card-title">Selamat Datang Kembali</h2>
                <p class="card-subtitle">Masuk ke sistem administrasi sekolah</p>

                @if($errors->any())
                    <div class="alert-error-box">
                        <i class="fas fa-circle-exclamation"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('login.process') }}" method="POST">
                    @csrf

                    <!-- Username Field -->
                    <div class="form-group">
                        <label class="form-label" for="username">Username</label>
                        <div class="input-group-custom">
                            <i class="far fa-user input-icon-left"></i>
                            <input 
                                type="text" 
                                name="username" 
                                id="username"
                                class="form-control-custom" 
                                placeholder="admin" 
                                value="{{ old('username') }}" 
                                required 
                                autofocus
                            >
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <div class="input-group-custom">
                            <i class="fas fa-lock input-icon-left"></i>
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                class="form-control-custom" 
                                placeholder="••••••••••" 
                                required
                            >
                            <button type="button" class="password-toggle-btn" id="togglePasswordBtn" title="Tampilkan/Sembunyikan password">
                                <i class="far fa-eye" id="toggleEyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Options: Remember & Forgot -->
                    <div class="form-options-row">
                        <label class="remember-checkbox-label">
                            <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                            <span>Ingat saya</span>
                        </label>

                        <a href="{{ route('password.request') }}" class="forgot-password-link">
                            Lupa password?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit-login">
                        <i class="fas fa-right-to-bracket"></i>
                        <span>Masuk</span>
                    </button>

                </form>

                <!-- Footer Divider -->
                <div class="divider-admin-text">
                    <span>Sistem Administrasi Sekolah</span>
                </div>

            </div>
        </div>

    </div>

    <!-- Outer Bottom Copyright Footer -->
    <footer class="page-bottom-footer">
        &copy; {{ date('Y') }} Bagus Narendra Rizqi Ananto. Semua hak dilindungi.
    </footer>

    <!-- Password Toggle Script -->
    <script>
        const passwordInput = document.getElementById('password');
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const toggleIcon = document.getElementById('toggleEyeIcon');

        if (toggleBtn && passwordInput && toggleIcon) {
            toggleBtn.addEventListener('click', function () {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleIcon.classList.remove('fa-eye');
                    toggleIcon.classList.add('fa-eye-slash');
                    toggleBtn.title = 'Sembunyikan password';
                } else {
                    passwordInput.type = 'password';
                    toggleIcon.classList.remove('fa-eye-slash');
                    toggleIcon.classList.add('fa-eye');
                    toggleBtn.title = 'Tampilkan password';
                }
            });
        }
    </script>

</body>
</html>