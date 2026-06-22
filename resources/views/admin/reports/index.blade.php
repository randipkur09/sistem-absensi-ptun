@extends('layouts.admin')

@section('title', 'Laporan Absensi')
@section('page-title', 'Laporan Absensi')

@section('content')
<div class="row g-3 mb-4 animate-fade-in">
    <div class="col-12">
        <div class="card-custom p-4">
            <form action="{{ route('admin.reports.index') }}" method="GET" id="filterForm">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Mulai Tanggal</label>
                        <input type="date" class="form-control" name="start_date" id="start_date" value="{{ $startDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" class="form-control" name="end_date" id="end_date" value="{{ $endDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Pilih Pegawai (Opsional)</label>
                        <select name="user_id" id="user_id" class="form-select">
                            <option value="">Semua Pegawai</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-primary-custom"><i class="bi bi-filter"></i> Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row g-3 mb-4 animate-fade-in" style="animation-delay: 0.1s;">
    <div class="col-md-3 col-6">
        <div class="card-custom bg-success bg-opacity-10 text-success border-success text-center py-3">
            <h3 class="fw-bold mb-0">{{ $summary['hadir'] ?? 0 }}</h3>
            <div class="small fw-semibold mt-1">Total Hadir</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card-custom bg-warning bg-opacity-10 text-warning border-warning text-center py-3">
            <h3 class="fw-bold mb-0">{{ $summary['terlambat'] ?? 0 }}</h3>
            <div class="small fw-semibold mt-1">Total Terlambat</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card-custom bg-primary bg-opacity-10 text-primary border-primary text-center py-3">
            <h3 class="fw-bold mb-0">{{ ($summary['izin'] ?? 0) + ($summary['sakit'] ?? 0) }}</h3>
            <div class="small fw-semibold mt-1">Total Izin/Sakit</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card-custom bg-danger bg-opacity-10 text-danger border-danger text-center py-3">
            <h3 class="fw-bold mb-0">{{ $summary['alfa'] ?? 0 }}</h3>
            <div class="small fw-semibold mt-1">Total Alfa</div>
        </div>
    </div>
</div>

<div class="card-custom animate-fade-in" style="animation-delay: 0.2s;">
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
        <span><i class="bi bi-file-text-fill me-2"></i> Hasil Laporan</span>
        <div>
            <button type="button" class="btn btn-sm btn-danger text-white" onclick="exportReport('pdf')">
                <i class="bi bi-filetype-pdf me-1"></i> Export PDF
            </button>
            <button type="button" class="btn btn-sm btn-success text-white mx-1" onclick="exportReport('excel')">
                <i class="bi bi-filetype-xls me-1"></i> Export Excel
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Pegawai</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $att)
                    <tr>
                        <td>{{ $att->tanggal->format('d/m/Y') }}</td>
                        <td>{{ $att->user->name ?? '-' }}</td>
                        <td>{{ $att->jam_masuk ?? '-' }}</td>
                        <td>{{ $att->jam_pulang ?? '-' }}</td>
                        <td><span class="badge-status badge-{{ $att->status }}">{{ ucfirst($att->status) }}</span></td>
                        <td>{{ $att->keterangan ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Data laporan tidak ditemukan untuk filter ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-3 py-3 border-top">
            {{ $attendances->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<script>
    function exportReport(type) {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        const userId = document.getElementById('user_id').value;
        
        let url = type === 'pdf' ? '{{ route("admin.reports.export-pdf") }}' : '{{ route("admin.reports.export-excel") }}';
        url += `?start_date=${startDate}&end_date=${endDate}&user_id=${userId}`;
        
        window.open(url, '_blank');
    }
</script>
@endsection
