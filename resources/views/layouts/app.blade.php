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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #1a5632;
            --primary-dark: #0d3320;
            --primary-light: #2d7a4a;
            --secondary: #c5a237;
            --secondary-light: #d4b44e;
            --accent: #1a5632;
            --success: #16a34a;
            --warning: #d97706;
            --danger: #dc2626;
            --info: #2563eb;
            --dark: #111827;
            --sidebar-bg: #0d3320;
            --sidebar-width: 264px;
            --body-bg: #f5f5f0;
            --card-bg: #ffffff;
            --text-primary: #1a1a1a;
            --text-secondary: #4b5563;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
            --border-light: #f3f4f6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--body-bg);
            color: var(--text-primary);
            overflow-x: hidden;
            font-size: 0.9375rem;
        }

        /* ================================================================
           SIDEBAR
           ================================================================ */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #1a5632 0%, #0d3320 40%, #091f16 100%);
            z-index: 1000;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-brand img {
            transition: transform 0.3s ease;
        }
        .sidebar-brand img:hover {
            transform: scale(1.05);
        }

        .sidebar-brand h4 {
            color: #ffffff;
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 0.15rem;
        }

        .sidebar-brand small {
            color: var(--secondary);
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .sidebar-nav {
            padding: 0.75rem 0;
            flex: 1;
        }

        .nav-section-title {
            color: rgba(255,255,255,0.4);
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.8px;
            padding: 1rem 1.5rem 0.5rem;
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 1.5rem;
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
            margin: 1px 0;
        }

        .sidebar-nav .nav-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
            border-left-color: var(--secondary);
        }

        .sidebar-nav .nav-link.active {
            color: #ffffff;
            background: rgba(197, 162, 55, 0.15);
            border-left-color: var(--secondary);
            font-weight: 700;
        }

        .sidebar-nav .nav-link i {
            font-size: 1.1rem;
            width: 22px;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
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
            background: linear-gradient(135deg, var(--secondary), #a88a2a);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .sidebar-footer .user-name {
            color: #ffffff;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .sidebar-footer .user-role {
            color: var(--secondary);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .btn-logout-sidebar {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            margin-top: 0.75rem;
            padding: 0.5rem;
            background: rgba(220, 38, 38, 0.12);
            color: #fca5a5;
            border: none;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .btn-logout-sidebar:hover {
            background: rgba(220, 38, 38, 0.25);
            color: #fecaca;
        }

        /* ================================================================
           MAIN CONTENT
           ================================================================ */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ================================================================
           TOPBAR
           ================================================================ */
        .topbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .topbar .page-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .topbar-date {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: var(--primary);
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .btn-toggle-sidebar {
            display: none;
            background: none;
            border: none;
            font-size: 1.3rem;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.25rem;
        }

        /* ================================================================
           CONTENT AREA
           ================================================================ */
        .content-area {
            padding: 1.5rem;
        }

        /* ================================================================
           STAT CARDS
           ================================================================ */
        .stat-card {
            background: var(--card-bg);
            border-radius: 14px;
            padding: 1.5rem;
            border: 1px solid var(--border-color);
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
            height: 3px;
            border-radius: 14px 14px 0 0;
        }

        .stat-card.primary::before { background: linear-gradient(90deg, var(--primary), var(--primary-light)); }
        .stat-card.success::before { background: linear-gradient(90deg, var(--success), #22c55e); }
        .stat-card.warning::before { background: linear-gradient(90deg, var(--warning), #f59e0b); }
        .stat-card.danger::before  { background: linear-gradient(90deg, var(--danger), #ef4444); }
        .stat-card.info::before    { background: linear-gradient(90deg, var(--info), #3b82f6); }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #fff;
        }

        .stat-card.primary .stat-icon { background: linear-gradient(135deg, var(--primary), var(--primary-light)); }
        .stat-card.success .stat-icon { background: linear-gradient(135deg, var(--success), #22c55e); }
        .stat-card.warning .stat-icon { background: linear-gradient(135deg, var(--warning), #f59e0b); }
        .stat-card.danger .stat-icon  { background: linear-gradient(135deg, var(--danger), #ef4444); }
        .stat-card.info .stat-icon    { background: linear-gradient(135deg, var(--info), #3b82f6); }

        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1;
        }

        .stat-card .stat-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* ================================================================
           CUSTOM CARD
           ================================================================ */
        .card-custom {
            background: var(--card-bg);
            border-radius: 14px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .card-custom .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.9rem;
        }

        .card-custom .card-body {
            padding: 1.25rem;
        }

        /* ================================================================
           TABLES
           ================================================================ */
        .table-custom {
            font-size: 0.875rem;
        }

        .table-custom thead th {
            background: #f8faf8;
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--border-color);
            padding: 0.75rem;
            white-space: nowrap;
        }

        .table-custom tbody td {
            padding: 0.75rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-light);
            color: var(--text-primary);
        }

        .table-custom tbody tr:hover {
            background: #fafaf8;
        }

        /* ================================================================
           BADGES
           ================================================================ */
        .badge-status {
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            display: inline-block;
        }

        .badge-hadir      { background: #dcfce7; color: #166534; }
        .badge-terlambat   { background: #fef3c7; color: #92400e; }
        .badge-izin        { background: #dbeafe; color: #1e40af; }
        .badge-sakit       { background: #fce7f3; color: #9d174d; }
        .badge-alfa        { background: #fee2e2; color: #991b1b; }
        .badge-aktif       { background: #dcfce7; color: #166534; }
        .badge-nonaktif    { background: #fee2e2; color: #991b1b; }
        .badge-pending     { background: #fef3c7; color: #92400e; }
        .badge-approved    { background: #dcfce7; color: #166534; }
        .badge-rejected    { background: #fee2e2; color: #991b1b; }

        /* ── Period / Contract Status Badges ─────────────────────── */
        .badge-period-status {
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            transition: all 0.2s ease;
        }

        /* Belum Mulai (Not Started) - Slate/Gray */
        .badge-belum-mulai {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        /* Sedang Aktif (Active / In Progress) - Green */
        .badge-sedang-aktif {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        /* Selesai / Berakhir (Completed / Ended) - Red-Orange */
        .badge-selesai {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* ================================================================
           BUTTONS
           ================================================================ */
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
            background: linear-gradient(135deg, var(--primary-dark), #071f13);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(26, 86, 50, 0.3);
        }

        .btn-success-custom {
            background: linear-gradient(135deg, var(--success), #15803d);
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
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
        }

        .btn-danger-custom {
            background: linear-gradient(135deg, var(--danger), #b91c1c);
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 0.5rem 1.25rem;
            border-radius: 10px;
            font-size: 0.85rem;
        }

        .btn-outline-custom {
            border: 1.5px solid var(--border-color);
            background: transparent;
            color: var(--text-secondary);
            font-weight: 500;
            padding: 0.45rem 1rem;
            border-radius: 10px;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .btn-outline-custom:hover {
            background: #f0fdf4;
            border-color: var(--primary);
            color: var(--primary);
        }

        /* ================================================================
           FORMS
           ================================================================ */
        .form-control, .form-select {
            border-radius: 10px;
            border: 1.5px solid var(--border-color);
            padding: 0.6rem 1rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            color: var(--text-primary);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26, 86, 50, 0.1);
        }

        .form-label {
            font-weight: 600;
            font-size: 0.8rem;
            color: #374151;
            margin-bottom: 0.35rem;
        }

        /* ================================================================
           ALERTS
           ================================================================ */
        .alert {
            border-radius: 12px;
            border: none;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* ================================================================
           RESPONSIVE
           ================================================================ */
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

        /* ================================================================
           ANIMATIONS
           ================================================================ */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse-glow {
            0%   { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.4); }
            70%  { box-shadow: 0 0 0 6px rgba(22, 163, 74, 0); }
            100% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0); }
        }

        .animate-fade-in {
            animation: fadeInUp 0.4s ease forwards;
        }

        .animate-fade-in:nth-child(2) { animation-delay: 0.08s; }
        .animate-fade-in:nth-child(3) { animation-delay: 0.16s; }
        .animate-fade-in:nth-child(4) { animation-delay: 0.24s; }

        /* ================================================================
           SCROLLBAR
           ================================================================ */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #d1d5db;
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
            <img src="{{ asset('images/logo.png') }}" alt="Logo PTUN" style="height: 56px; margin-bottom: 0.75rem;">
            <h4>PTUN Bandar Lampung</h4>
            <small>Sistem Absensi Digital</small>
        </div>

        <nav class="sidebar-nav">
            @yield('sidebar')
        </nav>

        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <div class="user-name">{{ auth()->user()->name ?? 'User' }}</div>
                    <div class="user-role">{{ auth()->user()->role->name ?? 'Guest' }}</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout-sidebar">
                    <i class="bi bi-box-arrow-left"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <div class="d-flex align-items-center gap-2">
                <button class="btn-toggle-sidebar" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <span class="page-title">@yield('page-title', 'Dashboard')</span>
            </div>
            <div class="topbar-actions">
                <span class="topbar-date">
                    <i class="bi bi-calendar3"></i>
                    {{ now()->translatedFormat('l, d F Y') }}
                </span>
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
                    <i class="bi bi-exclamation-triangle me-2"></i>
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
