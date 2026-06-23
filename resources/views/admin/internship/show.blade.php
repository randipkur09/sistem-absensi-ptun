@extends('layouts.admin')

@section('title', 'Detail Peserta Magang')
@section('page-title', 'Detail Peserta Magang')

@section('content')
<div class="row g-4 animate-fade-in">
    <div class="col-lg-4">
        <div class="card-custom h-100">
            <div class="card-body text-center p-4">
                <div class="mb-4">
                    @if($internship->photo)
                        <img src="{{ asset('storage/' . $internship->photo) }}" alt="Profile" class="rounded-circle shadow" style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #fff;">
                    @else
                        <div class="rounded-circle text-white d-inline-flex align-items-center justify-content-center shadow" style="width: 150px; height: 150px; font-size: 4rem; font-weight: 700; border: 4px solid #fff; background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                            {{ strtoupper(substr($internship->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                
                <h4 class="fw-bold mb-1">{{ $internship->name }}</h4>
                <div class="text-muted mb-3">{{ $internship->email }}</div>
                
                <span class="badge-status badge-{{ $internship->status }} mb-4 px-3 py-2 fs-6">
                    <i class="bi bi-person-circle me-1"></i> Akun {{ ucfirst($internship->status) }}
                </span>

                <div class="d-grid gap-2">
                    <a href="{{ route('admin.internship.edit', $internship->id) }}" class="btn btn-warning text-white fw-bold">
                        <i class="bi bi-pencil me-1"></i> Edit Data
                    </a>
                    <a href="{{ route('admin.internship.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card-custom mb-4">
            <div class="card-header">
                <span><i class="bi bi-info-circle-fill me-2"></i> Informasi Detail Magang</span>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-sm-6 border-bottom pb-3">
                        <div class="text-muted small mb-1">Institusi / Universitas</div>
                        <div class="fw-bold fs-6">{{ $internship->internshipParticipant->institution ?? '-' }}</div>
                    </div>
                    <div class="col-sm-6 border-bottom pb-3">
                        <div class="text-muted small mb-1">Jurusan / Program Studi</div>
                        <div class="fw-bold fs-6">{{ $internship->internshipParticipant->major ?? '-' }}</div>
                    </div>
                    <div class="col-sm-4 border-bottom pb-3">
                        <div class="text-muted small mb-1">Periode Magang</div>
                        <div class="fw-bold">
                            {{ optional($internship->internshipParticipant->start_date)->format('d/m/Y') }} - 
                            {{ optional($internship->internshipParticipant->end_date)->format('d/m/Y') }}
                        </div>
                        @if($internship->internshipParticipant && !$internship->internshipParticipant->isActive())
                            <span class="badge bg-danger mt-1">Magang Selesai</span>
                        @else
                            <span class="badge bg-success mt-1">Magang Aktif</span>
                        @endif
                    </div>
                    <div class="col-sm-4 border-bottom pb-3">
                        <div class="text-muted small mb-1">Dosen/Pembimbing</div>
                        <div class="fw-bold">{{ $internship->internshipParticipant->supervisor ?? '-' }}</div>
                    </div>
                    <div class="col-sm-4 border-bottom pb-3">
                        <div class="text-muted small mb-1">Nomor Telepon</div>
                        <div class="fw-bold">{{ $internship->phone ?? '-' }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small mb-1">Alamat Domisili</div>
                        <div class="fw-bold">{{ $internship->address ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-custom">
            <div class="card-header">
                <span><i class="bi bi-clock-history me-2"></i> 5 Riwayat Absensi Terakhir</span>
                <a href="{{ route('admin.attendance.index', ['user_id' => $internship->id]) }}" class="btn btn-sm btn-outline-custom">Lihat Semua Absensi</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Masuk</th>
                                <th>Pulang</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($internship->attendances->take(5) as $att)
                            <tr>
                                <td>{{ $att->tanggal->translatedFormat('d M Y') }}</td>
                                <td>{{ $att->jam_masuk ? \Carbon\Carbon::parse($att->jam_masuk)->format('H:i') : '-' }}</td>
                                <td>{{ $att->jam_pulang ? \Carbon\Carbon::parse($att->jam_pulang)->format('H:i') : '-' }}</td>
                                <td><span class="badge-status badge-{{ $att->status }}">{{ ucfirst($att->status) }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-3 text-muted">Belum ada riwayat absensi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
