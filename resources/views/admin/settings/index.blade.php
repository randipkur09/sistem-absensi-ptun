@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map-preview { height: 350px; border-radius: 12px; z-index: 1; border: 1px solid #e2e8f0; }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10 animate-fade-in">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="card-custom mb-4">
                <div class="card-header">
                    <span><i class="bi bi-geo-alt-fill me-2"></i> Pengaturan Lokasi Kantor</span>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Nama Kantor</label>
                            <input type="text" class="form-control" name="office_name" value="{{ old('office_name', $setting->office_name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Radius Maksimal Absensi (Meter)</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="max_radius_meters" value="{{ old('max_radius_meters', $setting->max_radius_meters) }}" required min="10" max="1000">
                                <span class="input-group-text">Meter</span>
                            </div>
                            <div class="form-text">Jarak maksimal pegawai dapat melakukan absensi dari titik koordinat.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Latitude</label>
                            <input type="text" class="form-control" id="office_latitude" name="office_latitude" value="{{ old('office_latitude', $setting->office_latitude) }}" required readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Longitude</label>
                            <input type="text" class="form-control" id="office_longitude" name="office_longitude" value="{{ old('office_longitude', $setting->office_longitude) }}" required readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control" name="office_address" rows="2">{{ old('office_address', $setting->office_address) }}</textarea>
                        </div>
                        
                        <div class="col-12 mt-4">
                            <label class="form-label fw-bold text-primary">Preview & Atur Titik Lokasi (Geser Marker)</label>
                            <div id="map-preview"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-custom mb-4">
                <div class="card-header">
                    <span><i class="bi bi-clock-fill me-2"></i> Pengaturan Jam Kerja</span>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-3">
                            <label class="form-label">Jam Masuk (Mulai)</label>
                            <input type="time" class="form-control" name="jam_masuk_start" value="{{ old('jam_masuk_start', \Carbon\Carbon::parse($setting->jam_masuk_start)->format('H:i')) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jam Masuk (Selesai)</label>
                            <input type="time" class="form-control" name="jam_masuk_end" value="{{ old('jam_masuk_end', \Carbon\Carbon::parse($setting->jam_masuk_end)->format('H:i')) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Batas Keterlambatan</label>
                            <input type="time" class="form-control" name="batas_terlambat" value="{{ old('batas_terlambat', \Carbon\Carbon::parse($setting->batas_terlambat)->format('H:i')) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jam Pulang</label>
                            <input type="time" class="form-control" name="jam_pulang" value="{{ old('jam_pulang', \Carbon\Carbon::parse($setting->jam_pulang)->format('H:i')) }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end mb-5">
                <button type="submit" class="btn btn-primary-custom px-5 py-2 fs-6">
                    <i class="bi bi-save me-2"></i> Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const latInput = document.getElementById('office_latitude');
    const lngInput = document.getElementById('office_longitude');
    const radiusInput = document.querySelector('input[name="max_radius_meters"]');
    
    let currentLat = parseFloat(latInput.value) || -5.3971;
    let currentLng = parseFloat(lngInput.value) || 105.2668;
    let currentRadius = parseFloat(radiusInput.value) || 50;

    const map = L.map('map-preview').setView([currentLat, currentLng], 17);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    const marker = L.marker([currentLat, currentLng], {
        draggable: true
    }).addTo(map);

    const circle = L.circle([currentLat, currentLng], {
        color: 'blue',
        fillColor: '#3b82f6',
        fillOpacity: 0.2,
        radius: currentRadius
    }).addTo(map);

    marker.on('dragend', function (e) {
        const position = marker.getLatLng();
        latInput.value = position.lat.toFixed(7);
        lngInput.value = position.lng.toFixed(7);
        
        circle.setLatLng(position);
        map.panTo(position);
    });

    radiusInput.addEventListener('input', function() {
        const newRadius = parseFloat(this.value) || 50;
        circle.setRadius(newRadius);
    });
</script>
@endpush
