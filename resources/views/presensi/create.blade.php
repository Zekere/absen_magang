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

    /* Map responsif */
    #map {
        width: 100%;
        height: 300px;
        min-height: 200px;
        border-radius: 10px;
        margin-top: 15px;
    }

    /* Tombol Absen */
    #takeabsen {
        font-size: 1rem;
        padding: 12px 30px;
        border-radius: 50px;
    }

    /* Mobile-friendly */
    @media (max-width: 768px) {
        .pageTitle {
            font-size: 18px;
        }
        #map {
            height: 250px;
        }
        #takeabsen {
            width: 100%;
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
<div class="container" style="margin-top: 70px">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 text-center">
            <input type="hidden" id="lokasi">
            <div class="webcam-capture"></div>
        </div>
    </div>

    <div class="row mt-4 justify-content-center">
        <div class="col-12 col-md-6 text-center">
            @if($cek > 0)
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

    <!-- Map -->
    <div class="row mt-3 justify-content-center">
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

    // Lokasi
    var lokasi = document.getElementById('lokasi');
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
    }

    function successCallback(position) {
        lokasi.value = position.coords.latitude + ',' + position.coords.longitude;
        var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 15);
        var lokasi_kantor = "{{$lok_kantor->lokasi_kantor}}";
        var lok = lokasi_kantor.split(',');
        var lat_kantor = lok[0];
        var long_kantor = lok[1];
        var radius = "{{$lok_kantor->radius}}";

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
        var circle = L.circle([lat_kantor, long_kantor], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: radius
        }).addTo(map);
        marker.bindPopup("<b>Anda Disini</b>").openPopup();
    }

    function errorCallback(position) {
        alert("Tidak bisa mendapatkan lokasi!");
    }

    // Tombol Absen
    $('#takeabsen').click(function(e) {
        e.preventDefault();

        Webcam.snap(function(uri) {
            let image = uri; 
            let lokasi = $('#lokasi').val();

            // 🔎 Debug lokasi
            console.log("Lokasi yang dikirim:", lokasi);

            if(!lokasi){
                Swal.fire({
                    title:"Error!",
                    text:"Lokasi tidak terbaca. Pastikan GPS aktif dan izinkan akses lokasi!",
                    icon: "error",
                });
                return; // hentikan proses kalau lokasi kosong
            }

            // Kirim ke server
            $.ajax({
                url: '/presensi/store',
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    lokasi: lokasi,
                    image: image
                },
                cache: false,
                success: function(respond) {
                    var status = respond.split("|"); 
                    // status[0] = success / error
                    // status[1] = pesan
                    // status[2] = in / out

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
                        text:"status[1]",
                        icon: "error",
                    });
                    console.error("Error AJAX:", xhr); 
                }
            });
        });
    });
</script>
@endpush