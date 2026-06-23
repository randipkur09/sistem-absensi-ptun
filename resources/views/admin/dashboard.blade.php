@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
<div class="row mb-4 animate-fade-in">
    <div class="col-12">
        <h4 class="fw-bold" style="color: #0f172a;">
            @php
                $hour = now()->format('H');
                if ($hour < 12) $greeting = 'Selamat Pagi';
                elseif ($hour < 15) $greeting = 'Selamat Siang';
                elseif ($hour < 18) $greeting = 'Selamat Sore';
                else $greeting = 'Selamat Malam';
            @endphp
            {{ $greeting }}, {{ auth()->user()->name ?? 'Admin' }}! 👋
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
    <div class="col-xl-3 col-md-6 animate-fade-in">
        <div class="stat-card warning">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Izin Pending</div>
                    <div class="stat-value">{{ $pendingPermissions }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-envelope-exclamation-fill"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- Today's Attendance Stats -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header">
                <span><i class="bi bi-calendar-check me-2"></i> Absensi Hari Ini</span>
                <span class="badge bg-light text-dark">{{ now()->translatedFormat('d F Y') }}</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <div class="text-center p-3 rounded-3" style="background: #dcfce7;">
                            <div style="font-size: 2rem; font-weight: 800; color: #166534;">{{ $hadirToday }}</div>
                            <div style="font-size: 0.8rem; color: #166534; font-weight: 600;">Hadir</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="text-center p-3 rounded-3" style="background: #fef3c7;">
                            <div style="font-size: 2rem; font-weight: 800; color: #92400e;">{{ $terlambatToday }}</div>
                            <div style="font-size: 0.8rem; color: #92400e; font-weight: 600;">Terlambat</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="text-center p-3 rounded-3" style="background: #dbeafe;">
                            <div style="font-size: 2rem; font-weight: 800; color: #1e40af;">{{ $izinToday }}</div>
                            <div style="font-size: 0.8rem; color: #1e40af; font-weight: 600;">Izin</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="text-center p-3 rounded-3" style="background: #fce7f3;">
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
    <div class="col-lg-4">
        <div class="card-custom">
            <div class="card-header">
                <span><i class="bi bi-bar-chart-fill me-2"></i> Rekap Bulan Ini</span>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" height="220"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card-custom">
            <div class="card-header">
                <span><i class="bi bi-clock-history me-2"></i> Absensi Terbaru</span>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    const monthlyData = @json($monthlyAttendances);
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Hadir', 'Terlambat', 'Izin', 'Sakit', 'Alfa'],
            datasets: [{
                data: [
                    monthlyData['hadir'] || 0,
                    monthlyData['terlambat'] || 0,
                    monthlyData['izin'] || 0,
                    monthlyData['sakit'] || 0,
                    monthlyData['alfa'] || 0,
                ],
                backgroundColor: ['#10b981', '#f59e0b', '#3b82f6', '#ec4899', '#ef4444'],
                borderWidth: 0,
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        pointStyleWidth: 10,
                        font: { size: 12, family: 'Inter' }
                    }
                }
            }
        }
    });
</script>
@endpush
