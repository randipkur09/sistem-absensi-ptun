@extends('layouts.admin')

@section('title', 'Edit Data Outsourcing')
@section('page-title', 'Edit Tenaga Outsourcing')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10 animate-fade-in">
        <div class="card-custom">
            <div class="card-header">
                <span><i class="bi bi-pencil-square me-2"></i> Edit Data: {{ $outsourcing->name }}</span>
                <a href="{{ route('admin.outsourcing.index') }}" class="btn btn-sm btn-outline-custom">Kembali</a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.outsourcing.update', $outsourcing->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <h6 class="fw-bold mb-3 border-bottom pb-2" style="color: var(--primary);">Informasi Akun Utama</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{ old('name', $outsourcing->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="username" value="{{ old('username', $outsourcing->username) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password Baru (Kosongkan jika tidak diubah)</label>
                            <input type="password" class="form-control" name="password">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" name="password_confirmation">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nomor WhatsApp/Telepon</label>
                            <input type="text" class="form-control" name="phone" value="{{ old('phone', $outsourcing->phone) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status Akun</label>
                            <select name="status" class="form-select" required>
                                <option value="aktif" {{ $outsourcing->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ $outsourcing->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ganti Foto Profil</label>
                            <input type="file" class="form-control" name="photo" accept="image/*">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control" name="address" rows="2">{{ old('address', $outsourcing->address) }}</textarea>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 border-bottom pb-2" style="color: var(--primary);">Informasi Pekerjaan & Kontrak</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Perusahaan (Vendor) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="company_name" value="{{ old('company_name', $outsourcing->outsourcingEmployee->company_name ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jabatan/Tugas <span class="text-danger">*</span></label>
                            @php
                                $oldPosition = old('position', $outsourcing->outsourcingEmployee->position ?? '');
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
                            <input type="date" class="form-control" name="contract_start" value="{{ old('contract_start', optional($outsourcing->outsourcingEmployee->contract_start)->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Selesai Kontrak <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="contract_end" value="{{ old('contract_end', optional($outsourcing->outsourcingEmployee->contract_end)->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nomor Kontrak</label>
                            <input type="text" class="form-control" name="contract_number" value="{{ old('contract_number', $outsourcing->outsourcingEmployee->contract_number ?? '') }}">
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-warning px-4 text-white fw-bold">
                            <i class="bi bi-save me-1"></i> Update Data
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
