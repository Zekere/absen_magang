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
    .container {
        padding-top: 70px;
        padding-bottom: 80px;
    }

    .webcam-capture {
        position: relative;
        display: block;
        width: 100% !important;
        max-width: 600px;
        margin: auto;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    }

    .webcam-capture video,
    .webcam-capture canvas {
        display: block;
        width: 100% !important;
        height: auto !important;
        border-radius: 15px;
    }

    #face-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 10;
    }

    #face-status {
        position: absolute;
        top: 15px;
        left: 50%;
        transform: translateX(-50%);
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 14px;
        z-index: 20;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        animation: pulse 2s infinite;
        max-width: 90%;
        text-align: center;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .face-detecting {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .face-verified {
        background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
        color: white;
        animation: none;
    }

    .face-not-verified {
        background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
        color: white;
    }

    .face-loading {
        background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%);
        color: white;
    }

    #map {
        width: 100%;
        height: 300px;
        min-height: 200px;
        border-radius: 10px;
        margin-top: 15px;
        z-index: 1;
    }

    #takeabsen {
        font-size: 1rem;
        padding: 12px 30px;
        border-radius: 50px;
        transition: all 0.3s ease;
    }

    #takeabsen:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

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

    .spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Hidden image for face comparison */
    #reference-face {
        display: none;
    }

    @media (max-width: 768px) {
        .container {
            padding-top: 60px;
            padding-bottom: 100px;
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

        .webcam-capture {
            max-width: 100%;
        }

        #face-status {
            font-size: 11px;
            padding: 8px 12px;
        }
    }
</style>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>

<!-- Face-API.js -->
<script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
@endsection

@section('content')
<div class="container">
    <!-- Hidden Reference Face Image -->
    <img id="reference-face" crossorigin="anonymous" 
         src="{{ asset('storage/uploads/faces/' . Auth::guard('karyawan')->user()->nik . '_face.jpg') }}" 
         alt="Reference Face">

    <!-- Webcam Section -->
    <div class="row justify-content-center webcam-section">
        <div class="col-12 col-md-8 text-center">
            <input type="hidden" id="lokasi">
            <div class="webcam-capture">
                <video id="webcam" autoplay playsinline></video>
                <canvas id="face-overlay"></canvas>
                <div id="face-status" class="face-loading" style="display: none;">
                    <span class="spinner"></span>
                    <span>Memuat model...</span>
                </div>
            </div>
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
                <button id="takeabsen" class="btn btn-danger btn-lg shadow rounded-pill" disabled>
                    <ion-icon name="camera-outline"></ion-icon> Absen Pulang
                </button>
                <p class="mt-2 text-muted small" id="button-hint">Tunggu verifikasi wajah...</p>
            @else
                <button id="takeabsen" class="btn btn-success btn-lg shadow rounded-pill" disabled>
                    <ion-icon name="camera-outline"></ion-icon> Absen Masuk
                </button>
                <p class="mt-2 text-muted small" id="button-hint">Tunggu verifikasi wajah...</p>
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

<script>
    // ===== GLOBAL VARIABLES =====
    let faceVerified = false;
    let isInRadius = false;
    let userLatitude, userLongitude;
    let video, canvas, faceStatus, takeAbsenBtn, referenceImage;
    let faceDetectionInterval;
    let modelsLoaded = false;
    let referenceFaceDescriptor = null;

    const SIMILARITY_THRESHOLD = 0.45;
    const userNik = '{{ Auth::guard("karyawan")->user()->nik }}';

    // ===== INISIALISASI (OPTIMIZED) =====
    document.addEventListener('DOMContentLoaded', async function() {
        video = document.getElementById('webcam');
        canvas = document.getElementById('face-overlay');
        faceStatus = document.getElementById('face-status');
        takeAbsenBtn = document.getElementById('takeabsen');
        referenceImage = document.getElementById('reference-face');

        // OPTIMASI: Jalankan secara paralel
        try {
            await Promise.all([
                setupWebcam(), // Prioritas pertama - langsung akses kamera
                loadFaceModels(), // Load models sambil kamera menyala
                setupLocation() // Load lokasi bersamaan
            ]);

            // Load reference face setelah models loaded
            await loadReferenceFace();

            // Start detection
            startFaceDetection();
        } catch (error) {
            console.error('Initialization error:', error);
        }
    });

    // ===== SETUP WEBCAM (OPTIMIZED) =====
    async function setupWebcam() {
        try {
            faceStatus.style.display = 'flex';
            faceStatus.className = 'face-loading';
            faceStatus.innerHTML = '<span class="spinner"></span><span>Mengaktifkan kamera...</span>';

            // OPTIMASI: Gunakan constraint yang lebih ringan
            const stream = await navigator.mediaDevices.getUserMedia({ 
                video: { 
                    width: { ideal: 640, max: 1280 }, 
                    height: { ideal: 480, max: 720 },
                    facingMode: 'user',
                    frameRate: { ideal: 30, max: 30 } // Batasi frame rate
                },
                audio: false // Explicitly disable audio
            });
            
            video.srcObject = stream;
            
            // OPTIMASI: Gunakan play() promise untuk faster loading
            await video.play();
            
            // Set canvas size setelah video ready
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            console.log('✅ Webcam initialized');
            faceStatus.innerHTML = '<span class="spinner"></span><span>Memuat model AI...</span>';
        } catch (error) {
            console.error('❌ Webcam error:', error);
            faceStatus.className = 'face-not-verified';
            faceStatus.innerHTML = '<ion-icon name="close-circle"></ion-icon><span>Gagal mengakses kamera</span>';
            
            Swal.fire({
                title: "Error!",
                text: "Tidak dapat mengakses kamera! Pastikan izin kamera telah diberikan.",
                icon: "error",
            });
            throw error;
        }
    }

    // ===== LOAD FACE-API MODELS (OPTIMIZED) =====
    async function loadFaceModels() {
        try {
            const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model/';
            
            // OPTIMASI: Load models secara paralel
            await Promise.all([
                faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL),
                faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
            ]);

            modelsLoaded = true;
            console.log('✅ Face-API models loaded');
        } catch (error) {
            console.error('❌ Error loading models:', error);
            faceStatus.className = 'face-not-verified';
            faceStatus.innerHTML = '<ion-icon name="close-circle"></ion-icon><span>Gagal memuat model</span>';
            throw error;
        }
    }

    // ===== LOAD REFERENCE FACE (OPTIMIZED) =====
    async function loadReferenceFace() {
        try {
            faceStatus.innerHTML = '<span class="spinner"></span><span>Memuat foto referensi...</span>';
            
            // OPTIMASI: Check if image is already loaded
            if (!referenceImage.complete) {
                await new Promise((resolve, reject) => {
                    referenceImage.onload = resolve;
                    referenceImage.onerror = () => reject(new Error('Gagal memuat foto referensi'));
                    // Timeout after 5 seconds
                    setTimeout(() => reject(new Error('Timeout loading reference image')), 5000);
                });
            }

            // Deteksi wajah dari foto referensi
            const detection = await faceapi
                .detectSingleFace(referenceImage)
                .withFaceLandmarks()
                .withFaceDescriptor();

            if (!detection) {
                throw new Error('Tidak ada wajah terdeteksi di foto referensi');
            }

            referenceFaceDescriptor = detection.descriptor;
            console.log('✅ Reference face loaded');
            
            faceStatus.innerHTML = '<span class="spinner"></span><span>Mendeteksi wajah...</span>';
        } catch (error) {
            console.error('❌ Error loading reference face:', error);
            Swal.fire({
                title: "Error!",
                text: "Gagal memuat foto wajah Anda. Pastikan foto wajah sudah tersimpan saat registrasi.",
                icon: "error",
            });
            faceStatus.className = 'face-not-verified';
            faceStatus.innerHTML = '<ion-icon name="close-circle"></ion-icon><span>Foto wajah tidak ditemukan</span>';
            throw error;
        }
    }

    // ===== START FACE DETECTION (OPTIMIZED) =====
    function startFaceDetection() {
        if (!modelsLoaded || !referenceFaceDescriptor) {
            console.log('Waiting for models and reference face...');
            setTimeout(startFaceDetection, 500);
            return;
        }

        // OPTIMASI: Gunakan interval yang lebih efisien (1 detik cukup)
        faceDetectionInterval = setInterval(async () => {
            await detectAndVerifyFace();
        }, 1000); // Ubah dari 500ms ke 1000ms untuk performance

        console.log('✅ Face detection started');
    }

    // ===== DETECT & VERIFY FACE (OPTIMIZED) =====
    async function detectAndVerifyFace() {
        if (!video || video.readyState !== 4 || !referenceFaceDescriptor) return;

        try {
            // OPTIMASI: Gunakan options yang lebih cepat
            const detections = await faceapi
                .detectAllFaces(video, new faceapi.SsdMobilenetv1Options({ 
                    minConfidence: 0.5,
                    maxResults: 2 // Batasi hanya 2 wajah untuk performance
                }))
                .withFaceLandmarks()
                .withFaceDescriptors();

            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            if (detections.length === 0) {
                faceVerified = false;
                faceStatus.className = 'face-detecting';
                faceStatus.innerHTML = '<ion-icon name="scan-outline"></ion-icon><span>Tidak ada wajah</span>';
                faceStatus.style.display = 'flex';
                takeAbsenBtn.disabled = true;
                document.getElementById('button-hint').textContent = 'Posisikan wajah Anda di depan kamera';
            } else if (detections.length > 1) {
                faceVerified = false;
                faceStatus.className = 'face-not-verified';
                faceStatus.innerHTML = '<ion-icon name="warning"></ion-icon><span>Terdeteksi ' + detections.length + ' wajah!</span>';
                faceStatus.style.display = 'flex';
                takeAbsenBtn.disabled = true;
                document.getElementById('button-hint').textContent = 'Hanya 1 orang yang diizinkan';

                detections.forEach(detection => {
                    drawFaceBox(detection.detection.box, '#ff0000');
                });
            } else {
                const detection = detections[0];
                const distance = faceapi.euclideanDistance(referenceFaceDescriptor, detection.descriptor);
                
                console.log('Face distance:', distance, '(Threshold:', SIMILARITY_THRESHOLD, ')');

                if (distance < SIMILARITY_THRESHOLD) {
                    faceVerified = true;
                    const similarity = Math.round((1 - distance) * 100);
                    faceStatus.className = 'face-verified';
                    faceStatus.innerHTML = '<ion-icon name="checkmark-circle"></ion-icon><span>Wajah Cocok! (' + similarity + '%)</span>';
                    faceStatus.style.display = 'flex';
                    
                    if (@if($cek < 2) true @else false @endif) {
                        takeAbsenBtn.disabled = false;
                        document.getElementById('button-hint').textContent = '✅ Klik tombol untuk absen';
                    }

                    drawFaceBox(detection.detection.box, '#00ff00');
                } else {
                    faceVerified = false;
                    const similarity = Math.round((1 - distance) * 100);
                    faceStatus.className = 'face-not-verified';
                    faceStatus.innerHTML = '<ion-icon name="close-circle"></ion-icon><span>Wajah Tidak Cocok! (' + similarity + '%)</span>';
                    faceStatus.style.display = 'flex';
                    takeAbsenBtn.disabled = true;
                    document.getElementById('button-hint').textContent = '❌ Ini bukan wajah Anda!';

                    drawFaceBox(detection.detection.box, '#ff0000');
                }
            }
        } catch (error) {
            console.error('Detection error:', error);
        }
    }

    // ===== DRAW FACE BOX =====
    function drawFaceBox(box, color) {
        const ctx = canvas.getContext('2d');
        ctx.strokeStyle = color;
        ctx.lineWidth = 3;
        ctx.strokeRect(box.x, box.y, box.width, box.height);

        const cornerLength = 20;
        ctx.lineWidth = 5;
        
        // Top-left
        ctx.beginPath();
        ctx.moveTo(box.x, box.y + cornerLength);
        ctx.lineTo(box.x, box.y);
        ctx.lineTo(box.x + cornerLength, box.y);
        ctx.stroke();

        // Top-right
        ctx.beginPath();
        ctx.moveTo(box.x + box.width - cornerLength, box.y);
        ctx.lineTo(box.x + box.width, box.y);
        ctx.lineTo(box.x + box.width, box.y + cornerLength);
        ctx.stroke();

        // Bottom-left
        ctx.beginPath();
        ctx.moveTo(box.x, box.y + box.height - cornerLength);
        ctx.lineTo(box.x, box.y + box.height);
        ctx.lineTo(box.x + cornerLength, box.y + box.height);
        ctx.stroke();

        // Bottom-right
        ctx.beginPath();
        ctx.moveTo(box.x + box.width - cornerLength, box.y + box.height);
        ctx.lineTo(box.x + box.width, box.y + box.height);
        ctx.lineTo(box.x + box.width, box.y + box.height - cornerLength);
        ctx.stroke();
    }

    // ===== SETUP LOCATION (OPTIMIZED) =====
    async function setupLocation() {
        return new Promise((resolve) => {
            var lokasi = document.getElementById('lokasi');
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        successCallback(position);
                        resolve();
                    },
                    (error) => {
                        errorCallback(error);
                        resolve(); // Tetap resolve meskipun error
                    },
                    {
                        enableHighAccuracy: false, // OPTIMASI: Gunakan low accuracy untuk faster response
                        timeout: 10000,
                        maximumAge: 30000 // Cache lokasi selama 30 detik
                    }
                );
            } else {
                resolve();
            }
        });
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

        var jarakDariKantor = hitungJarak(userLatitude, userLongitude, lat_kantor, long_kantor);
        isInRadius = jarakDariKantor <= radius;

        var statusDiv = document.getElementById('location-status');
        statusDiv.style.display = 'block';
        
        if (isInRadius) {
            statusDiv.className = 'status-dalam-kantor';
            statusDiv.innerHTML = '<ion-icon name="checkmark-circle-outline"></ion-icon> Anda di Lingkungan Kantor';
        } else {
            statusDiv.className = 'status-luar-kantor';
            statusDiv.innerHTML = '<ion-icon name="warning-outline"></ion-icon> Anda di Luar Kantor (Jarak: ' + Math.round(jarakDariKantor) + ' meter)';
        }

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

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

        var circle = L.circle([lat_kantor, long_kantor], {
            color: isInRadius ? 'green' : 'red',
            fillColor: isInRadius ? '#5cb85c' : '#f03',
            fillOpacity: 0.3,
            radius: radius
        }).addTo(map);

        userMarker.bindPopup("<b>Lokasi Anda</b><br>Jarak: " + Math.round(jarakDariKantor) + " meter").openPopup();
        kantorMarker.bindPopup("<b>Kantor</b>");

        var group = L.featureGroup([userMarker, kantorMarker, circle]);
        map.fitBounds(group.getBounds().pad(0.1));
    }

    function hitungJarak(lat1, lon1, lat2, lon2) {
        var R = 6371e3;
        var φ1 = lat1 * Math.PI / 180;
        var φ2 = lat2 * Math.PI / 180;
        var Δφ = (lat2 - lat1) * Math.PI / 180;
        var Δλ = (lon2 - lon1) * Math.PI / 180;

        var a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                Math.cos(φ1) * Math.cos(φ2) *
                Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        return R * c;
    }

    function errorCallback(error) {
        console.error('Geolocation error:', error);
        Swal.fire({
            title: "Error!",
            text: "Tidak bisa mendapatkan lokasi! Pastikan GPS aktif.",
            icon: "error",
        });
    }

    // ===== TOMBOL ABSEN =====
    $('#takeabsen').click(function(e) {
        e.preventDefault();

        if (!faceVerified) {
            Swal.fire({
                title: "Peringatan!",
                text: "Wajah Anda tidak cocok dengan data yang tersimpan!",
                icon: "warning",
            });
            return;
        }

        const tempCanvas = document.createElement('canvas');
        tempCanvas.width = video.videoWidth;
        tempCanvas.height = video.videoHeight;
        const tempCtx = tempCanvas.getContext('2d');
        tempCtx.drawImage(video, 0, 0);
        
        const image = tempCanvas.toDataURL('image/jpeg', 0.9);
        const lokasi = $('#lokasi').val();

        if(!lokasi){
            Swal.fire({
                title:"Error!",
                text:"Lokasi tidak terbaca!",
                icon: "error",
            });
            return;
        }

        $.ajax({
            url: '/presensi/store',
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                lokasi: lokasi,
                image: image,
                is_in_radius: isInRadius ? 1 : 0,
                face_verified: faceVerified ? 1 : 0
            },
            cache: false,
            success: function(respond) {
                var status = respond.split("|");

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
                }
            },
            error: function(xhr) {
                Swal.fire({
                    title:"Error!",
                    text:"Terjadi kesalahan",
                    icon: "error",
                });
            }
        });
    });

    // Cleanup
    window.addEventListener('beforeunload', () => {
        if (faceDetectionInterval) {
            clearInterval(faceDetectionInterval);
        }
        if (video && video.srcObject) {
            video.srcObject.getTracks().forEach(track => track.stop());
        }
    });
</script>