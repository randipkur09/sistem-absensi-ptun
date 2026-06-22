@extends('layouts.employee')

@section('title', 'Riwayat Absensi')
@section('page-title', 'Riwayat Absensi')

@section('content')
<div class="card-custom animate-fade-in">
    <div class="card-header">
        <span><i class="bi bi-clock-history me-2"></i> Riwayat Absensi Pribadi</span>
        
        <form action="{{ route('employee.history.index') }}" method="GET" class="d-flex align-items-center gap-2">
            <input type="month" name="month" class="form-control form-control-sm" value="{{ request('month', now()->format('Y-m')) }}">
            <button type="submit" class="btn btn-sm btn-primary-custom">Filter</button>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom mb-0 text-center align-middle">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Foto Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Foto Pulang</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $att)
                    <tr>
                        <td>{{ $att->tanggal->translatedFormat('l, d M Y') }}</td>
                        <td>
                            @if($att->jam_masuk)
                                <div>{{ \Carbon\Carbon::parse($att->jam_masuk)->format('H:i:s') }}</div>
                                <small class="text-muted">{{ $att->jarak_masuk }}m</small>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($att->foto_masuk_url)
                                <img src="{{ $att->foto_masuk_url }}" alt="Masuk" class="rounded" style="width: 40px; height: 40px; object-fit: cover; cursor: pointer;" onclick="showImage('{{ $att->foto_masuk_url }}')">
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($att->jam_pulang)
                                <div>{{ \Carbon\Carbon::parse($att->jam_pulang)->format('H:i:s') }}</div>
                                <small class="text-muted">{{ $att->jarak_pulang }}m</small>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($att->foto_pulang_url)
                                <img src="{{ $att->foto_pulang_url }}" alt="Pulang" class="rounded" style="width: 40px; height: 40px; object-fit: cover; cursor: pointer;" onclick="showImage('{{ $att->foto_pulang_url }}')">
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <span class="badge-status badge-{{ $att->status }}">{{ ucfirst($att->status) }}</span>
                        </td>
                        <td>{{ $att->keterangan ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Belum ada riwayat absensi pada bulan ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-3 py-3 border-top">
            {{ $attendances->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal Image Preview -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-0">
                <img id="modalImage" src="" alt="Preview" class="img-fluid rounded-3 shadow-lg" style="max-height: 80vh;">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showImage(url) {
        document.getElementById('modalImage').src = url;
        new bootstrap.Modal(document.getElementById('imageModal')).show();
    }
</script>
@endpush
