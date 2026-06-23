<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Absensi') - PTUN Bandar Lampung</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --primary-light: #818cf8;
            --secondary: #0ea5e9;
            --accent: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #6366f1;
            --dark: #0f172a;
            --sidebar-bg: #0f172a;
            --sidebar-width: 260px;
            --body-bg: #f1f5f9;
            --card-bg: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--body-bg);
            color: #334155;
            overflow-x: hidden;
        }

        /* ─── Sidebar ────────────────────────────────────── */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            z-index: 1000;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-brand h4 {
            color: #ffffff;
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
        }

        .sidebar-brand small {
            color: #94a3b8;
            font-size: 0.75rem;
        }

        .sidebar-brand .brand-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem;
            color: #fff;
            box-shadow: 0 4px 20px rgba(79, 70, 229, 0.4);
            transition: transform 0.3s ease;
        }
        .sidebar-brand .brand-icon:hover {
            transform: scale(1.05);
        }
        .sidebar-brand .brand-icon svg {
            width: 28px;
            height: 28px;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.5;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .sidebar-nav {
            padding: 1rem 0;
            flex: 1;
        }

        .nav-section-title {
            color: #64748b;
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 0.75rem 1.5rem 0.5rem;
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 1.5rem;
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 450;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
            margin: 2px 0;
        }

        .sidebar-nav .nav-link:hover {
            color: #e2e8f0;
            background: rgba(255, 255, 255, 0.05);
            border-left-color: var(--primary-light);
        }

        .sidebar-nav .nav-link.active {
            color: #ffffff;
            background: rgba(79, 70, 229, 0.15);
            border-left-color: var(--primary);
            font-weight: 600;
        }

        .sidebar-nav .nav-link i {
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-footer .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-footer .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .sidebar-footer .user-name {
            color: #e2e8f0;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .sidebar-footer .user-role {
            color: #64748b;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ─── Main Content ───────────────────────────────── */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ─── Topbar ─────────────────────────────────────── */
        .topbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .topbar .page-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0f172a;
        }

        .topbar .breadcrumb {
            font-size: 0.8rem;
            margin-bottom: 0;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-toggle-sidebar {
            display: none;
            background: none;
            border: none;
            font-size: 1.3rem;
            color: #475569;
            cursor: pointer;
            padding: 0.25rem;
        }

        /* ─── Content Area ───────────────────────────────── */
        .content-area {
            padding: 1.5rem;
        }

        /* ─── Cards ──────────────────────────────────────── */
        .stat-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            border-radius: 16px 16px 0 0;
        }

        .stat-card.primary::before { background: linear-gradient(90deg, var(--primary), var(--primary-light)); }
        .stat-card.success::before { background: linear-gradient(90deg, var(--success), #34d399); }
        .stat-card.warning::before { background: linear-gradient(90deg, var(--warning), #fbbf24); }
        .stat-card.danger::before { background: linear-gradient(90deg, var(--danger), #f87171); }
        .stat-card.info::before { background: linear-gradient(90deg, var(--info), var(--secondary)); }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: #fff;
        }

        .stat-card.primary .stat-icon { background: linear-gradient(135deg, var(--primary), var(--primary-light)); }
        .stat-card.success .stat-icon { background: linear-gradient(135deg, var(--success), #34d399); }
        .stat-card.warning .stat-icon { background: linear-gradient(135deg, var(--warning), #fbbf24); }
        .stat-card.danger .stat-icon { background: linear-gradient(135deg, var(--danger), #f87171); }
        .stat-card.info .stat-icon { background: linear-gradient(135deg, var(--info), var(--secondary)); }

        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1;
        }

        .stat-card .stat-label {
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 500;
        }

        /* ─── Custom Card ────────────────────────────────── */
        .card-custom {
            background: var(--card-bg);
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .card-custom .card-header {
            background: transparent;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 1.25rem;
            font-weight: 600;
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-custom .card-body {
            padding: 1.25rem;
        }

        /* ─── Tables ─────────────────────────────────────── */
        .table-custom {
            font-size: 0.85rem;
        }

        .table-custom thead th {
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
            padding: 0.75rem;
            white-space: nowrap;
        }

        .table-custom tbody td {
            padding: 0.75rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }

        .table-custom tbody tr:hover {
            background: #f8fafc;
        }

        /* ─── Badges ─────────────────────────────────────── */
        .badge-status {
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .badge-hadir { background: #dcfce7; color: #166534; }
        .badge-terlambat { background: #fef3c7; color: #92400e; }
        .badge-izin { background: #dbeafe; color: #1e40af; }
        .badge-sakit { background: #fce7f3; color: #9d174d; }
        .badge-alfa { background: #fee2e2; color: #991b1b; }
        .badge-aktif { background: #dcfce7; color: #166534; }
        .badge-nonaktif { background: #fee2e2; color: #991b1b; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-approved { background: #dcfce7; color: #166534; }
        .badge-rejected { background: #fee2e2; color: #991b1b; }

        /* ─── Buttons ────────────────────────────────────── */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 0.5rem 1.25rem;
            border-radius: 10px;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .btn-primary-custom:hover {
            background: linear-gradient(135deg, var(--primary-dark), #312e81);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
        }

        .btn-success-custom {
            background: linear-gradient(135deg, var(--success), #059669);
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 0.5rem 1.25rem;
            border-radius: 10px;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .btn-success-custom:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35);
        }

        .btn-danger-custom {
            background: linear-gradient(135deg, var(--danger), #dc2626);
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 0.5rem 1.25rem;
            border-radius: 10px;
            font-size: 0.85rem;
        }

        .btn-outline-custom {
            border: 1.5px solid #e2e8f0;
            background: transparent;
            color: #475569;
            font-weight: 500;
            padding: 0.45rem 1rem;
            border-radius: 10px;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .btn-outline-custom:hover {
            background: #f8fafc;
            border-color: var(--primary);
            color: var(--primary);
        }

        /* ─── Forms ──────────────────────────────────────── */
        .form-control, .form-select {
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            padding: 0.6rem 1rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
        }

        .form-label {
            font-weight: 600;
            font-size: 0.8rem;
            color: #374151;
            margin-bottom: 0.35rem;
        }

        /* ─── Alerts ─────────────────────────────────────── */
        .alert {
            border-radius: 12px;
            border: none;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        /* ─── Responsive ─────────────────────────────────── */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .btn-toggle-sidebar {
                display: block;
            }

            .sidebar-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
                display: none;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }

        /* ─── Animations ─────────────────────────────────── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse-glow {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
            70% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        @keyframes slide-in {
            from { transform: translateX(-15px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .animate-fade-in {
            animation: fadeInUp 0.5s ease forwards;
        }

        .animate-fade-in:nth-child(2) { animation-delay: 0.1s; }
        .animate-fade-in:nth-child(3) { animation-delay: 0.2s; }
        .animate-fade-in:nth-child(4) { animation-delay: 0.3s; }

        /* ─── Scrollbar ──────────────────────────────────── */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
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
            <h4>PTUN Bandar Lampung</h4>
            <small>Sistem Absensi Digital</small>
        </div>

        <nav class="sidebar-nav">
            @yield('sidebar')
        </nav>

        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar position-relative">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-light rounded-circle" style="animation: pulse-glow 2s infinite;"></span>
                </div>
                <div>
                    <div class="user-name">{{ auth()->user()->name ?? 'User' }}</div>
                    <div class="user-role">{{ auth()->user()->role->name ?? 'Guest' }}</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="mt-2">
                @csrf
                <button type="submit" class="btn btn-sm w-100" style="background: rgba(239,68,68,0.15); color: #ef4444; border: none; border-radius: 8px; font-size: 0.8rem; font-weight: 600;">
                    <i class="bi bi-box-arrow-left me-1"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <div>
                <button class="btn-toggle-sidebar" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <span class="page-title">@yield('page-title', 'Dashboard')</span>
            </div>
            <div class="topbar-actions">
                <span class="badge bg-light text-dark px-3 py-2 border rounded-pill d-flex align-items-center" style="font-size: 0.8rem;">
                    <i class="bi bi-calendar3 text-primary me-2"></i>
                    {{ now()->translatedFormat('l, d F Y') }}
                </span>
                <button class="btn btn-light position-relative rounded-circle ms-2" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-bell text-muted"></i>
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                </button>
            </div>
        </header>

        <!-- Content -->
        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    @foreach($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }

        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('show');
            this.classList.remove('show');
        });
    </script>
    @stack('scripts')
</body>
</html>
