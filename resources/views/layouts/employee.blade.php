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


@endsection
