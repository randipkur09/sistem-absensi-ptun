<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Absensi PTUN Bandar Lampung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 40%, rgba(79,70,229,0.15) 0%, transparent 50%),
                        radial-gradient(circle at 70% 60%, rgba(6,182,212,0.1) 0%, transparent 50%);
            animation: bgShift 15s ease-in-out infinite alternate;
        }
        @keyframes bgShift {
            0% { transform: translate(0, 0); }
            100% { transform: translate(-5%, -3%); }
        }
        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
            padding: 1rem;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .login-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.8s ease forwards;
        }
        .login-brand {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-brand .brand-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #4f46e5, #06b6d4);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: #fff;
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
            animation: pulse-glow 3s infinite;
        }
        @keyframes pulse-glow {
            0% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.4); }
            70% { box-shadow: 0 0 0 15px rgba(79, 70, 229, 0); }
            100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); }
        }
        .login-brand .brand-icon svg {
            width: 38px;
            height: 38px;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.5;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        .login-brand h3 {
            color: #f1f5f9;
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 0.25rem;
        }
        .login-brand p {
            color: #64748b;
            font-size: 0.85rem;
        }
        .form-floating {
            margin-bottom: 1rem;
        }
        .form-floating .form-control {
            background: rgba(255, 255, 255, 0.06);
            border: 1.5px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: #e2e8f0;
            font-size: 0.9rem;
            padding: 1rem 1rem;
            height: calc(3.5rem + 2px);
        }
        .form-floating .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
            color: #f1f5f9;
        }
        .form-floating label {
            color: #94a3b8;
            font-size: 0.85rem;
        }
        .form-floating .form-control:focus ~ label,
        .form-floating .form-control:not(:placeholder-shown) ~ label {
            color: #818cf8;
        }
        .btn-login {
            background: linear-gradient(135deg, #4f46e5, #3730a3);
            border: none;
            color: #fff;
            font-weight: 700;
            padding: 0.85rem;
            border-radius: 12px;
            font-size: 0.95rem;
            width: 100%;
            transition: all 0.3s ease;
            letter-spacing: 0.3px;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #3730a3, #312e81);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
        }
        .alert {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            border-radius: 12px;
            font-size: 0.85rem;
        }
        .floating-shapes div {
            position: absolute;
            border-radius: 50%;
            opacity: 0.05;
            background: #4f46e5;
        }
        .floating-shapes .shape-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
        .floating-shapes .shape-2 { width: 200px; height: 200px; bottom: -50px; left: -80px; }
        .floating-shapes .shape-3 { width: 150px; height: 150px; bottom: 30%; right: 10%; }
    </style>
</head>
<body>
    <div class="floating-shapes">
        <div class="shape-1"></div>
        <div class="shape-2"></div>
        <div class="shape-3"></div>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-brand">
                <div class="brand-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M3 21h18"></path>
                        <path d="M5 21v-4"></path>
                        <path d="M19 21v-4"></path>
                        <path d="M6 7l6-4 6 4"></path>
                        <path d="M5 11h14"></path>
                        <path d="M6 11v6"></path>
                        <path d="M10 11v6"></path>
                        <path d="M14 11v6"></path>
                        <path d="M18 11v6"></path>
                        <path d="M12 7v4"></path>
                    </svg>
                </div>
                <h3>PTUN Bandar Lampung</h3>
                <p>Sistem Absensi Digital</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success mb-3" style="background: rgba(16,185,129,0.15); border-color: rgba(16,185,129,0.3); color: #6ee7b7;">
                    <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert mb-3">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    @foreach($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-floating">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
                    <label for="email"><i class="bi bi-envelope me-1"></i> Email</label>
                </div>

                <div class="form-floating">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password"><i class="bi bi-lock me-1"></i> Password</label>
                </div>

                <button type="submit" class="btn btn-login mt-3">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Masuk
                </button>
            </form>
        </div>

        <p style="text-align: center; color: #475569; font-size: 0.75rem; margin-top: 1.5rem;">
            &copy; {{ date('Y') }} PTUN Bandar Lampung. All rights reserved.
        </p>
    </div>
</body>
</html>
