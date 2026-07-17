@extends('layouts.employee')

@section('title', 'Absensi GPS & Kamera')
@section('page-title', 'Absensi')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map { height: 250px; border-radius: 12px; z-index: 1; }
    #camera-container {
        position: relative;
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
        border-radius: 16px;
        overflow: hidden;
        background: #000;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    #video {
        width: 100%;
        display: block;
        transform: scaleX(-1); /* Mirror effect */
    }
    #canvas { display: none; }
    #photo-preview {
        width: 100%;
        display: none;
        transform: scaleX(-1); /* Mirror effect */
    }
    .camera-overlay {
        position: absolute;
        bottom: 20px;
        left: 0;
        width: 100%;
        text-align: center;
        z-index: 10;
    }
    .btn-capture {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        border: 4px solid #fff;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-block;
    }
    .btn-capture:hover { background: rgba(255, 255, 255, 0.6); transform: scale(1.05); }
    .btn-retake { display: none; }
    .info-box {
        background: #f8faf8;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid var(--border-color);
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 animate-fade-in">
        <div class="card-custom">
            <div class="card-header bg-white border-bottom text-center py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-clock me-2"></i><span id="live-clock">00:00:00</span></h5>
                <div class="text-muted small mt-1">{{ now()->translatedFormat('l, d F Y') }}</div>
            </div>
            <div class="card-body p-4">
                @if($todayAttendance && ($todayAttendance->jam_pulang || !in_array($todayAttendance->status, ['hadir', 'terlambat'])))
                    <div class="text-center py-5">
                        <div class="display-1 mb-3" style="color: var(--success);"><i class="bi bi-check-circle-fill"></i></div>
                        <h4>Absensi Selesai</h4>
                        <p class="text-muted">Anda sudah melakukan absensi hari ini (Status: {{ ucfirst($todayAttendance->status) }}).</p>
                    </div>
                @else
                    @if(auth()->user()->isSatpam())
                        @if($shiftForToday)
                            <div class="alert alert-info mb-4 border-0 shadow-sm rounded-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-info bg-opacity-25 p-2 rounded-circle me-3">
                                        <i class="bi bi-clock-history fs-4 text-info"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold">Jadwal Anda: {{ $shiftForToday->name }}</h6>
                                        <div class="small mb-0">
                                            Masuk: {{ \Carbon\Carbon::parse($shiftForToday->jam_masuk_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($shiftForToday->jam_masuk_end)->format('H:i') }} | 
                                            Pulang: {{ \Carbon\Carbon::parse($shiftForToday->jam_pulang)->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning mb-4 border-0 shadow-sm rounded-3">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i> Anda tidak memiliki jadwal shift hari ini (Libur). Jika ini kesalahan, hubungi Admin.
                            </div>
                        @endif
                    @endif

                    <div class="row g-4">
                        <!-- Camera Section -->
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3 text-center">1. Ambil Foto Wajah</h6>
                            <div id="camera-container">
                                <video id="video" autoplay playsinline></video>
                                <img id="photo-preview" src="" alt="Preview">
                                <canvas id="canvas"></canvas>
                                <div class="camera-overlay">
                                    <button type="button" class="btn-capture" id="btn-capture" title="Ambil Foto"></button>
                                    <button type="button" class="btn btn-light btn-sm btn-retake rounded-pill px-3 shadow" id="btn-retake">
                                        <i class="bi bi-arrow-repeat me-1"></i> Ulangi
                                    </button>
                                </div>
                            </div>
                            <div class="text-center mt-2 small text-muted">
                                <i class="bi bi-info-circle me-1"></i>Pastikan wajah terlihat jelas.
                            </div>
                        </div>

                        <!-- Location Section -->
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3 text-center">2. Lokasi GPS</h6>
                            <div id="map" class="mb-2 border"></div>
                            
                            <div class="info-box">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted small">Status Lokasi:</span>
                                    <span id="location-status" class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Mencari...</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted small">Jarak dari Kantor:</span>
                                    <span id="distance-info" class="fw-bold">- meter</span>
                                </div>
                            </div>

                            <form id="attendance-form">
                                <input type="hidden" id="latitude" name="latitude">
                                <input type="hidden" id="longitude" name="longitude">
                                <input type="hidden" id="foto" name="foto">
                                
                                @if(!$todayAttendance || !$todayAttendance->jam_masuk)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-muted">Status Absensi</label>
                                        <select class="form-select mb-2" id="status_absensi" name="status_absensi">
                                            <option value="hadir">Hadir</option>
                                            <option value="sakit">Sakit</option>
                                            <option value="izin">Izin</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 d-none" id="keterangan_container">
                                        <label class="form-label fw-bold small text-muted">Keterangan</label>
                                        <textarea class="form-control" id="keterangan" name="keterangan" rows="2" placeholder="Masukkan alasan / keterangan (wajib)"></textarea>
                                    </div>
                                    
                                    <button type="button" id="btn-submit-masuk" class="btn btn-primary-custom w-100 py-2 mb-2" disabled>
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Absen Masuk
                                    </button>
                                @elseif(!$todayAttendance->jam_pulang && in_array($todayAttendance->status, ['hadir', 'terlambat']))
                                    <button type="button" id="btn-submit-pulang" class="btn btn-success-custom w-100 py-2 mb-2" disabled>
                                        <i class="bi bi-box-arrow-right me-2"></i>Absen Pulang
                                    </button>
                                @endif
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const officeLat = {{ $setting->office_latitude }};
    const officeLng = {{ $setting->office_longitude }};
    const maxRadius = {{ $setting->max_radius_meters }};
    
    // Live Clock
    function updateClock() {
        const now = new Date();
        document.getElementById('live-clock').innerText = now.toLocaleTimeString('id-ID', { hour12: false });
    }
    setInterval(updateClock, 1000);
    updateClock();

    @if(!($todayAttendance && $todayAttendance->jam_masuk && $todayAttendance->jam_pulang))
        // --- Camera Logic ---
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const photoPreview = document.getElementById('photo-preview');
        const btnCapture = document.getElementById('btn-capture');
        const btnRetake = document.getElementById('btn-retake');
        const inputFoto = document.getElementById('foto');
        
        let stream = null;

        async function startCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
                video.srcObject = stream;
            } catch (err) {
                Swal.fire('Kamera Tidak Tersedia', 'Tidak dapat mengakses kamera: ' + err.message, 'error');
            }
        }

        startCamera();

        btnCapture.addEventListener('click', () => {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            
            const photoData = canvas.toDataURL('image/jpeg', 0.8);
            inputFoto.value = photoData;
            photoPreview.src = photoData;
            
            video.style.display = 'none';
            photoPreview.style.display = 'block';
            btnCapture.style.display = 'none';
            btnRetake.style.display = 'inline-block';
            
            checkSubmitStatus();
        });

        btnRetake.addEventListener('click', () => {
            inputFoto.value = '';
            photoPreview.src = '';
            
            photoPreview.style.display = 'none';
            video.style.display = 'block';
            btnRetake.style.display = 'none';
            btnCapture.style.display = 'inline-block';
            
            checkSubmitStatus();
        });

        // --- GPS & Map Logic ---
        const map = L.map('map').setView([officeLat, officeLng], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Office marker & circle
        L.marker([officeLat, officeLng]).addTo(map).bindPopup('Lokasi Kantor').openPopup();
        L.circle([officeLat, officeLng], {
            color: '#1a5632', fillColor: '#1a5632', fillOpacity: 0.15, radius: maxRadius
        }).addTo(map);

        let userMarker = null;
        let isLocationValid = false;
        const inputLat = document.getElementById('latitude');
        const inputLng = document.getElementById('longitude');

        // Haversine formula
        function getDistance(lat1, lon1, lat2, lon2) {
            const R = 6371e3;
            const p1 = lat1 * Math.PI/180;
            const p2 = lat2 * Math.PI/180;
            const dp = (lat2-lat1) * Math.PI/180;
            const dl = (lon2-lon1) * Math.PI/180;

            const a = Math.sin(dp/2) * Math.sin(dp/2) +
                    Math.cos(p1) * Math.cos(p2) *
                    Math.sin(dl/2) * Math.sin(dl/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

            return R * c;
        }

        if (navigator.geolocation) {
            navigator.geolocation.watchPosition((position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                inputLat.value = lat;
                inputLng.value = lng;

                if (userMarker) {
                    map.removeLayer(userMarker);
                }

                userMarker = L.marker([lat, lng], {icon: L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                })}).addTo(map).bindPopup('Lokasi Anda');

                const distance = getDistance(lat, lng, officeLat, officeLng);
                document.getElementById('distance-info').innerText = Math.round(distance) + ' meter';

                if (distance <= maxRadius) {
                    document.getElementById('location-status').className = 'badge bg-success';
                    document.getElementById('location-status').innerHTML = '<i class="bi bi-geo-alt-fill"></i> Di Area Kantor';
                    isLocationValid = true;
                } else {
                    document.getElementById('location-status').className = 'badge bg-danger';
                    document.getElementById('location-status').innerHTML = '<i class="bi bi-x-circle-fill"></i> Di Luar Radius';
                    isLocationValid = false;
                }

                checkSubmitStatus();

            }, (error) => {
                let msg = '';
                switch(error.code) {
                    case error.PERMISSION_DENIED: msg = "Akses lokasi ditolak pengguna."; break;
                    case error.POSITION_UNAVAILABLE: msg = "Informasi lokasi tidak tersedia."; break;
                    case error.TIMEOUT: msg = "Waktu permintaan lokasi habis."; break;
                    default: msg = "Terjadi kesalahan tidak diketahui."; break;
                }
                document.getElementById('location-status').className = 'badge bg-danger';
                document.getElementById('location-status').innerText = 'Error GPS';
                Swal.fire('Error GPS', msg, 'error');
            }, {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            });
        } else {
            Swal.fire('Error GPS', 'Browser Anda tidak mendukung Geolocation', 'error');
        }

        // --- Submit Logic ---
        const btnMasuk = document.getElementById('btn-submit-masuk');
        const btnPulang = document.getElementById('btn-submit-pulang');
        const statusSelect = document.getElementById('status_absensi');
        const ketContainer = document.getElementById('keterangan_container');
        const ketInput = document.getElementById('keterangan');

        if (statusSelect) {
            statusSelect.addEventListener('change', function() {
                if (this.value === 'hadir') {
                    ketContainer.classList.add('d-none');
                } else {
                    ketContainer.classList.remove('d-none');
                }
                checkSubmitStatus();
            });
        }
        
        if (ketInput) {
            ketInput.addEventListener('input', checkSubmitStatus);
        }

        function checkSubmitStatus() {
            const hasPhoto = inputFoto.value !== '';
            
            if (btnMasuk) {
                const status = statusSelect ? statusSelect.value : 'hadir';
                const hasKet = ketInput ? ketInput.value.trim() !== '' : false;
                
                if (status === 'hadir') {
                    btnMasuk.disabled = !(isLocationValid && hasPhoto);
                } else {
                    btnMasuk.disabled = !(hasPhoto && hasKet);
                }
            }
            if (btnPulang) {
                btnPulang.disabled = !(isLocationValid && hasPhoto);
            }
        }

        function submitAttendance(url, type) {
            const status = statusSelect ? statusSelect.value : 'hadir';
            const keterangan = ketInput ? ketInput.value : '';

            Swal.fire({
                title: 'Memproses Absensi...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    latitude: inputLat.value,
                    longitude: inputLng.value,
                    foto: inputFoto.value,
                    status_absensi: status,
                    keterangan: keterangan
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    Swal.fire('Berhasil!', data.message, 'success').then(() => {
                        if (type === 'masuk') {
                            window.location.href = '{{ route("employee.dashboard", [], false) }}';
                        } else {
                            window.location.reload();
                        }
                    });
                } else {
                    Swal.fire('Gagal', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
                console.error('Error:', error);
            });
        }

        if (btnMasuk) {
            btnMasuk.addEventListener('click', () => submitAttendance('{{ route("employee.attendance.check-in", [], false) }}', 'masuk'));
        }
        if (btnPulang) {
            btnPulang.addEventListener('click', () => submitAttendance('{{ route("employee.attendance.check-out", [], false) }}', 'pulang'));
        }
    @endif
</script>
@endpush
