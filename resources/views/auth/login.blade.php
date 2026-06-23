<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Absensi PTUN Bandar Lampung</title>
    <meta name="description" content="Sistem Absensi Digital Pengadilan Tata Usaha Negara Bandar Lampung">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background: #f5f5f0;
        }

        .login-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* --- Left Branding Panel --- */
        .brand-panel {
            width: 45%;
            background: linear-gradient(160deg, #1a5632 0%, #0d3320 60%, #0a2819 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 3rem;
        }

        .brand-panel::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -20%;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(197,162,55,0.12) 0%, transparent 70%);
        }

        .brand-panel::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -15%;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(197,162,55,0.08) 0%, transparent 70%);
        }

        .brand-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .brand-logo {
            width: 140px;
            height: auto;
            margin-bottom: 2rem;
            filter: drop-shadow(0 8px 24px rgba(0,0,0,0.3));
            animation: logoFloat 6s ease-in-out infinite;
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .brand-title {
            color: #ffffff;
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.3px;
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }

        .brand-subtitle {
            color: #c5a237;
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 2.5rem;
        }

        .brand-divider {
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, transparent, #c5a237, transparent);
            margin: 0 auto 2rem;
            border-radius: 2px;
        }

        .brand-quote {
            color: rgba(255,255,255,0.6);
            font-size: 0.85rem;
            font-weight: 400;
            line-height: 1.7;
            max-width: 320px;
        }

        .brand-motto {
            color: #c5a237;
            font-weight: 700;
            font-style: italic;
            font-size: 0.95rem;
            margin-top: 1.5rem;
        }

        /* --- Right Form Panel --- */
        .form-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: #ffffff;
        }

        .form-container {
            width: 100%;
            max-width: 420px;
            animation: formSlideIn 0.6s ease-out;
        }

        @keyframes formSlideIn {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-header {
            margin-bottom: 2.5rem;
        }

        .form-header h2 {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 0.5rem;
        }

        .form-header p {
            color: #6b7280;
            font-size: 0.95rem;
            font-weight: 400;
        }

        /* --- Form Inputs --- */
        .input-group-custom {
            margin-bottom: 1.25rem;
        }

        .input-group-custom label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 1.1rem;
            transition: color 0.2s ease;
        }

        .input-wrapper input {
            width: 100%;
            padding: 0.85rem 1rem 0.85rem 3rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.95rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1a1a1a;
            background: #fafafa;
            transition: all 0.25s ease;
            outline: none;
        }

        .input-wrapper input::placeholder {
            color: #9ca3af;
        }

        .input-wrapper input:focus {
            border-color: #1a5632;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(26,86,50,0.1);
        }

        .input-wrapper input:focus + i,
        .input-wrapper input:focus ~ i {
            color: #1a5632;
        }

        .input-wrapper:has(input:focus) i {
            color: #1a5632;
        }

        .btn-login {
            width: 100%;
            padding: 0.9rem;
            background: linear-gradient(135deg, #1a5632 0%, #0d3320 100%);
            border: none;
            border-radius: 12px;
            color: #ffffff;
            font-size: 1rem;
            font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 0.75rem;
            letter-spacing: 0.3px;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #0d3320 0%, #1a5632 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(26,86,50,0.3);
            color: #ffffff;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* --- Alert --- */
        .alert-custom-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            border-radius: 12px;
            padding: 0.85rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }

        .alert-custom-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            border-radius: 12px;
            padding: 0.85rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }

        .form-footer {
            text-align: center;
            margin-top: 2.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #f3f4f6;
        }

        .form-footer p {
            color: #9ca3af;
            font-size: 0.8rem;
        }

        /* --- Mobile Logo (hidden on desktop) --- */
        .mobile-brand {
            display: none;
            text-align: center;
            margin-bottom: 2rem;
        }

        .mobile-brand img {
            height: 64px;
            margin-bottom: 0.75rem;
        }

        .mobile-brand h5 {
            font-weight: 700;
            color: #1a5632;
            font-size: 1.1rem;
            margin-bottom: 0.15rem;
        }

        .mobile-brand span {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* --- Responsive --- */
        @media (max-width: 991.98px) {
            .brand-panel {
                display: none;
            }

            .form-panel {
                padding: 1.5rem;
                background: linear-gradient(160deg, #1a5632 0%, #0d3320 60%, #0a2819 100%);
            }

            .mobile-brand {
                display: block;
            }

            .form-container {
                max-width: 450px;
                margin: 0 auto;
                background: #ffffff;
                padding: 2.5rem 2rem;
                border-radius: 24px;
                box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            }
        }

        @media (max-width: 575.98px) {
            .form-container {
                padding: 2rem 1.5rem;
            }
            
            .form-header h2 {
                font-size: 1.35rem;
            }
            
            .mobile-brand img {
                height: 56px;
            }
            
            .mobile-brand h5 {
                font-size: 1rem;
            }
            
            .form-header {
                margin-bottom: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <!-- Left Branding Panel -->
        <div class="brand-panel">
            <div class="brand-content">
                <img src="{{ asset('images/logo.png') }}" alt="Logo PTUN Bandar Lampung" class="brand-logo">
                <h1 class="brand-title">Pengadilan Tata Usaha Negara<br>Bandar Lampung</h1>
                <div class="brand-divider"></div>
                <div class="brand-subtitle">Sistem Absensi Digital</div>
                <p class="brand-quote">Mengelola kehadiran pegawai secara akurat, transparan, dan efisien dengan teknologi digital.</p>
                <p class="brand-motto">"Dharma Mayukti"</p>
            </div>
        </div>

        <!-- Right Form Panel -->
        <div class="form-panel">
            <div class="form-container">
                <!-- Mobile Logo -->
                <div class="mobile-brand">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo PTUN">
                    <h5>PTUN Bandar Lampung</h5>
                    <span>Sistem Absensi Digital</span>
                </div>

                <div class="form-header">
                    <h2>Selamat Datang</h2>
                    <p>Silakan masuk untuk melanjutkan ke sistem absensi.</p>
                </div>

                @if(session('success'))
                    <div class="alert-custom-success">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert-custom-error">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        @foreach($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf
                    <div class="input-group-custom">
                        <label for="email">Alamat Email</label>
                        <div class="input-wrapper">
                            <input type="email" id="email" name="email" placeholder="Masukkan email anda" value="{{ old('email') }}" required autofocus>
                            <i class="bi bi-envelope"></i>
                        </div>
                    </div>

                    <div class="input-group-custom">
                        <label for="password">Kata Sandi</label>
                        <div class="input-wrapper">
                            <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" required>
                            <i class="bi bi-lock"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn-login" id="btnLogin">
                        Masuk ke Sistem
                    </button>
                </form>

                <div class="form-footer">
                    <p>&copy; {{ date('Y') }} PTUN Bandar Lampung. Seluruh hak dilindungi.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
