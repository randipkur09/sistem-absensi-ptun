@extends('layouts.employee')

@section('title', 'Data Izin & Sakit')
@section('page-title', 'Izin & Sakit')

@section('content')
<div class="card-custom animate-fade-in">
    <div class="card-header">
        <span><i class="bi bi-envelope-paper-fill me-2"></i> Riwayat Pengajuan Izin/Sakit</span>
        <a href="{{ route('employee.permissions.create') }}" class="btn btn-sm btn-primary-custom">
            <i class="bi bi-plus-lg me-1"></i> Buat Pengajuan
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom mb-0 text-center align-middle">
                <thead>
                    <tr>
                        <th>Tgl Pengajuan</th>
                        <th>Tipe</th>
                        <th>Tanggal Berlaku</th>
                        <th>Keterangan</th>
                        <th>Lampiran</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permissions as $perm)
                    <tr>
                        <td>{{ $perm->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <span class="badge {{ $perm->type == 'izin' ? 'bg-primary' : 'bg-danger' }}">
                                {{ ucfirst($perm->type) }}
                            </span>
                        </td>
                        <td>
                            @if($perm->tanggal_mulai->equalTo($perm->tanggal_selesai))
                                {{ $perm->tanggal_mulai->format('d/m/Y') }}
                            @else
                                {{ $perm->tanggal_mulai->format('d/m/Y') }} - {{ $perm->tanggal_selesai->format('d/m/Y') }}
                            @endif
                        </td>
                        <td class="text-start" style="max-width: 200px;">
                            <div class="text-truncate" title="{{ $perm->keterangan }}">{{ $perm->keterangan }}</div>
                        </td>
                        <td>
                            @if($perm->attachment_url)
                                <a href="{{ $perm->attachment_url }}" target="_blank" class="btn btn-sm btn-outline-custom">
                                    <i class="bi bi-file-earmark-text"></i> Lihat
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <span class="badge-status badge-{{ $perm->status_approval }}">{{ ucfirst($perm->status_approval) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Belum ada riwayat pengajuan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-3 py-3 border-top">
            {{ $permissions->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
