@extends('layouts.admin')

@section('title', 'Data Tenaga Outsourcing')
@section('page-title', 'Outsourcing')

@section('content')
<div class="card-custom animate-fade-in">
    <div class="card-header border-bottom">
        <div class="d-flex justify-content-between align-items-center w-100">
            <span>
                <i class="bi bi-people-fill me-2" style="color: var(--primary);"></i> Data Tenaga Outsourcing
                <span class="badge ms-2 rounded-pill" style="background: rgba(26,86,50,0.1); color: var(--primary);">{{ $outsourcings->total() }} Data</span>
            </span>
            <div>
                <button type="button" class="btn btn-sm btn-success-custom" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="bi bi-file-earmark-arrow-up me-1"></i> Import
                </button>
                <a href="{{ route('admin.export-users', 'outsourcing') }}" class="btn btn-sm btn-outline-custom mx-1">
                    <i class="bi bi-file-earmark-arrow-down me-1"></i> Export
                </a>
                <a href="{{ route('admin.outsourcing.create') }}" class="btn btn-sm btn-primary-custom">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Data
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body p-3">
        <form action="{{ route('admin.outsourcing.index') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari nama, username, perusahaan..." value="{{ request('search') }}">
                <button class="btn btn-primary-custom" type="submit"><i class="bi bi-search"></i> Cari</button>
                @if(request('search'))
                    <a href="{{ route('admin.outsourcing.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-custom align-middle">
                <thead>
                    <tr>
                        <th>Profil</th>
                        <th>Nama & Kontak</th>
                        <th>Perusahaan & Jabatan</th>
                        <th>Kontrak</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($outsourcings as $item)
                    <tr>
                        <td>
                            @if($item->photo)
                                <img src="{{ asset('storage/' . $item->photo) }}" alt="Foto" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                @php
                                    $colors = ['#1a5632', '#2d7a4a', '#c5a237', '#16a34a', '#d97706', '#2563eb', '#9333ea'];
                                    $bgColor = $colors[strlen($item->name) % count($colors)];
                                @endphp
                                <div class="rounded-circle text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: bold; background: linear-gradient(135deg, {{ $bgColor }}, #0d3320);">
                                    {{ strtoupper(substr($item->name, 0, 1)) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold">{{ $item->name }}</div>
                            <div class="small text-muted">{{ $item->username }}</div>
                            <div class="small text-muted"><i class="bi bi-telephone me-1"></i> {{ $item->phone ?? '-' }}</div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $item->outsourcingEmployee->company_name ?? '-' }}</div>
                            <div class="small text-muted">{{ $item->outsourcingEmployee->position ?? '-' }}</div>
                        </td>
                        <td>
                            @if($item->outsourcingEmployee)
                                <div><small>Mulai: {{ $item->outsourcingEmployee->contract_start->format('d/m/Y') }}</small></div>
                                <div><small>Selesai: {{ $item->outsourcingEmployee->contract_end->format('d/m/Y') }}</small></div>
                                @if(now()->lt($item->outsourcingEmployee->contract_start))
                                    <span class="badge-period-status badge-belum-mulai mt-1">
                                        Belum Mulai Kontrak
                                    </span>
                                @elseif(now()->between($item->outsourcingEmployee->contract_start, $item->outsourcingEmployee->contract_end))
                                    <span class="badge-period-status badge-sedang-aktif mt-1">
                                        Kontrak Aktif
                                    </span>
                                @else
                                    <span class="badge-period-status badge-selesai mt-1">
                                        Kontrak Berakhir
                                    </span>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($item->outsourcingEmployee && now()->between($item->outsourcingEmployee->contract_start, $item->outsourcingEmployee->contract_end))
                                <span class="badge-status badge-aktif">Aktif</span>
                            @else
                                <span class="badge-status badge-nonaktif">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ route('admin.outsourcing.show', $item->id) }}" class="btn btn-sm btn-info text-white" title="Detail"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.outsourcing.edit', $item->id) }}" class="btn btn-sm btn-warning text-white" title="Edit"><i class="bi bi-pencil"></i></a>
                                <button type="button" class="btn btn-sm btn-danger" title="Hapus" onclick="confirmDelete({{ $item->id }})"><i class="bi bi-trash"></i></button>
                            </div>
                            <form id="delete-form-{{ $item->id }}" action="{{ route('admin.outsourcing.destroy', $item->id) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Data tenaga outsourcing tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $outsourcings->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal Import -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Data Outsourcing</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.import-users', 'outsourcing') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">File Excel (.xlsx, .xls)</label>
                        <input type="file" class="form-control" name="file" required accept=".xlsx,.xls,.csv">
                    </div>
                    <div class="alert alert-info py-2 small mb-0">
                        Pastikan format kolom sesuai: nama, username, password, telepon, alamat, perusahaan, jabatan, tanggal_mulai, tanggal_selesai, nomor_kontrak.
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
