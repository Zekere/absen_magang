@if($presensi)
<style>
    #map {
        height: 450px;
        width: 100%;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
</style>

<div id="map"></div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // Lokasi kantor
    var lok_kantor = [{{ $lok_kantor->lokasi_kantor }}];
    var radius_kantor = {{ $lok_kantor->radius }};

    // Lokasi presensi masuk
    @if(!empty($presensi->lokasi_in))
    var lok_masuk = [{{ $presensi->lokasi_in }}];
    @else
    var lok_masuk = null;
    @endif

    // Lokasi presensi pulang
    @if(!empty($presensi->lokasi_out))
    var lok_pulang = [{{ $presensi->lokasi_out }}];
    @else
    var lok_pulang = null;
    @endif

    // Inisialisasi map
    var map = L.map('map').setView(lok_kantor, 17);

    // Tambahkan tile layer (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19
    }).addTo(map);

    // Icon untuk marker kantor (Biru)
    var kantorIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    // Icon untuk marker masuk (Hijau)
    var masukIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    // Icon untuk marker pulang (Merah)
    var pulangIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    // Marker kantor dengan popup
    var kantorMarker = L.marker(lok_kantor, {icon: kantorIcon}).addTo(map);
    kantorMarker.bindPopup("<b><i class='bi bi-building'></i> Lokasi Kantor</b><br>Radius: " + radius_kantor + " meter").openPopup();

    // Circle radius kantor
    var circle = L.circle(lok_kantor, {
        color: '#3388ff',
        fillColor: '#3388ff',
        fillOpacity: 0.15,
        radius: radius_kantor
    }).addTo(map);

    // Marker lokasi absen masuk
    if (lok_masuk) {
        var masukMarker = L.marker(lok_masuk, {icon: masukIcon}).addTo(map);
        masukMarker.bindPopup("<b><i class='bi bi-box-arrow-in-right'></i> Absen Masuk</b><br>Jam: {{ $presensi->jam_in ?? '-' }}");
    }

    // Marker lokasi absen pulang
    if (lok_pulang) {
        var pulangMarker = L.marker(lok_pulang, {icon: pulangIcon}).addTo(map);
        pulangMarker.bindPopup("<b><i class='bi bi-box-arrow-right'></i> Absen Pulang</b><br>Jam: {{ $presensi->jam_out ?? '-' }}");
    }

    // Fit map ke semua marker
    var group = new L.featureGroup([kantorMarker, circle]);
    if (lok_masuk) group.addLayer(masukMarker);
    if (lok_pulang) group.addLayer(pulangMarker);
    
    map.fitBounds(group.getBounds().pad(0.3));
</script>
@else
<div class="alert alert-warning">
    <i class="bi bi-exclamation-triangle-fill"></i> Data presensi tidak ditemukan
</div>
@endif