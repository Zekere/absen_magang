@extends('layout.presensi')
@section('header')
<div class = "appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name = "chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">E-Presensi</div>
    <div class="right"></div>
</div>
<style>
    .webcam-capture,
    .webcam-capture video {
        display: inline-block;
        width:100% important;
        margin: auto;
        height: auto !important;
        border-radius: 15px;

    }

    #map {
    width: 100%;
    height: 300px;   /* jangan kurang dari 200px */
    border-radius: 10px;
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
<div class="row" style="margin-top: 70px">
    <div class="col">
        <input type="hidden" id="lokasi">
        <div class="webcam-capture"></div>
    </div>
</div>
<div class="row mt-4">
    <div class="col text-center">
        <button id="takeabsen" class="btn btn-success btn-lg shadow rounded-pill px-5">
            <i class="bi bi-fingerprint me-2">
                <ion-icon name="camera-outline"></ion-icon>
            </i> Absen Masuk
        </button>
    </div>
</div>


</div>
<div class="row mt-2">
    <div class="col"></div>
     <div id="map"></div>
</div>
</div>
@endsection

@push ('scripts')
<script>
    Webcam.set({
        width:420,
        height:720,
        image_format:'jpeg',
        jpeg_quality:90
});
Webcam.attach('.webcam-capture');

var lokasi = document.getElementById('lokasi');
if(navigator.geolocation){
    navigator.geolocation.getCurrentPosition(successCallback, errorCallback);

}

function successCallback(position){
    
    lokasi.value = position.coords.latitude + ',' + position.coords.longitude;
    var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 13);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
var circle = L.circle([position.coords.latitude, position.coords.longitude],{
    color: 'red',
    fillColor: '#f03',
    fillOpacity: 0.5,
    radius: 20
}).addTo(map);
marker.bindPopup("<b>Anda Disini</b>").openPopup();

}
function errorCallback(position){}
</script>
@endpush