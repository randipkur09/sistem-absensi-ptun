@extends('layouts.admin')

@section('title', 'Edit Data Magang')
@section('page-title', 'Edit Peserta Magang')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10 animate-fade-in">
        <div class="card-custom">
            <div class="card-header">
                <span><i class="bi bi-pencil-square me-2"></i> Edit Data: {{ $internship->name }}</span>
                <a href="{{ route('admin.internship.index') }}" class="btn btn-sm btn-outline-custom">Kembali</a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.internship.update', $internship->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <h6 class="fw-bold mb-3 border-bottom pb-2" style="color: var(--primary);">Informasi Akun Utama</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{ old('name', $internship->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="username" value="{{ old('username', $internship->username) }}" required>
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
                            <input type="text" class="form-control" name="phone" value="{{ old('phone', $internship->phone) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status Akun</label>
                            <select name="status" class="form-select" required>
                                <option value="aktif" {{ $internship->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ $internship->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ganti Foto Profil</label>
                            <input type="file" class="form-control" name="photo" accept="image/*">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control" name="address" rows="2">{{ old('address', $internship->address) }}</textarea>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 border-bottom pb-2" style="color: var(--primary);">Informasi Kampus & Periode Magang</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Institusi / Universitas <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="institution" value="{{ old('institution', $internship->internshipParticipant->institution ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jurusan / Program Studi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="major" value="{{ old('major', $internship->internshipParticipant->major ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Mulai Magang <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="start_date" value="{{ old('start_date', optional($internship->internshipParticipant->start_date)->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Selesai Magang <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="end_date" value="{{ old('end_date', optional($internship->internshipParticipant->end_date)->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nama Dosen/Pembimbing</label>
                            <input type="text" class="form-control" name="supervisor" value="{{ old('supervisor', $internship->internshipParticipant->supervisor ?? '') }}">
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
@endsection
