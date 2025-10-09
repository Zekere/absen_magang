 <style>
    #map { height: 250px; }

 </style>
 <div id="map"></div>
 <script>
    var lokasi = "{{ $presensi->lokasi_in }}"; 
    var lok = lokasi.split(",");
    var latitude = parseFloat(lok[0]);
    var longitude = parseFloat(lok[1]);

    var map = L.map('map').setView([latitude, longitude], 16);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    // Marker lokasi presensi
    var marker = L.marker([latitude, longitude]).addTo(map);

    // Circle area
     var circle = L.circle([-7.004715376154676, 110.40668618650808], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: 1000
        }).addTo(map);
        
        
    var popup = L.popup()
    .setLatLng([latitude, longitude])
    .setContent("{{ $presensi->nama_lengkap }}")
    .openOn(map);
</script>
