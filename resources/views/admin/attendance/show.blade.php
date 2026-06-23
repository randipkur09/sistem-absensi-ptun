@extends('layouts.admin')

@section('title', 'Detail Absensi')
@section('page-title', 'Detail Absensi')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .map-container { height: 300px; border-radius: 12px; z-index: 1; border: 1px solid #e2e8f0; }
    .photo-container {
        width: 100%;
        height: 300px;
        border-radius: 12px;
        overflow: hidden;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e2e8f0;
    }
    .photo-container img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
</style>
@endpush

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline-custom">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Absensi
        </a>
    </div>
</div>

<div class="row g-4 animate-fade-in">
    <!-- Info Pegawai -->
    <div class="col-12">
        <div class="card-custom p-4 d-flex align-items-center gap-4">
            @if($attendance->user->photo)
                <img src="{{ asset('storage/' . $attendance->user->photo) }}" alt="Foto" class="rounded-circle shadow-sm" style="width: 80px; height: 80px; object-fit: cover;">
            @else
                <div class="rounded-circle text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 80px; height: 80px; font-size: 2rem; font-weight: bold; background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                    {{ strtoupper(substr($attendance->user->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <h4 class="fw-bold mb-1">{{ $attendance->user->name }}</h4>
                <div class="text-muted">{{ $attendance->user->email }} | {{ ucfirst($attendance->user->employee_type) }}</div>
                <div class="mt-2">
                    <span class="badge bg-light text-dark me-2"><i class="bi bi-calendar3 me-1"></i> {{ $attendance->tanggal->translatedFormat('l, d F Y') }}</span>
                    <span class="badge-status badge-{{ $attendance->status }}"><i class="bi bi-info-circle me-1"></i> {{ ucfirst($attendance->status) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Absen Masuk -->
    <div class="col-md-6">
        <div class="card-custom h-100">
            <div class="card-header text-white" style="background: var(--success);">
                <span><i class="bi bi-box-arrow-in-right me-2"></i> Data Absen Masuk</span>
            </div>
            <div class="card-body">
                @if($attendance->jam_masuk)
                    <div class="row mb-3 text-center">
                        <div class="col-6 border-end">
                            <div class="text-muted small">Waktu</div>
                            <h4 class="fw-bold text-success">{{ \Carbon\Carbon::parse($attendance->jam_masuk)->format('H:i:s') }}</h4>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Jarak dari Kantor</div>
                            <h4 class="fw-bold">{{ $attendance->jarak_masuk }} <small>m</small></h4>
                        </div>
                    </div>
                    
                    <h6 class="fw-bold mb-2 text-muted small">Foto Bukti</h6>
                    <div class="photo-container mb-3">
                        @if($attendance->foto_masuk_url)
                            <img src="{{ $attendance->foto_masuk_url }}" alt="Foto Masuk">
                        @else
                            <div class="text-muted"><i class="bi bi-image" style="font-size: 3rem;"></i><br>Tidak ada foto</div>
                        @endif
                    </div>

                    <h6 class="fw-bold mb-2 text-muted small">Lokasi GPS</h6>
                    <div id="map-masuk" class="map-container"></div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-clock-history fs-1 d-block mb-2"></i>
                        Belum ada data absen masuk.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Absen Pulang -->
    <div class="col-md-6">
        <div class="card-custom h-100">
            <div class="card-header text-white" style="background: var(--primary);">
                <span><i class="bi bi-box-arrow-right me-2"></i> Data Absen Pulang</span>
            </div>
            <div class="card-body">
                @if($attendance->jam_pulang)
                    <div class="row mb-3 text-center">
                        <div class="col-6 border-end">
                            <div class="text-muted small">Waktu</div>
                            <h4 class="fw-bold text-primary">{{ \Carbon\Carbon::parse($attendance->jam_pulang)->format('H:i:s') }}</h4>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Jarak dari Kantor</div>
                            <h4 class="fw-bold">{{ $attendance->jarak_pulang }} <small>m</small></h4>
                        </div>
                    </div>
                    
                    <h6 class="fw-bold mb-2 text-muted small">Foto Bukti</h6>
                    <div class="photo-container mb-3">
                        @if($attendance->foto_pulang_url)
                            <img src="{{ $attendance->foto_pulang_url }}" alt="Foto Pulang">
                        @else
                            <div class="text-muted"><i class="bi bi-image" style="font-size: 3rem;"></i><br>Tidak ada foto</div>
                        @endif
                    </div>

                    <h6 class="fw-bold mb-2 text-muted small">Lokasi GPS</h6>
                    <div id="map-pulang" class="map-container"></div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-clock-history fs-1 d-block mb-2"></i>
                        Belum ada data absen pulang.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const officeLat = {{ \App\Models\AttendanceSetting::current()->office_latitude }};
    const officeLng = {{ \App\Models\AttendanceSetting::current()->office_longitude }};
    const maxRadius = {{ \App\Models\AttendanceSetting::current()->max_radius_meters }};

    function initMap(mapId, lat, lng, type) {
        if (!document.getElementById(mapId)) return;
        
        const map = L.map(mapId).setView([officeLat, officeLng], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Office Marker
        L.marker([officeLat, officeLng]).addTo(map).bindPopup('Lokasi Kantor');
        L.circle([officeLat, officeLng], {
            color: '#1a5632', fillColor: '#1a5632', fillOpacity: 0.1, radius: maxRadius
        }).addTo(map);

        // User Marker
        if (lat && lng) {
            L.marker([lat, lng], {icon: L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
            })}).addTo(map).bindPopup('Lokasi Absen ' + type).openPopup();

            // Adjust view to fit both markers
            const bounds = L.latLngBounds([
                [officeLat, officeLng],
                [lat, lng]
            ]);
            map.fitBounds(bounds, { padding: [30, 30] });
        }
    }

    @if($attendance->jam_masuk)
        initMap('map-masuk', {{ $attendance->latitude_masuk }}, {{ $attendance->longitude_masuk }}, 'Masuk');
    @endif

    @if($attendance->jam_pulang)
        initMap('map-pulang', {{ $attendance->latitude_pulang }}, {{ $attendance->longitude_pulang }}, 'Pulang');
    @endif
</script>
@endpush
