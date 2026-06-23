@extends('layouts.admin')

@section('title', 'Data Peserta Magang')
@section('page-title', 'Peserta Magang')

@section('content')
<div class="card-custom animate-fade-in">
    <div class="card-header border-bottom">
        <div class="d-flex justify-content-between align-items-center w-100">
            <span>
                <i class="bi bi-mortarboard-fill me-2 text-primary"></i> Data Peserta Magang
                <span class="badge bg-primary bg-opacity-10 text-primary ms-2 rounded-pill">{{ $internships->total() }} Data</span>
            </span>
            <div>
                <button type="button" class="btn btn-sm btn-success-custom" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="bi bi-file-earmark-arrow-up me-1"></i> Import
                </button>
                <a href="{{ route('admin.export-users', 'magang') }}" class="btn btn-sm btn-outline-custom mx-1">
                    <i class="bi bi-file-earmark-arrow-down me-1"></i> Export
                </a>
                <a href="{{ route('admin.internship.create') }}" class="btn btn-sm btn-primary-custom">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Data
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body p-3">
        <form action="{{ route('admin.internship.index') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari nama, email, institusi..." value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
                @if(request('search'))
                    <a href="{{ route('admin.internship.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-custom align-middle">
                <thead>
                    <tr>
                        <th>Profil</th>
                        <th>Nama & Kontak</th>
                        <th>Institusi & Jurusan</th>
                        <th>Periode Magang</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($internships as $item)
                    <tr>
                        <td>
                            @if($item->photo)
                                <img src="{{ asset('storage/' . $item->photo) }}" alt="Foto" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                @php
                                    $colors = ['#4f46e5', '#0ea5e9', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'];
                                    $bgColor = $colors[strlen($item->name) % count($colors)];
                                @endphp
                                <div class="rounded-circle text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: bold; background: linear-gradient(135deg, {{ $bgColor }}, #1e293b);">
                                    {{ strtoupper(substr($item->name, 0, 1)) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold">{{ $item->name }}</div>
                            <div class="small text-muted">{{ $item->email }}</div>
                            <div class="small text-muted"><i class="bi bi-telephone me-1"></i> {{ $item->phone ?? '-' }}</div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $item->internshipParticipant->institution ?? '-' }}</div>
                            <div class="small text-muted">{{ $item->internshipParticipant->major ?? '-' }}</div>
                        </td>
                        <td>
                            @if($item->internshipParticipant)
                                <div><small>Mulai: {{ $item->internshipParticipant->start_date->format('d/m/Y') }}</small></div>
                                <div><small>Selesai: {{ $item->internshipParticipant->end_date->format('d/m/Y') }}</small></div>
                                @if(!$item->internshipParticipant->isActive())
                                    <span class="badge bg-danger mt-1">Selesai Magang</span>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <span class="badge-status badge-{{ $item->status }}">{{ ucfirst($item->status) }}</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ route('admin.internship.show', $item->id) }}" class="btn btn-sm btn-info text-white" title="Detail"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.internship.edit', $item->id) }}" class="btn btn-sm btn-warning text-white" title="Edit"><i class="bi bi-pencil"></i></a>
                                <button type="button" class="btn btn-sm btn-danger" title="Hapus" onclick="confirmDelete({{ $item->id }})"><i class="bi bi-trash"></i></button>
                            </div>
                            <form id="delete-form-{{ $item->id }}" action="{{ route('admin.internship.destroy', $item->id) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Data peserta magang tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $internships->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal Import -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Data Magang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.import-users', 'magang') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">File Excel (.xlsx, .xls)</label>
                        <input type="file" class="form-control" name="file" required accept=".xlsx,.xls,.csv">
                    </div>
                    <div class="alert alert-info py-2 small mb-0">
                        Pastikan format kolom sesuai: nama, email, password, telepon, alamat, institusi, jurusan, tanggal_mulai, tanggal_selesai, pembimbing.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Import Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
@endpush
