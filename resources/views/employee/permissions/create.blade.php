@extends('layouts.employee')

@section('title', 'Buat Pengajuan Izin/Sakit')
@section('page-title', 'Form Pengajuan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 animate-fade-in">
        <div class="card-custom">
            <div class="card-header">
                <span><i class="bi bi-file-earmark-plus me-2"></i> Form Pengajuan Izin / Sakit</span>
                <a href="{{ route('employee.permissions.index') }}" class="btn btn-sm btn-outline-custom">Kembali</a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('employee.permissions.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Jenis Pengajuan <span class="text-danger">*</span></label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="typeIzin" value="izin" {{ old('type') == 'izin' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="typeIzin">Izin</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="typeSakit" value="sakit" {{ old('type') == 'sakit' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="typeSakit">Sakit</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="tanggal_mulai">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required min="{{ date('Y-m-d') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="tanggal_selesai">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required min="{{ date('Y-m-d') }}">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label" for="keterangan">Keterangan / Alasan <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required placeholder="Jelaskan alasan izin/sakit...">{{ old('keterangan') }}</textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label" for="attachment">Lampiran Surat / Bukti (Opsional)</label>
                            <input type="file" class="form-control" id="attachment" name="attachment" accept=".jpg,.jpeg,.png,.pdf">
                            <div class="form-text small text-muted">Format: JPG, PNG, PDF. Maksimal 5MB. Wajib diisi jika sakit > 2 hari.</div>
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary-custom px-4">
                            <i class="bi bi-send me-1"></i> Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
