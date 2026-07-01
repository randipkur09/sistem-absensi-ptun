@extends('layouts.app')

@section('sidebar')
    <div class="nav-section-title">Menu Utama</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i> Dashboard
    </a>

    <div class="nav-section-title mt-3">Manajemen Data</div>
    <a href="{{ route('admin.outsourcing.index') }}" class="nav-link {{ request()->routeIs('admin.outsourcing.*') ? 'active' : '' }}">
        <i class="bi bi-people-fill"></i> Outsourcing
    </a>
    <a href="{{ route('admin.internship.index') }}" class="nav-link {{ request()->routeIs('admin.internship.*') ? 'active' : '' }}">
        <i class="bi bi-mortarboard-fill"></i> Peserta Magang
    </a>
    <a href="{{ route('admin.shifts.index') }}" class="nav-link {{ request()->routeIs('admin.shifts.*') ? 'active' : '' }}">
        <i class="bi bi-calendar-week-fill"></i> Jadwal Shift Satpam
    </a>

    <div class="nav-section-title mt-3">Absensi</div>
    <a href="{{ route('admin.attendance.index') }}" class="nav-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
        <i class="bi bi-clipboard-check-fill"></i> Data Absensi
    </a>

    <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-bar-graph-fill"></i> Laporan
    </a>

    <div class="nav-section-title mt-3">Sistem</div>
    <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
        <i class="bi bi-gear-fill"></i> Pengaturan
    </a>
@endsection
