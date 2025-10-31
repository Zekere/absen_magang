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

    // Inisialisasi map dengan preferScrollWheelZoom false agar tidak zoom saat scroll
    var map = L.map('map', {
        center: lok_kantor,
        zoom: 17,
        zoomControl: true,
        scrollWheelZoom: true
    });

    // Tambahkan tile layer (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
        minZoom: 10
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

    // Array untuk menyimpan semua layer
    var allLayers = [];

    // Marker kantor dengan popup
    var kantorMarker = L.marker(lok_kantor, {icon: kantorIcon}).addTo(map);
    kantorMarker.bindPopup("<b><i class='bi bi-building'></i> Lokasi Kantor</b><br>Radius: " + radius_kantor + " meter");
    allLayers.push(kantorMarker);

    // Circle radius kantor
    var circle = L.circle(lok_kantor, {
        color: '#3388ff',
        fillColor: '#3388ff',
        fillOpacity: 0.15,
        radius: radius_kantor
    }).addTo(map);
    allLayers.push(circle);

    // Marker lokasi absen masuk
    if (lok_masuk) {
        var masukMarker = L.marker(lok_masuk, {icon: masukIcon}).addTo(map);
        
        // Hitung jarak dari kantor
        var jarakMasuk = (map.distance(lok_kantor, lok_masuk)).toFixed(0);
        var statusMasuk = jarakMasuk <= radius_kantor 
            ? '<span style="color: green; font-weight: bold;">✓ Dalam Radius</span>' 
            : '<span style="color: red; font-weight: bold;">✗ Luar Radius (' + jarakMasuk + 'm)</span>';
        
        masukMarker.bindPopup(
            "<b><i class='bi bi-box-arrow-in-right'></i> Absen Masuk</b><br>" +
            "Jam: <strong>{{ $presensi->jam_in ?? '-' }}</strong><br>" +
            "Jarak: <strong>" + jarakMasuk + " meter</strong><br>" +
            statusMasuk
        );
        allLayers.push(masukMarker);
        
        // Garis dari kantor ke lokasi masuk
        var polylineMasuk = L.polyline([lok_kantor, lok_masuk], {
            color: 'green',
            weight: 2,
            opacity: 0.6,
            dashArray: '5, 10'
        }).addTo(map);
        allLayers.push(polylineMasuk);
    }

    // Marker lokasi absen pulang
    if (lok_pulang) {
        var pulangMarker = L.marker(lok_pulang, {icon: pulangIcon}).addTo(map);
        
        // Hitung jarak dari kantor
        var jarakPulang = (map.distance(lok_kantor, lok_pulang)).toFixed(0);
        var statusPulang = jarakPulang <= radius_kantor 
            ? '<span style="color: green; font-weight: bold;">✓ Dalam Radius</span>' 
            : '<span style="color: red; font-weight: bold;">✗ Luar Radius (' + jarakPulang + 'm)</span>';
        
        pulangMarker.bindPopup(
            "<b><i class='bi bi-box-arrow-right'></i> Absen Pulang</b><br>" +
            "Jam: <strong>{{ $presensi->jam_out ?? '-' }}</strong><br>" +
            "Jarak: <strong>" + jarakPulang + " meter</strong><br>" +
            statusPulang
        );
        allLayers.push(pulangMarker);
        
        // Garis dari kantor ke lokasi pulang
        var polylinePulang = L.polyline([lok_kantor, lok_pulang], {
            color: 'red',
            weight: 2,
            opacity: 0.6,
            dashArray: '5, 10'
        }).addTo(map);
        allLayers.push(polylinePulang);
    }

    // FIT BOUNDS SETELAH SEMUA MARKER DIBUAT
    // Tunggu sebentar agar map selesai render
    setTimeout(function() {
        if (allLayers.length > 0) {
            var group = new L.featureGroup(allLayers);
            map.fitBounds(group.getBounds().pad(0.2));
        }
        
        // Buka popup lokasi masuk jika ada
        if (lok_masuk && typeof masukMarker !== 'undefined') {
            masukMarker.openPopup();
        } else {
            // Jika tidak ada absen masuk, buka popup kantor
            kantorMarker.openPopup();
        }
    }, 300);

    // Event listener untuk memastikan map ter-render dengan benar
    map.on('load', function() {
        map.invalidateSize();
    });

    // Trigger invalidateSize setelah modal ditampilkan
    setTimeout(function() {
        map.invalidateSize();
    }, 500);
</script>
@else
<div class="alert alert-warning">
    <i class="bi bi-exclamation-triangle-fill"></i> Data presensi tidak ditemukan
</div>
@endif