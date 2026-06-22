@extends('layouts.admin')

@section('title', 'Data Absensi Pegawai')
@section('page-title', 'Data Absensi')

@section('content')
<div class="card-custom animate-fade-in">
    <div class="card-header border-bottom">
        <span><i class="bi bi-clipboard-check-fill me-2"></i> Filter Data Absensi</span>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.attendance.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" class="form-control" name="tanggal" value="{{ request('tanggal', now()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pegawai</label>
                    <select name="user_id" class="form-select">
                        <option value="">Semua Pegawai</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ ucfirst($user->employee_type) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                        <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="alfa" {{ request('status') == 'alfa' ? 'selected' : '' }}>Alfa</option>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary-custom"><i class="bi bi-filter"></i> Tampilkan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card-custom mt-4 animate-fade-in" style="animation-delay: 0.1s;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom mb-0 align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pegawai</th>
                        <th>Waktu Masuk</th>
                        <th>Waktu Pulang</th>
                        <th>Jarak (M/P)</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $index => $att)
                    <tr>
                        <td>{{ $attendances->firstItem() + $index }}</td>
                        <td>
                            <div class="fw-bold">{{ $att->user->name ?? '-' }}</div>
                            <div class="small text-muted">{{ ucfirst($att->user->employee_type ?? '-') }}</div>
                        </td>
                        <td>
                            @if($att->jam_masuk)
                                <div class="fw-semibold text-success">{{ \Carbon\Carbon::parse($att->jam_masuk)->format('H:i:s') }}</div>
                                @if($att->foto_masuk_url)
                                    <div class="small text-muted mt-1"><i class="bi bi-camera me-1"></i> Ada Foto</div>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($att->jam_pulang)
                                <div class="fw-semibold text-primary">{{ \Carbon\Carbon::parse($att->jam_pulang)->format('H:i:s') }}</div>
                                @if($att->foto_pulang_url)
                                    <div class="small text-muted mt-1"><i class="bi bi-camera me-1"></i> Ada Foto</div>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <div><small>M: {{ $att->jarak_masuk ? $att->jarak_masuk.' m' : '-' }}</small></div>
                            <div><small>P: {{ $att->jarak_pulang ? $att->jarak_pulang.' m' : '-' }}</small></div>
                        </td>
                        <td>
                            <span class="badge-status badge-{{ $att->status }}">{{ ucfirst($att->status) }}</span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.attendance.show', $att->id) }}" class="btn btn-sm btn-info text-white" title="Lihat Detail Foto & Lokasi">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">Data absensi tidak ditemukan.</td>
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
@endsection
