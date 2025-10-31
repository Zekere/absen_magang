@extends('layout.presensi')

@section('header')
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">E-Presensi</div>
    <div class="right"></div>
</div>

<style>
    /* Fix konten agar tidak tertutup navbar */
    .container {
        padding-top: 70px;
        padding-bottom: 80px; /* Ruang untuk bottom navbar jika ada */
    }

    /* Webcam responsif */
    .webcam-capture,
    .webcam-capture video {
        display: block;
        width: 100% !important;
        max-width: 600px;
        margin: auto;
        height: auto !important;
        border-radius: 15px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }

    /* Map responsif dengan posisi yang lebih baik */
    #map {
        width: 100%;
        height: 300px;
        min-height: 200px;
        border-radius: 10px;
        margin-top: 15px;
        z-index: 1; /* Pastikan di bawah navbar */
    }

    /* Tombol Absen */
    #takeabsen {
        font-size: 1rem;
        padding: 12px 30px;
        border-radius: 50px;
    }

    /* Status Lokasi */
    #location-status {
        margin-top: 15px;
        padding: 12px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        display: none;
    }

    .status-dalam-kantor {
        background-color: #d4edda;
        color: #155724;
        border: 2px solid #c3e6cb;
    }

    .status-luar-kantor {
        background-color: #fff3cd;
        color: #856404;
        border: 2px solid #ffeaa7;
    }

    /* Spacing section */
    .webcam-section {
        margin-bottom: 20px;
    }

    .button-section {
        margin: 20px 0;
    }

    .map-section {
        margin-top: 20px;
        margin-bottom: 30px;
    }

    /* Mobile-friendly */
    @media (max-width: 768px) {
        .container {
            padding-top: 60px;
        }
        
        .pageTitle {
            font-size: 18px;
        }
        
        #map {
            height: 250px;
        }
        
        #takeabsen {
            width: 100%;
        }

        .webcam-capture,
        .webcam-capture video {
            max-width: 100%;
        }
    }

    /* Untuk device dengan navbar bottom */
    @media (max-width: 768px) {
        .container {
            padding-bottom: 100px;
        }
    }
</style>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
@endsection

@section('content')
<div class="container">
    <!-- Webcam Section -->
    <div class="row justify-content-center webcam-section">
        <div class="col-12 col-md-8 text-center">
            <input type="hidden" id="lokasi">
            <div class="webcam-capture"></div>
        </div>
    </div>

    <!-- Status Lokasi -->
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 text-center">
            <div id="location-status"></div>
        </div>
    </div>

    <!-- Button Section -->
    <div class="row justify-content-center button-section">
        <div class="col-12 col-md-6 text-center">
            @if($cek >= 2)
                <button class="btn btn-secondary btn-lg shadow rounded-pill" disabled>
                    <ion-icon name="checkmark-done-outline"></ion-icon> Absen Hari Ini Sudah Selesai
                </button>
                <p class="mt-2 text-muted">Anda sudah melakukan absen masuk dan pulang hari ini</p>
            @elseif($cek == 1)
                <button id="takeabsen" class="btn btn-danger btn-lg shadow rounded-pill">
                    <ion-icon name="camera-outline"></ion-icon> Absen Pulang
                </button>
            @else
                <button id="takeabsen" class="btn btn-success btn-lg shadow rounded-pill">
                    <ion-icon name="camera-outline"></ion-icon> Absen Masuk
                </button>
            @endif
        </div>
    </div>

    <!-- Map Section -->
    <div class="row justify-content-center map-section">
        <div class="col-12 col-md-10">
            <div id="map"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Setup Webcam
    Webcam.set({
        width: 420,
        height: 320,
        image_format: 'jpeg',
        jpeg_quality: 90
    });
    Webcam.attach('.webcam-capture');

    // Variabel global untuk menyimpan status lokasi
    var isInRadius = false;
    var userLatitude, userLongitude;

    // Fungsi untuk menghitung jarak antara dua koordinat (Haversine formula)
    function hitungJarak(lat1, lon1, lat2, lon2) {
        var R = 6371e3; // Radius bumi dalam meter
        var φ1 = lat1 * Math.PI / 180;
        var φ2 = lat2 * Math.PI / 180;
        var Δφ = (lat2 - lat1) * Math.PI / 180;
        var Δλ = (lon2 - lon1) * Math.PI / 180;

        var a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                Math.cos(φ1) * Math.cos(φ2) *
                Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        var jarak = R * c; // Jarak dalam meter
        return jarak;
    }

    // Lokasi
    var lokasi = document.getElementById('lokasi');
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
    }

    function successCallback(position) {
        userLatitude = position.coords.latitude;
        userLongitude = position.coords.longitude;
        
        lokasi.value = userLatitude + ',' + userLongitude;
        
        var map = L.map('map').setView([userLatitude, userLongitude], 15);
        var lokasi_kantor = "{{$lok_kantor->lokasi_kantor}}";
        var lok = lokasi_kantor.split(',');
        var lat_kantor = parseFloat(lok[0]);
        var long_kantor = parseFloat(lok[1]);
        var radius = parseFloat("{{$lok_kantor->radius}}");

        // Hitung jarak user dari kantor
        var jarakDariKantor = hitungJarak(userLatitude, userLongitude, lat_kantor, long_kantor);
        
        // Cek apakah dalam radius
        isInRadius = jarakDariKantor <= radius;

        // Tampilkan status lokasi
        var statusDiv = document.getElementById('location-status');
        statusDiv.style.display = 'block';
        
        if (isInRadius) {
            statusDiv.className = 'status-dalam-kantor';
            statusDiv.innerHTML = '<ion-icon name="checkmark-circle-outline"></ion-icon> Anda di Lingkungan Kantor';
        } else {
            statusDiv.className = 'status-luar-kantor';
            statusDiv.innerHTML = '<ion-icon name="warning-outline"></ion-icon> Anda di Luar Kantor (Jarak: ' + Math.round(jarakDariKantor) + ' meter)';
        }

        // Setup peta
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Marker posisi user (biru)
        var userMarker = L.marker([userLatitude, userLongitude], {
            icon: L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            })
        }).addTo(map);

        // Marker kantor (merah)
        var kantorMarker = L.marker([lat_kantor, long_kantor], {
            icon: L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            })
        }).addTo(map);

        // Circle radius kantor
        var circle = L.circle([lat_kantor, long_kantor], {
            color: isInRadius ? 'green' : 'red',
            fillColor: isInRadius ? '#5cb85c' : '#f03',
            fillOpacity: 0.3,
            radius: radius
        }).addTo(map);

        userMarker.bindPopup("<b>Lokasi Anda</b><br>Jarak: " + Math.round(jarakDariKantor) + " meter").openPopup();
        kantorMarker.bindPopup("<b>Kantor</b>");

        // Sesuaikan zoom agar kedua marker terlihat
        var group = L.featureGroup([userMarker, kantorMarker, circle]);
        map.fitBounds(group.getBounds().pad(0.1));
    }

    function errorCallback() {
        Swal.fire({
            title: "Error!",
            text: "Tidak bisa mendapatkan lokasi! Pastikan GPS aktif dan izinkan akses lokasi.",
            icon: "error",
        });
    }

    // Tombol Absen
    $('#takeabsen').click(function(e) {
        e.preventDefault();

        Webcam.snap(function(uri) {
            let image = uri; 
            let lokasi = $('#lokasi').val();

            // Debug lokasi
            console.log("Lokasi yang dikirim:", lokasi);
            console.log("Status dalam radius:", isInRadius);

            if(!lokasi){
                Swal.fire({
                    title:"Error!",
                    text:"Lokasi tidak terbaca. Pastikan GPS aktif dan izinkan akses lokasi!",
                    icon: "error",
                });
                return;
            }

            // Kirim ke server dengan info status lokasi
            $.ajax({
                url: '/presensi/store',
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    lokasi: lokasi,
                    image: image,
                    is_in_radius: isInRadius ? 1 : 0
                },
                cache: false,
                success: function(respond) {
                    var status = respond.split("|"); 

                    console.log("Respon server:", status);

                    if (status[0] === "success") {
                        Swal.fire({
                            title: "Success!",
                            text: status[1],
                            icon: "success",
                        });
                        setTimeout("location.href='/dashboard'", 3000);
                    } else {
                        Swal.fire({
                            title: "Error!",
                            text: status[1],
                            icon: "error",
                        });
                        setTimeout("location.href='/dashboard'", 3000);
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        title:"Error!",
                        text:"Terjadi kesalahan saat mengirim data presensi",
                        icon: "error",
                    });
                    console.error("Error AJAX:", xhr); 
                }
            });
        });
    });
</script>
@endpush