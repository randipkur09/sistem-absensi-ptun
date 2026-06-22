@extends('layouts.admin')

@section('title', 'Persetujuan Izin & Sakit')
@section('page-title', 'Izin & Sakit')

@section('content')
<div class="card-custom animate-fade-in">
    <div class="card-header border-bottom">
        <span><i class="bi bi-envelope-paper-fill me-2"></i> Daftar Pengajuan Pegawai</span>
    </div>
    
    <div class="card-body p-3">
        <form action="{{ route('admin.permissions.index') }}" method="GET" class="mb-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Tipe</label>
                    <select name="type" class="form-select form-select-sm">
                        <option value="">Semua Tipe</option>
                        <option value="izin" {{ request('type') == 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="sakit" {{ request('type') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-sm btn-primary-custom">Filter</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-custom align-middle">
                <thead>
                    <tr>
                        <th>Tgl Pengajuan</th>
                        <th>Pegawai</th>
                        <th>Tipe</th>
                        <th>Tanggal Berlaku</th>
                        <th>Keterangan</th>
                        <th>Lampiran</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permissions as $perm)
                    <tr>
                        <td>{{ $perm->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="fw-bold">{{ $perm->user->name ?? '-' }}</div>
                            <div class="small text-muted">{{ ucfirst($perm->user->employee_type ?? '-') }}</div>
                        </td>
                        <td>
                            <span class="badge {{ $perm->type == 'izin' ? 'bg-primary' : 'bg-danger' }}">
                                {{ ucfirst($perm->type) }}
                            </span>
                        </td>
                        <td>
                            @if($perm->tanggal_mulai->equalTo($perm->tanggal_selesai))
                                {{ $perm->tanggal_mulai->format('d/m/Y') }}
                            @else
                                <div class="small">{{ $perm->tanggal_mulai->format('d/m/Y') }} s.d</div>
                                <div class="small">{{ $perm->tanggal_selesai->format('d/m/Y') }}</div>
                            @endif
                        </td>
                        <td style="max-width: 200px;">
                            <div class="text-truncate small" title="{{ $perm->keterangan }}">{{ $perm->keterangan }}</div>
                        </td>
                        <td>
                            @if($perm->attachment_url)
                                <a href="{{ $perm->attachment_url }}" target="_blank" class="btn btn-sm btn-outline-info py-0">Lihat File</a>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge-status badge-{{ $perm->status_approval }}">{{ ucfirst($perm->status_approval) }}</span>
                            @if($perm->status_approval != 'pending')
                                <div class="small text-muted mt-1" style="font-size: 0.65rem;">By: {{ $perm->approver->name ?? '-' }}</div>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($perm->status_approval == 'pending')
                                <div class="btn-group">
                                    <form action="{{ route('admin.permissions.approve', $perm->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-success" title="Setujui"><i class="bi bi-check-lg"></i></button>
                                    </form>
                                    <form action="{{ route('admin.permissions.reject', $perm->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-danger ms-1" title="Tolak"><i class="bi bi-x-lg"></i></button>
                                    </form>
                                </div>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Belum ada pengajuan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $permissions->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
