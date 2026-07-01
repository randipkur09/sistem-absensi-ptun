@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
<div class="row mb-4 animate-fade-in">
    <div class="col-12">
        <h4 class="fw-bold" style="color: var(--text-primary);">
            @php
                $hour = now()->format('H');
                if ($hour < 12) $greeting = 'Selamat Pagi';
                elseif ($hour < 15) $greeting = 'Selamat Siang';
                elseif ($hour < 18) $greeting = 'Selamat Sore';
                else $greeting = 'Selamat Malam';
            @endphp
            {{ $greeting }}, {{ auth()->user()->name ?? 'Admin' }}
        </h4>
        <p class="text-muted mb-0">Berikut adalah ringkasan sistem absensi hari ini.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <!-- Stat Cards -->
    <div class="col-xl-3 col-md-6 animate-fade-in">
        <div class="stat-card primary">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Total Pegawai</div>
                    <div class="stat-value">{{ $totalUsers }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 animate-fade-in">
        <div class="stat-card success">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Outsourcing Aktif</div>
                    <div class="stat-value">{{ $totalOutsourcing }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-person-badge-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 animate-fade-in">
        <div class="stat-card info">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Peserta Magang</div>
                    <div class="stat-value">{{ $totalMagang }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-mortarboard-fill"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- Today's Attendance Stats -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header">
                <span><i class="bi bi-calendar-check me-2"></i>Absensi Hari Ini</span>
                <span class="badge bg-light text-dark" style="font-size: 0.75rem;">{{ now()->translatedFormat('d F Y') }}</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <div class="text-center p-3 rounded-3" style="background: #f0fdf4;">
                            <div style="font-size: 2rem; font-weight: 800; color: #166534;">{{ $hadirToday }}</div>
                            <div style="font-size: 0.8rem; color: #166534; font-weight: 600;">Hadir</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="text-center p-3 rounded-3" style="background: #fffbeb;">
                            <div style="font-size: 2rem; font-weight: 800; color: #92400e;">{{ $terlambatToday }}</div>
                            <div style="font-size: 0.8rem; color: #92400e; font-weight: 600;">Terlambat</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="text-center p-3 rounded-3" style="background: #eff6ff;">
                            <div style="font-size: 2rem; font-weight: 800; color: #1e40af;">{{ $izinToday }}</div>
                            <div style="font-size: 0.8rem; color: #1e40af; font-weight: 600;">Izin</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="text-center p-3 rounded-3" style="background: #fdf2f8;">
                            <div style="font-size: 2rem; font-weight: 800; color: #9d174d;">{{ $sakitToday }}</div>
                            <div style="font-size: 0.8rem; color: #9d174d; font-weight: 600;">Sakit</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Recap & Recent -->
<div class="row g-3">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header">
                <span><i class="bi bi-clock-history me-2"></i>Absensi Terbaru</span>
                <a href="{{ route('admin.attendance.index') }}" class="btn btn-sm btn-outline-custom">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Tipe</th>
                                <th>Tanggal</th>
                                <th>Masuk</th>
                                <th>Pulang</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAttendances as $att)
                            <tr>
                                <td class="fw-semibold">{{ $att->user->name ?? '-' }}</td>
                                <td><span class="badge bg-light text-dark">{{ ucfirst($att->user->employee_type ?? '-') }}</span></td>
                                <td>{{ $att->tanggal->format('d/m/Y') }}</td>
                                <td>{{ $att->jam_masuk ?? '-' }}</td>
                                <td>{{ $att->jam_pulang ?? '-' }}</td>
                                <td><span class="badge-status badge-{{ $att->status }}">{{ ucfirst($att->status) }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada data absensi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
