@extends('layouts.app')

@section('sidebar')
    <div class="nav-section-title">Menu Utama</div>
    <a href="{{ route('employee.dashboard') }}" class="nav-link {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i> Dashboard
    </a>

    <div class="nav-section-title mt-3">Absensi</div>
    <a href="{{ route('employee.attendance.index') }}" class="nav-link {{ request()->routeIs('employee.attendance.*') ? 'active' : '' }}">
        <i class="bi bi-camera-fill"></i> Absensi
    </a>
    <a href="{{ route('employee.history.index') }}" class="nav-link {{ request()->routeIs('employee.history.*') ? 'active' : '' }}">
        <i class="bi bi-clock-history"></i> Riwayat
    </a>

    <div class="nav-section-title mt-3">Pengajuan</div>
    <a href="{{ route('employee.permissions.index') }}" class="nav-link {{ request()->routeIs('employee.permissions.*') ? 'active' : '' }}">
        <i class="bi bi-envelope-paper-fill"></i> Izin/Sakit
        @php
            $myPending = \App\Models\Permission::where('user_id', auth()->id())->where('status_approval', 'pending')->count();
        @endphp
        @if($myPending > 0)
            <span class="badge rounded-pill ms-auto" style="background: var(--secondary); color: #1a1a1a; font-size: 0.6rem; font-weight: 700;">{{ $myPending }} Proses</span>
        @endif
    </a>
@endsection
