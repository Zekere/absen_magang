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
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    :root {
        --bg: #f0f4ff;
        --surface: #ffffff;
        --surface-2: #e8eeff;
        --border: rgba(99,130,220,0.15);
        --accent: #3b6ff0;
        --accent-light: #667eea;
        --accent-glow: rgba(59,111,240,0.12);
        --success: #16a34a;
        --success-bg: #f0fdf4;
        --success-border: #bbf7d0;
        --warning: #b45309;
        --warning-bg: #fffbeb;
        --warning-border: #fde68a;
        --danger: #dc2626;
        --danger-bg: #fff1f2;
        --danger-border: #fecdd3;
        --text: #1e2a4a;
        --text-muted: #64748b;
        --radius: 16px;
        --radius-sm: 10px;
    }

    * { box-sizing: border-box; }

    body, .wrapper {
        background: var(--bg) !important;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .appHeader {
        background: linear-gradient(135deg, #3b6ff0, #667eea) !important;
        backdrop-filter: blur(20px);
        border-bottom: none;
        box-shadow: 0 2px 20px rgba(59,111,240,0.3) !important;
    }

    .appHeader .pageTitle {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: 16px;
        letter-spacing: 0.3px;
        color: #ffffff !important;
    }

    .container {
        padding-top: 75px;
        padding-bottom: 100px;
        max-width: 520px;
        margin: 0 auto;
    }

    /* ── WEBCAM ── */
    .webcam-wrap {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        background: var(--surface);
        border: 2px solid rgba(59,111,240,0.2);
        box-shadow: 0 8px 40px rgba(59,111,240,0.15), 0 2px 8px rgba(0,0,0,0.08);
        aspect-ratio: 4/3;
    }

    .webcam-wrap video,
    .webcam-wrap canvas {
        display: block;
        width: 100% !important;
        height: 100% !important;
        object-fit: cover;
        border-radius: 20px;
    }

    #face-overlay {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        pointer-events: none;
        z-index: 10;
        border-radius: 20px;
    }

    #face-status {
        position: absolute;
        top: 12px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        align-items: center;
        gap: 7px;
        padding: 8px 16px;
        border-radius: 100px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.2px;
        z-index: 20;
        white-space: nowrap;
        backdrop-filter: blur(12px);
        transition: all 0.3s ease;
        border: 1px solid rgba(255,255,255,0.12);
    }

    .face-loading   { background: rgba(255,255,255,0.92); color: #64748b; border: 1px solid rgba(99,130,220,0.2); }
    .face-detecting { background: rgba(59,111,240,0.1); color: #3b6ff0; border-color: rgba(59,111,240,0.35); }
    .face-verified  { background: rgba(22,163,74,0.1);  color: #16a34a;  border-color: rgba(22,163,74,0.35); }
    .face-not-verified { background: rgba(220,38,38,0.1); color: #dc2626; border-color: rgba(220,38,38,0.35); }

    /* Scan line animation on webcam */
    .webcam-wrap::after {
        content: '';
        position: absolute;
        top: -100%;
        left: 0;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(59,111,240,0.6), transparent);
        animation: scanline 3s ease-in-out infinite;
        z-index: 5;
        opacity: 0.7;
    }

    .face-loading .spinner {
        border-color: rgba(100,116,139,0.25);
        border-top-color: #64748b;
    }

    @keyframes scanline {
        0%   { top: -2px; }
        100% { top: 100%; }
    }

    /* ── JAM KERJA CARD ── */
    .jam-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
        margin: 16px 0;
        box-shadow: 0 4px 20px rgba(59,111,240,0.08);
    }

    .jam-card-header {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 13px 18px;
        background: linear-gradient(135deg, #3b6ff0, #667eea);
        color: white;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .jam-card-header ion-icon {
        font-size: 16px;
        opacity: 0.9;
    }

    .jam-card-body {
        padding: 4px 0;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 11px 18px;
        border-bottom: 1px solid #f1f5ff;
    }

    .info-row:last-of-type { border-bottom: none; }

    .info-row .label {
        color: var(--text-muted);
        font-size: 13px;
        font-weight: 500;
    }

    .info-row .value {
        color: var(--text);
        font-size: 13px;
        font-weight: 700;
        font-variant-numeric: tabular-nums;
    }

    /* ── STATUS BADGE ── */
    .status-badge {
        margin: 14px 18px;
        padding: 10px 16px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        font-weight: 600;
    }

    .status-badge ion-icon { font-size: 18px; flex-shrink: 0; }

    .status-success { background: var(--success-bg); color: var(--success); border: 1px solid var(--success-border); }
    .status-warning { background: var(--warning-bg); color: var(--warning); border: 1px solid var(--warning-border); }
    .status-danger  { background: var(--danger-bg);  color: var(--danger);  border: 1px solid var(--danger-border); }

    /* ── LOCATION STATUS ── */
    #location-status {
        display: none;
        align-items: center;
        gap: 8px;
        padding: 11px 16px;
        border-radius: var(--radius-sm);
        font-size: 13px;
        font-weight: 600;
        margin: 12px 0;
    }

    #location-status.status-dalam-kantor {
        background: var(--success-bg);
        color: var(--success);
        border: 1px solid var(--success-border);
    }

    #location-status.status-luar-kantor {
        background: var(--warning-bg);
        color: var(--warning);
        border: 1px solid var(--warning-border);
    }

    /* ── ABSEN BUTTON ── */
    .absen-btn-wrap {
        margin: 20px 0 16px;
    }

    #takeabsen {
        width: 100%;
        padding: 15px 24px;
        border-radius: var(--radius);
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 15px;
        font-weight: 700;
        letter-spacing: 0.3px;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    #takeabsen.btn-success {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
        box-shadow: 0 4px 20px rgba(34,197,94,0.3);
    }

    #takeabsen.btn-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        box-shadow: 0 4px 20px rgba(239,68,68,0.3);
    }

    #takeabsen.btn-secondary {
        background: #f1f5f9;
        color: var(--text-muted);
        box-shadow: none;
        border: 1px solid var(--border);
    }

    #takeabsen:not(:disabled):hover {
        transform: translateY(-1px);
        filter: brightness(1.1);
    }

    #takeabsen:disabled {
        opacity: 0.45;
        cursor: not-allowed;
        transform: none;
        filter: none;
        box-shadow: none;
    }

    #takeabsen ion-icon {
        font-size: 18px;
    }

    #button-hint {
        text-align: center;
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 8px;
        font-weight: 500;
    }

    .done-badge {
        width: 100%;
        padding: 15px 24px;
        border-radius: var(--radius);
        background: #f8faff;
        color: var(--text-muted);
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: 1px solid var(--border);
    }

    .done-badge ion-icon { color: var(--success); font-size: 18px; }

    /* ── MAP ── */
    #map {
        width: 100%;
        height: 240px;
        border-radius: var(--radius);
        overflow: hidden;
        border: 1px solid var(--border);
        margin-top: 8px;
        box-shadow: 0 4px 20px rgba(59,111,240,0.1);
    }

    /* ── SPINNER ── */
    .spinner {
        display: inline-block;
        width: 15px; height: 15px;
        border: 2px solid rgba(255,255,255,0.25);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 0.7s linear infinite;
        flex-shrink: 0;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    #reference-face { display: none; }

    /* ── DIVIDER ── */
    .section-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 20px 0 8px;
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 480px) {
        .container { padding-top: 68px; padding-bottom: 110px; }
        #face-status { font-size: 11px; padding: 6px 12px; }
        #map { height: 200px; }
    }
</style>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
<script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
@endsection

@section('content')
<div class="container">

    <img id="reference-face" crossorigin="anonymous" 
         src="{{ asset('storage/uploads/faces/' . Auth::guard('karyawan')->user()->nik . '_face.jpg') }}" 
         alt="Reference Face">

    {{-- WEBCAM --}}
    <div class="webcam-wrap">
        <input type="hidden" id="lokasi">
        <video id="webcam" autoplay playsinline></video>
        <canvas id="face-overlay"></canvas>
        <div id="face-status" class="face-loading" style="display:none;">
            <span class="spinner"></span>
            <span>Memuat model...</span>
        </div>
    </div>

    {{-- JAM KERJA --}}
    @if($jamKerja)
    <div class="jam-card">
        <div class="jam-card-header">
            <ion-icon name="time-outline"></ion-icon>
            Informasi Jam Kerja
        </div>
        <div class="jam-card-body">
            <div class="info-row">
                <span class="label">Jam Masuk</span>
                <span class="value">{{ date('H:i', strtotime($jamKerja->jam_masuk)) }}</span>
            </div>
            <div class="info-row">
                <span class="label">Jam Pulang</span>
                <span class="value">{{ date('H:i', strtotime($jamKerja->jam_pulang)) }}</span>
            </div>
            <div class="info-row">
                <span class="label">Absen Masuk</span>
                <span class="value">
                    {{ sprintf("%02d:%02d", floor($jamKerja->batas_absen_masuk_awal / 60), $jamKerja->batas_absen_masuk_awal % 60) }} –
                    {{ sprintf("%02d:%02d", floor($jamKerja->batas_absen_masuk_akhir / 60), $jamKerja->batas_absen_masuk_akhir % 60) }}
                </span>
            </div>
            <div class="info-row">
                <span class="label">Absen Pulang</span>
                <span class="value">Mulai {{ $jamKerja->getWaktuMulaiAbsenPulang() }}</span>
            </div>

            @php
                $jamSekarangMenit = date('H') * 60 + date('i');
                $jamMasukMenit    = (strtotime($jamKerja->jam_masuk)   - strtotime('00:00:00')) / 60;
                $jamPulangMenit   = (strtotime($jamKerja->jam_pulang)  - strtotime('00:00:00')) / 60;
                $batasAbsenPulang = $jamPulangMenit - $jamKerja->batas_absen_pulang_sebelum;

                if ($cek > 0) {
                    if ($jamSekarangMenit >= $batasAbsenPulang) {
                        $statusClass = 'status-success';
                        $statusText  = 'Bisa Absen Pulang';
                        $statusIcon  = 'checkmark-circle';
                    } else {
                        $waktuBoleh  = sprintf("%02d:%02d", floor($batasAbsenPulang/60), $batasAbsenPulang%60);
                        $statusClass = 'status-warning';
                        $statusText  = 'Belum Bisa Absen Pulang — Mulai ' . $waktuBoleh;
                        $statusIcon  = 'time';
                    }
                } else {
                    if ($jamSekarangMenit < $jamKerja->batas_absen_masuk_awal) {
                        $waktuBoleh  = sprintf("%02d:%02d", floor($jamKerja->batas_absen_masuk_awal/60), $jamKerja->batas_absen_masuk_awal%60);
                        $statusClass = 'status-warning';
                        $statusText  = 'Belum Waktunya — Mulai ' . $waktuBoleh;
                        $statusIcon  = 'time';
                    } elseif ($jamSekarangMenit > $jamKerja->batas_absen_masuk_akhir) {
                        $statusClass = 'status-danger';
                        $statusText  = 'Waktu Absen Berakhir';
                        $statusIcon  = 'close-circle';
                    } elseif ($jamSekarangMenit > $jamMasukMenit + $jamKerja->toleransi_keterlambatan) {
                        $statusClass = 'status-danger';
                        $statusText  = 'Terlambat';
                        $statusIcon  = 'warning';
                    } else {
                        $statusClass = 'status-success';
                        $statusText  = 'Tepat Waktu';
                        $statusIcon  = 'checkmark-circle';
                    }
                }
            @endphp

            <div class="status-badge {{ $statusClass }}" style="margin-bottom:6px;">
                <ion-icon name="{{ $statusIcon }}"></ion-icon>
                <span>{{ $statusText }}</span>
            </div>
        </div>
    </div>
    @endif

    {{-- LOCATION STATUS --}}
    <div id="location-status"></div>

    {{-- BUTTON --}}
    <div class="absen-btn-wrap">
        @if($cek >= 2)
            <div class="done-badge">
                <ion-icon name="checkmark-done-circle"></ion-icon>
                Absen Hari Ini Sudah Selesai
            </div>
        @elseif($cek == 1)
            <button id="takeabsen" class="btn btn-danger" disabled>
                <ion-icon name="log-out-outline"></ion-icon>
                Absen Pulang
            </button>
            <p id="button-hint">Tunggu verifikasi wajah...</p>
        @else
            <button id="takeabsen" class="btn btn-success" disabled>
                <ion-icon name="log-in-outline"></ion-icon>
                Absen Masuk
            </button>
            <p id="button-hint">Tunggu verifikasi wajah...</p>
        @endif
    </div>

    {{-- MAP --}}
    <p class="section-label">Lokasi Anda</p>
    <div id="map"></div>

</div>
@endsection

@push('scripts')
<script>
    // ===== GLOBAL VARIABLES =====
    let faceVerified = false;
    let isInRadius = false;
    let userLatitude, userLongitude;
    let video, canvas, faceStatus, takeAbsenBtn, referenceImage, lokasiInput;
    let faceDetectionInterval;
    let modelsLoaded = false;
    let referenceFaceDescriptor = null;

    const SIMILARITY_THRESHOLD = 0.45;
    const userNik = '{{ Auth::guard("karyawan")->user()->nik }}';

    document.addEventListener('DOMContentLoaded', async function() {
        video         = document.getElementById('webcam');
        canvas        = document.getElementById('face-overlay');
        faceStatus    = document.getElementById('face-status');
        takeAbsenBtn  = document.getElementById('takeabsen');
        referenceImage= document.getElementById('reference-face');
        lokasiInput   = document.getElementById('lokasi');

        setupButtonHandler();

        try {
            await Promise.all([setupWebcam(), loadFaceModels(), setupLocation()]);
            await loadReferenceFace();
            startFaceDetection();
        } catch (error) {
            console.error('❌ Initialization error:', error);
        }
    });

    function setupButtonHandler() {
        if (!takeAbsenBtn) return;
        takeAbsenBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (!faceVerified) {
                Swal.fire({ title:"Peringatan!", text:"Wajah Anda tidak cocok dengan data yang tersimpan!", icon:"warning" });
                return;
            }
            const tempCanvas = document.createElement('canvas');
            tempCanvas.width  = video.videoWidth;
            tempCanvas.height = video.videoHeight;
            tempCanvas.getContext('2d').drawImage(video, 0, 0);
            const image  = tempCanvas.toDataURL('image/jpeg', 0.9);
            const lokasi = lokasiInput.value;
            if (!lokasi) {
                Swal.fire({ title:"Error!", text:"Lokasi tidak terbaca!", icon:"error" });
                return;
            }
            takeAbsenBtn.disabled = true;
            const origHTML = takeAbsenBtn.innerHTML;
            takeAbsenBtn.innerHTML = '<span class="spinner"></span> Memproses...';

            fetch('/presensi/store', {
                method:'POST',
                headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
                body: JSON.stringify({ lokasi, image, is_in_radius: isInRadius?1:0, face_verified: faceVerified?1:0 })
            })
            .then(r => r.text())
            .then(respond => {
                var status = respond.split("|");
                if (status[0]==="success") {
                    Swal.fire({ title:"Berhasil!", text:status[1], icon:"success" }).then(() => { window.location.href='/dashboard'; });
                } else {
                    Swal.fire({ title:"Error!", text:status[1], icon:"error" });
                    takeAbsenBtn.disabled = false;
                    takeAbsenBtn.innerHTML = origHTML;
                }
            })
            .catch(() => {
                Swal.fire({ title:"Error!", text:"Terjadi kesalahan saat menyimpan presensi", icon:"error" });
                takeAbsenBtn.disabled = false;
                takeAbsenBtn.innerHTML = origHTML;
            });
        });
    }

    async function setupWebcam() {
        faceStatus.style.display = 'flex';
        faceStatus.className = 'face-loading';
        faceStatus.innerHTML = '<span class="spinner"></span><span>Mengaktifkan kamera...</span>';
        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video:{ width:{ideal:640,max:1280}, height:{ideal:480,max:720}, facingMode:'user', frameRate:{ideal:30,max:30} },
                audio: false
            });
            video.srcObject = stream;
            await video.play();
            canvas.width  = video.videoWidth;
            canvas.height = video.videoHeight;
            faceStatus.innerHTML = '<span class="spinner"></span><span>Memuat model AI...</span>';
        } catch (error) {
            faceStatus.className = 'face-not-verified';
            faceStatus.innerHTML = '<ion-icon name="close-circle"></ion-icon><span>Gagal mengakses kamera</span>';
            Swal.fire({ title:"Error!", text:"Tidak dapat mengakses kamera!", icon:"error" });
            throw error;
        }
    }

    async function loadFaceModels() {
        const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model/';
        await Promise.all([
            faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL),
            faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
            faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
        ]);
        modelsLoaded = true;
    }

    async function loadReferenceFace() {
        faceStatus.innerHTML = '<span class="spinner"></span><span>Memuat foto referensi...</span>';
        if (!referenceImage.complete) {
            await new Promise((res, rej) => {
                referenceImage.onload  = res;
                referenceImage.onerror = () => rej(new Error('Gagal memuat foto referensi'));
                setTimeout(() => rej(new Error('Timeout')), 5000);
            });
        }
        const detection = await faceapi.detectSingleFace(referenceImage).withFaceLandmarks().withFaceDescriptor();
        if (!detection) throw new Error('Tidak ada wajah di foto referensi');
        referenceFaceDescriptor = detection.descriptor;
        faceStatus.innerHTML = '<span class="spinner"></span><span>Mendeteksi wajah...</span>';
    }

    function startFaceDetection() {
        if (!modelsLoaded || !referenceFaceDescriptor) { setTimeout(startFaceDetection, 300); return; }
        faceDetectionInterval = setInterval(async () => { await detectAndVerifyFace(); }, 800);
    }

    async function detectAndVerifyFace() {
        if (!video || video.readyState !== 4 || !referenceFaceDescriptor) return;
        try {
            const detections = await faceapi
                .detectAllFaces(video, new faceapi.SsdMobilenetv1Options({ minConfidence:0.5, maxResults:2 }))
                .withFaceLandmarks().withFaceDescriptors();

            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            const buttonHint = document.getElementById('button-hint');
            const cek = @json($cek);

            if (detections.length === 0) {
                faceVerified = false;
                faceStatus.className = 'face-detecting';
                faceStatus.innerHTML = '<ion-icon name="scan-outline"></ion-icon><span>Tidak ada wajah</span>';
                faceStatus.style.display = 'flex';
                if (cek < 2) { takeAbsenBtn.disabled = true; if(buttonHint) buttonHint.textContent = 'Posisikan wajah di depan kamera'; }
            } else if (detections.length > 1) {
                faceVerified = false;
                faceStatus.className = 'face-not-verified';
                faceStatus.innerHTML = '<ion-icon name="warning"></ion-icon><span>' + detections.length + ' wajah terdeteksi</span>';
                faceStatus.style.display = 'flex';
                if (cek < 2) { takeAbsenBtn.disabled = true; if(buttonHint) buttonHint.textContent = 'Hanya 1 orang diizinkan'; }
                detections.forEach(d => drawFaceBox(d.detection.box, '#ef4444'));
            } else {
                const detection = detections[0];
                const distance  = faceapi.euclideanDistance(referenceFaceDescriptor, detection.descriptor);
                const similarity= Math.round((1 - distance) * 100);
                if (distance < SIMILARITY_THRESHOLD) {
                    faceVerified = true;
                    faceStatus.className = 'face-verified';
                    faceStatus.innerHTML = '<ion-icon name="checkmark-circle"></ion-icon><span>Wajah Cocok — ' + similarity + '%</span>';
                    faceStatus.style.display = 'flex';
                    if (cek < 2) { takeAbsenBtn.disabled = false; if(buttonHint) buttonHint.textContent = '✅ Klik tombol untuk absen'; }
                    drawFaceBox(detection.detection.box, '#22c55e');
                } else {
                    faceVerified = false;
                    faceStatus.className = 'face-not-verified';
                    faceStatus.innerHTML = '<ion-icon name="close-circle"></ion-icon><span>Tidak Cocok — ' + similarity + '%</span>';
                    faceStatus.style.display = 'flex';
                    if (cek < 2) { takeAbsenBtn.disabled = true; if(buttonHint) buttonHint.textContent = '❌ Ini bukan wajah Anda'; }
                    drawFaceBox(detection.detection.box, '#ef4444');
                }
            }
        } catch (error) { /* silent */ }
    }

    function drawFaceBox(box, color) {
        const ctx = canvas.getContext('2d');
        const r   = 8;
        const cl  = 22;

        // Rounded rect
        ctx.strokeStyle = color;
        ctx.lineWidth = 2;
        ctx.beginPath();
        ctx.roundRect(box.x, box.y, box.width, box.height, r);
        ctx.stroke();

        // Corner accents
        ctx.lineWidth = 3.5;
        const drawCorner = (x, y, dx, dy) => {
            ctx.beginPath();
            ctx.moveTo(x + dx * r, y);
            ctx.lineTo(x + dx * cl, y);
            ctx.moveTo(x, y + dy * r);
            ctx.lineTo(x, y + dy * cl);
            ctx.stroke();
        };
        drawCorner(box.x, box.y, 1, 1);
        drawCorner(box.x + box.width, box.y, -1, 1);
        drawCorner(box.x, box.y + box.height, 1, -1);
        drawCorner(box.x + box.width, box.y + box.height, -1, -1);
    }

    async function setupLocation() {
        return new Promise((resolve) => {
            if (!navigator.geolocation) { resolve(); return; }
            navigator.geolocation.getCurrentPosition(
                (pos) => { successCallback(pos); resolve(); },
                (err) => { errorCallback(err);  resolve(); },
                { enableHighAccuracy:false, timeout:10000, maximumAge:30000 }
            );
        });
    }

     function successCallback(position) {
        userLatitude  = position.coords.latitude;
        userLongitude = position.coords.longitude;
        lokasiInput.value = userLatitude + ',' + userLongitude;

        // ⭐ Semua lokasi kantor dari controller
        var semuaLokasi = @json(DB::table('konfigurasi_lokasi')->orderBy('id')->get());

        // ⭐ Cari lokasi terdekat untuk status badge
        var jarakTerdekat   = Infinity;
        var radiusTerdekat  = 0;
        var namaLokTerdekat = '';

        semuaLokasi.forEach(function(lok) {
            var coords = lok.lokasi_kantor.split(',');
            var lat    = parseFloat(coords[0]);
            var lng    = parseFloat(coords[1]);
            var jarak  = hitungJarak(userLatitude, userLongitude, lat, lng);
            if (jarak < jarakTerdekat) {
                jarakTerdekat   = jarak;
                radiusTerdekat  = parseFloat(lok.radius);
                namaLokTerdekat = lok.nama_kantor;
            }
        });

        isInRadius = jarakTerdekat <= radiusTerdekat;

        // Status badge lokasi
        var statusDiv = document.getElementById('location-status');
        statusDiv.style.display = 'flex';
        if (isInRadius) {
            statusDiv.className = 'status-dalam-kantor';
            statusDiv.innerHTML = '<ion-icon name="checkmark-circle-outline"></ion-icon> Anda di ' + namaLokTerdekat;
        } else {
            statusDiv.className = 'status-luar-kantor';
            statusDiv.innerHTML = '<ion-icon name="warning-outline"></ion-icon> Di Luar Kantor — Jarak: ' + Math.round(jarakTerdekat) + ' m dari ' + namaLokTerdekat;
        }

        // Inisialisasi map
        var map = L.map('map').setView([userLatitude, userLongitude], 15);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            maxZoom: 19, attribution: '© OpenStreetMap © CartoDB'
        }).addTo(map);

        var blueIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize:[25,41], iconAnchor:[12,41], popupAnchor:[1,-34], shadowSize:[41,41]
        });
        var redIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize:[25,41], iconAnchor:[12,41], popupAnchor:[1,-34], shadowSize:[41,41]
        });

        // Marker user (biru)
        var userMarker = L.marker([userLatitude, userLongitude], {icon: blueIcon}).addTo(map);
        userMarker.bindPopup('<b>📍 Lokasi Anda</b><br>Jarak ke kantor terdekat: ' + Math.round(jarakTerdekat) + ' m').openPopup();

        // ⭐ Loop semua lokasi kantor — render marker + circle masing-masing
        var allLayers = [userMarker];

        semuaLokasi.forEach(function(lok) {
            var coords    = lok.lokasi_kantor.split(',');
            var latKantor = parseFloat(coords[0]);
            var lngKantor = parseFloat(coords[1]);
            var radius    = parseFloat(lok.radius);
            var jarak     = hitungJarak(userLatitude, userLongitude, latKantor, lngKantor);
            var dalamRadius = jarak <= radius;

            // Warna circle: hijau jika dalam radius, merah jika tidak
            var warna = dalamRadius ? '#22c55e' : '#ef4444';

            var kantorMarker = L.marker([latKantor, lngKantor], {icon: redIcon}).addTo(map);
            kantorMarker.bindPopup(
                '<b>🏢 ' + lok.nama_kantor + '</b>' +
                (lok.id == 1 ? ' ⭐' : '') +
                '<br>Radius: ' + radius + ' m' +
                '<br>Jarak Anda: ' + Math.round(jarak) + ' m' +
                '<br>Status: ' + (dalamRadius ? '✅ Dalam radius' : '❌ Luar radius')
            );

            var circle = L.circle([latKantor, lngKantor], {
                color: warna, fillColor: warna, fillOpacity: 0.12, radius: radius
            }).addTo(map);

            allLayers.push(kantorMarker);
            allLayers.push(circle);
        });

        // Fit bounds semua marker + circle
        map.fitBounds(L.featureGroup(allLayers).getBounds().pad(0.15));
    }

    function hitungJarak(lat1, lon1, lat2, lon2) {
        var R=6371e3, φ1=lat1*Math.PI/180, φ2=lat2*Math.PI/180,
            Δφ=(lat2-lat1)*Math.PI/180, Δλ=(lon2-lon1)*Math.PI/180;
        var a=Math.sin(Δφ/2)**2+Math.cos(φ1)*Math.cos(φ2)*Math.sin(Δλ/2)**2;
        return R*2*Math.atan2(Math.sqrt(a),Math.sqrt(1-a));
    }

    function errorCallback() {
        Swal.fire({ title:"Error!", text:"Tidak bisa mendapatkan lokasi! Pastikan GPS aktif.", icon:"error" });
    }

    window.addEventListener('beforeunload', () => {
        if (faceDetectionInterval) clearInterval(faceDetectionInterval);
        if (video?.srcObject) video.srcObject.getTracks().forEach(t => t.stop());
    });
</script>
@endpush