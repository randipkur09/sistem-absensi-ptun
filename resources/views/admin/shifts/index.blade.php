@extends('layouts.admin')

@section('title', 'Manajemen Shift Satpam')
@section('page-title', 'Shift Satpam')

@push('styles')
<style>
    .shift-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.3px;
    }
    .shift-table th, .shift-table td {
        text-align: center;
        vertical-align: middle;
        padding: 0.5rem;
        min-width: 120px;
    }
    .shift-table th:first-child, .shift-table td:first-child {
        text-align: left;
        min-width: 180px;
        position: sticky;
        left: 0;
        background: #fff;
        z-index: 2;
    }
    .shift-select {
        font-size: 0.8rem;
        padding: 4px 8px;
        border-radius: 8px;
    }
    .day-header {
        font-size: 0.75rem;
        line-height: 1.3;
    }
    .day-header .day-name { font-weight: 700; }
    .day-header .day-date { color: #64748b; }
    .weekend-header { background: rgba(239, 68, 68, 0.05); }
    .weekend-cell { background: rgba(239, 68, 68, 0.03); }
</style>
@endpush

@section('content')
{{-- Master Shift --}}
<div class="card-custom mb-4 animate-fade-in">
    <div class="card-header border-bottom">
        <div class="d-flex justify-content-between align-items-center w-100">
            <span><i class="bi bi-clock-history me-2" style="color: var(--primary);"></i> Master Data Shift</span>
            <button class="btn btn-sm btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addShiftModal">
                <i class="bi bi-plus-lg me-1"></i> Tambah Shift
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Nama Shift</th>
                        <th>Jam Masuk (Mulai)</th>
                        <th>Jam Masuk (Selesai)</th>
                        <th>Batas Terlambat</th>
                        <th>Jam Pulang</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shifts as $shift)
                    <tr>
                        <td class="fw-bold">{{ $shift->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($shift->jam_masuk_start)->format('H:i') }}</td>
                        <td>{{ \Carbon\Carbon::parse($shift->jam_masuk_end)->format('H:i') }}</td>
                        <td>{{ \Carbon\Carbon::parse($shift->batas_terlambat)->format('H:i') }}</td>
                        <td>{{ \Carbon\Carbon::parse($shift->jam_pulang)->format('H:i') }}</td>
                        <td>
                            @if($shift->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#editShiftModal{{ $shift->id }}" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="confirmDeleteShift({{ $shift->id }})" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                            <form id="delete-shift-{{ $shift->id }}" action="{{ route('admin.shifts.destroy', $shift->id) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>


                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Belum ada data shift. Silakan tambah shift terlebih dahulu.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modals for Edit Shift (moved outside of table) --}}
@foreach($shifts as $shift)
<div class="modal fade" id="editShiftModal{{ $shift->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Shift: {{ $shift->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.shifts.update', $shift->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Shift</label>
                        <input type="text" class="form-control" name="name" value="{{ $shift->name }}" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Jam Masuk (Mulai)</label>
                            <input type="time" class="form-control" name="jam_masuk_start" value="{{ \Carbon\Carbon::parse($shift->jam_masuk_start)->format('H:i') }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Jam Masuk (Selesai)</label>
                            <input type="time" class="form-control" name="jam_masuk_end" value="{{ \Carbon\Carbon::parse($shift->jam_masuk_end)->format('H:i') }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Batas Terlambat</label>
                            <input type="time" class="form-control" name="batas_terlambat" value="{{ \Carbon\Carbon::parse($shift->batas_terlambat)->format('H:i') }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Jam Pulang</label>
                            <input type="time" class="form-control" name="jam_pulang" value="{{ \Carbon\Carbon::parse($shift->jam_pulang)->format('H:i') }}" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

{{-- Jadwal Shift Mingguan --}}
<div class="card-custom animate-fade-in" style="animation-delay: 0.1s;">
    <div class="card-header border-bottom">
        <div class="d-flex justify-content-between align-items-center w-100">
            <span><i class="bi bi-calendar-week me-2" style="color: var(--primary);"></i> Jadwal Shift Mingguan Satpam</span>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.shifts.index', ['week_start' => $weekStart->copy()->subWeek()->format('Y-m-d')]) }}" class="btn btn-sm btn-outline-custom">
                    <i class="bi bi-chevron-left"></i>
                </a>
                <span class="fw-bold small">{{ $weekStart->format('d M') }} - {{ $weekEnd->format('d M Y') }}</span>
                <a href="{{ route('admin.shifts.index', ['week_start' => $weekStart->copy()->addWeek()->format('Y-m-d')]) }}" class="btn btn-sm btn-outline-custom">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body p-3">
        @if($shifts->isEmpty())
            <div class="text-center py-4 text-muted">
                <i class="bi bi-exclamation-triangle display-6 d-block mb-2"></i>
                Silakan tambahkan Master Shift terlebih dahulu sebelum membuat jadwal.
            </div>
        @elseif($satpams->isEmpty())
            <div class="text-center py-4 text-muted">
                <i class="bi bi-person-x display-6 d-block mb-2"></i>
                Belum ada pegawai Outsourcing dengan jabatan "Satpam" yang aktif.
            </div>
        @else
            <form action="{{ route('admin.shifts.schedule.bulk') }}" method="POST" id="bulkScheduleForm">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered shift-table mb-0">
                        <thead>
                            <tr>
                                <th class="bg-light">Satpam</th>
                                @foreach($days as $day)
                                    <th class="day-header {{ $day->isWeekend() ? 'weekend-header' : '' }}">
                                        <div class="day-name">{{ $day->translatedFormat('D') }}</div>
                                        <div class="day-date">{{ $day->format('d/m') }}</div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($satpams as $idx => $satpam)
                            <tr>
                                <td class="text-start">
                                    <div class="fw-bold small">{{ $satpam->name }}</div>
                                    <div class="text-muted" style="font-size: 0.7rem;">{{ $satpam->outsourcingEmployee->company_name ?? '' }}</div>
                                </td>
                                @foreach($days as $dayIdx => $day)
                                    @php
                                        $key = $satpam->id . '_' . $day->format('Y-m-d');
                                        $schedule = $schedules->get($key)?->first();
                                        $inputName = 'schedules[' . ($idx * count($days) + $dayIdx) . ']';
                                    @endphp
                                    <td class="{{ $day->isWeekend() ? 'weekend-cell' : '' }}">
                                        <input type="hidden" name="{{ $inputName }}[user_id]" value="{{ $satpam->id }}">
                                        <input type="hidden" name="{{ $inputName }}[tanggal]" value="{{ $day->format('Y-m-d') }}">
                                        <select name="{{ $inputName }}[shift_id]" class="form-select shift-select">
                                            <option value="">Libur</option>
                                            @foreach($shifts as $shift)
                                                <option value="{{ $shift->id }}" {{ $schedule && $schedule->shift_id == $shift->id ? 'selected' : '' }}>
                                                    {{ $shift->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary-custom px-4">
                        <i class="bi bi-save me-2"></i> Simpan Jadwal Mingguan
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>

{{-- Modal Tambah Shift --}}
<div class="modal fade" id="addShiftModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Shift Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.shifts.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Shift</label>
                        <input type="text" class="form-control" name="name" placeholder="Misal: Shift Pagi" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Jam Masuk (Mulai)</label>
                            <input type="time" class="form-control" name="jam_masuk_start" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Jam Masuk (Selesai)</label>
                            <input type="time" class="form-control" name="jam_masuk_end" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Batas Terlambat</label>
                            <input type="time" class="form-control" name="batas_terlambat" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Jam Pulang</label>
                            <input type="time" class="form-control" name="jam_pulang" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDeleteShift(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Shift dan semua jadwal terkait akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-shift-' + id).submit();
            }
        })
    }
</script>
@endpush
