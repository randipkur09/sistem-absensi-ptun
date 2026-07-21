@extends('layouts.admin')

@section('title', 'Tambah Data Outsourcing')
@section('page-title', 'Tambah Tenaga Outsourcing')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10 animate-fade-in">
        <div class="card-custom">
            <div class="card-header">
                <span><i class="bi bi-person-plus-fill me-2"></i> Form Tambah Data Baru</span>
                <a href="{{ route('admin.outsourcing.index') }}" class="btn btn-sm btn-outline-custom">Kembali</a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.outsourcing.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <h6 class="fw-bold mb-3 border-bottom pb-2" style="color: var(--primary);">Informasi Akun Utama</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="username" value="{{ old('username') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password_confirmation" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nomor WhatsApp/Telepon</label>
                            <input type="text" class="form-control" name="phone" value="{{ old('phone') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Foto Profil (Opsional)</label>
                            <input type="file" class="form-control" name="photo" accept="image/*">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control" name="address" rows="2">{{ old('address') }}</textarea>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 border-bottom pb-2" style="color: var(--primary);">Informasi Pekerjaan & Kontrak</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Perusahaan (Vendor) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="company_name" value="{{ old('company_name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jabatan/Tugas <span class="text-danger">*</span></label>
                            @php
                                $oldPosition = old('position');
                                $isLainLain = $oldPosition && !in_array($oldPosition, ['Satpam', 'Cleaning service']);
                            @endphp
                            <select class="form-select mb-2" id="position_select" onchange="togglePositionInput()">
                                <option value="" {{ !$oldPosition ? 'selected' : '' }} disabled>-- Pilih Jabatan --</option>
                                <option value="Satpam" {{ $oldPosition == 'Satpam' ? 'selected' : '' }}>Satpam</option>
                                <option value="Cleaning service" {{ $oldPosition == 'Cleaning service' ? 'selected' : '' }}>Cleaning service</option>
                                <option value="Lain-lain" {{ $isLainLain ? 'selected' : '' }}>Lain-lain</option>
                            </select>
                            <input type="text" class="form-control" id="position_input" value="{{ $isLainLain ? $oldPosition : '' }}" placeholder="Masukkan Jabatan/Tugas" style="display: none;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Mulai Kontrak <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="contract_start" value="{{ old('contract_start') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Selesai Kontrak <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="contract_end" value="{{ old('contract_end') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nomor Kontrak</label>
                            <input type="text" class="form-control" name="contract_number" value="{{ old('contract_number') }}">
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary-custom px-4">
                            <i class="bi bi-save me-1"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    togglePositionInput();
});

function togglePositionInput() {
    var select = document.getElementById('position_select');
    var input = document.getElementById('position_input');
    
    if (select.value === 'Lain-lain') {
        input.style.display = 'block';
        input.required = true;
        input.name = 'position';
        select.removeAttribute('name');
    } else {
        input.style.display = 'none';
        input.required = false;
        input.name = ''; 
        select.name = 'position';
    }
}
</script>
@endsection
