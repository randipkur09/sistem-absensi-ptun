@extends('layouts.employee')

@section('title', 'Dashboard Pegawai')
@section('page-title', 'Dashboard Pegawai')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-xl-4 animate-fade-in">
        <div class="card-custom h-100">
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    @if(auth()->user()->photo)
                        <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Profile" class="rounded-circle shadow-sm" style="width: 100px; height: 100px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 100px; height: 100px; font-size: 2.5rem; font-weight: 700;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <h5 class="fw-bold mb-1">{{ auth()->user()->name }}</h5>
                <p class="text-muted mb-3">{{ ucfirst(auth()->user()->employee_type) }}</p>

                @if(auth()->user()->isOutsourcing())
                    <div class="badge bg-light text-dark mb-1 d-block p-2 text-start">
                        <i class="bi bi-building me-2"></i> {{ auth()->user()->outsourcingEmployee->company_name ?? '-' }}
                    </div>
                    <div class="badge bg-light text-dark d-block p-2 text-start">
                        <i class="bi bi-briefcase me-2"></i> {{ auth()->user()->outsourcingEmployee->position ?? '-' }}
                    </div>
                @else
                    <div class="badge bg-light text-dark mb-1 d-block p-2 text-start">
                        <i class="bi bi-mortarboard me-2"></i> {{ auth()->user()->internshipParticipant->institution ?? '-' }}
                    </div>
                    <div class="badge bg-light text-dark d-block p-2 text-start">
                        <i class="bi bi-book me-2"></i> {{ auth()->user()->internshipParticipant->major ?? '-' }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-xl-8 animate-fade-in" style="animation-delay: 0.1s;">
        <div class="card-custom h-100">
            <div class="card-header">
                <span><i class="bi bi-calendar-check me-2"></i> Status Absensi Hari Ini</span>
                <span class="badge bg-light text-dark">{{ now()->translatedFormat('d F Y') }}</span>
            </div>
            <div class="card-body">
                <div class="row text-center g-3 mt-2">
                    <div class="col-md-6">
                        <div class="p-4 rounded-3 border {{ $todayAttendance && $todayAttendance->jam_masuk ? 'bg-light' : '' }}">
                            <div class="text-muted mb-2">Jam Masuk</div>
                            @if($todayAttendance && $todayAttendance->jam_masuk)
                                <h2 class="fw-bold text-success mb-2">{{ \Carbon\Carbon::parse($todayAttendance->jam_masuk)->format('H:i') }}</h2>
                                <span class="badge-status badge-{{ $todayAttendance->status }}">{{ ucfirst($todayAttendance->status) }}</span>
                            @else
                                <h2 class="fw-bold text-muted mb-2">--:--</h2>
                                <span class="badge bg-secondary">Belum Absen</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-4 rounded-3 border {{ $todayAttendance && $todayAttendance->jam_pulang ? 'bg-light' : '' }}">
                            <div class="text-muted mb-2">Jam Pulang</div>
                            @if($todayAttendance && $todayAttendance->jam_pulang)
                                <h2 class="fw-bold text-primary mb-2">{{ \Carbon\Carbon::parse($todayAttendance->jam_pulang)->format('H:i') }}</h2>
                                <span class="badge bg-success">Sudah Pulang</span>
                            @else
                                <h2 class="fw-bold text-muted mb-2">--:--</h2>
                                <span class="badge bg-secondary">Belum Pulang</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('employee.attendance.index') }}" class="btn btn-primary-custom px-4 py-2">
                        <i class="bi bi-camera me-2"></i> Buka Halaman Absensi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4 animate-fade-in" style="animation-delay: 0.2s;">
        <div class="card-custom h-100">
            <div class="card-header">
                <span><i class="bi bi-pie-chart-fill me-2"></i> Statistik Bulan Ini</span>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                    <span class="text-muted">Hadir</span>
                    <span class="fw-bold text-success">{{ $monthlyStats['hadir'] ?? 0 }} Hari</span>
                </div>
                <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                    <span class="text-muted">Terlambat</span>
                    <span class="fw-bold text-warning">{{ $monthlyStats['terlambat'] ?? 0 }} Hari</span>
                </div>
                <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                    <span class="text-muted">Izin</span>
                    <span class="fw-bold text-primary">{{ $monthlyStats['izin'] ?? 0 }} Hari</span>
                </div>
                <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                    <span class="text-muted">Sakit</span>
                    <span class="fw-bold text-danger">{{ $monthlyStats['sakit'] ?? 0 }} Hari</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Alfa</span>
                    <span class="fw-bold text-danger">{{ $monthlyStats['alfa'] ?? 0 }} Hari</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8 animate-fade-in" style="animation-delay: 0.3s;">
        <div class="card-custom h-100">
            <div class="card-header">
                <span><i class="bi bi-clock-history me-2"></i> 7 Hari Terakhir</span>
                <a href="{{ route('employee.history.index') }}" class="btn btn-sm btn-outline-custom">Semua Riwayat</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Masuk</th>
                                <th>Pulang</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAttendances as $att)
                            <tr>
                                <td>{{ $att->tanggal->translatedFormat('d M Y') }}</td>
                                <td>{{ $att->jam_masuk ? \Carbon\Carbon::parse($att->jam_masuk)->format('H:i') : '-' }}</td>
                                <td>{{ $att->jam_pulang ? \Carbon\Carbon::parse($att->jam_pulang)->format('H:i') : '-' }}</td>
                                <td><span class="badge-status badge-{{ $att->status }}">{{ ucfirst($att->status) }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada data absensi</td>
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
