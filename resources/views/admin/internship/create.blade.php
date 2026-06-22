@extends('layouts.admin')

@section('title', 'Tambah Data Magang')
@section('page-title', 'Tambah Peserta Magang')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10 animate-fade-in">
        <div class="card-custom">
            <div class="card-header">
                <span><i class="bi bi-person-plus-fill me-2"></i> Form Tambah Data Baru</span>
                <a href="{{ route('admin.internship.index') }}" class="btn btn-sm btn-outline-custom">Kembali</a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.internship.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Informasi Akun Utama</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
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

                    <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Informasi Kampus & Periode Magang</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Institusi / Universitas <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="institution" value="{{ old('institution') }}" required placeholder="Contoh: Universitas Lampung">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jurusan / Program Studi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="major" value="{{ old('major') }}" required placeholder="Contoh: Ilmu Hukum">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Mulai Magang <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="start_date" value="{{ old('start_date') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Selesai Magang <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="end_date" value="{{ old('end_date') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nama Dosen/Pembimbing</label>
                            <input type="text" class="form-control" name="supervisor" value="{{ old('supervisor') }}">
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
@endsection
