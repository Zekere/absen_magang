@extends('layout.presensi')

@section('content')

<style>
/* ===== Dynamic Header Backgrounds ===== */
#user-section {
    position: relative;
    overflow: hidden;
    transition: background 1s ease;
    min-height: 150px;
}

/* Pagi (05:00 - 10:59) - Langit Biru dengan Awan */
.time-morning {
    background: linear-gradient(180deg, #87CEEB 0%, #B0E0E6 50%, #E0F6FF 100%);
}

/* Siang (11:00 - 14:59) - Langit Terang dengan Matahari */
.time-noon {
    background: linear-gradient(180deg, #4FC3F7 0%, #81D4FA 40%, #B3E5FC 70%, #E1F5FE 100%);
}

/* Sore (15:00 - 17:59) - Senja Orange */
.time-afternoon {
    background: linear-gradient(180deg, #FF6B6B 0%, #FF8E53 30%, #FFB347 60%, #FFDAB9 100%);
}

/* Malam (18:00 - 04:59) - Biru Gelap dengan Bulan */
.time-night {
    background: linear-gradient(180deg, #0F2027 0%, #203A43 50%, #2C5364 100%);
}

/* Cloud Animation */
.clouds {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 1;
    opacity: 0.6;
}

.cloud {
    position: absolute;
    background: rgba(255, 255, 255, 0.7);
    border-radius: 100px;
    opacity: 0.8;
}

.cloud:before,
.cloud:after {
    content: '';
    position: absolute;
    background: rgba(255, 255, 255, 0.7);
    border-radius: 100px;
}

.cloud-1 {
    width: 60px;
    height: 20px;
    top: 20px;
    left: -60px;
    animation: floatCloud 30s linear infinite;
}

.cloud-1:before {
    width: 30px;
    height: 30px;
    top: -15px;
    left: 10px;
}

.cloud-1:after {
    width: 40px;
    height: 35px;
    top: -18px;
    right: 8px;
}

.cloud-2 {
    width: 80px;
    height: 25px;
    top: 60px;
    left: -80px;
    animation: floatCloud 40s linear infinite;
    animation-delay: 5s;
}

.cloud-2:before {
    width: 40px;
    height: 40px;
    top: -20px;
    left: 15px;
}

.cloud-2:after {
    width: 50px;
    height: 45px;
    top: -22px;
    right: 10px;
}

.cloud-3 {
    width: 70px;
    height: 22px;
    top: 100px;
    left: -70px;
    animation: floatCloud 35s linear infinite;
    animation-delay: 10s;
}

.cloud-3:before {
    width: 35px;
    height: 35px;
    top: -17px;
    left: 12px;
}

.cloud-3:after {
    width: 45px;
    height: 40px;
    top: -20px;
    right: 9px;
}

@keyframes floatCloud {
    0% {
        left: -100px;
    }
    100% {
        left: 100%;
    }
}

/* Sun */
.sun {
    position: absolute;
    width: 90px;
    height: 90px;
    background: radial-gradient(circle, #FFF700 0%, #FFD700 40%, #FFA500 100%);
    border-radius: 50%;
    top: 25px;
    right: 35px;
    box-shadow: 
        0 0 20px rgba(255, 215, 0, 0.8),
        0 0 40px rgba(255, 165, 0, 0.6),
        0 0 60px rgba(255, 140, 0, 0.4),
        inset 0 -10px 20px rgba(255, 140, 0, 0.3);
    z-index: 1;
    animation: pulseSun 3s ease-in-out infinite;
}

.sun::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.4) 0%, transparent 70%);
}

@keyframes pulseSun {
    0%, 100% {
        transform: scale(1);
        box-shadow: 
            0 0 20px rgba(255, 215, 0, 0.8),
            0 0 40px rgba(255, 165, 0, 0.6),
            0 0 60px rgba(255, 140, 0, 0.4),
            inset 0 -10px 20px rgba(255, 140, 0, 0.3);
    }
    50% {
        transform: scale(1.08);
        box-shadow: 
            0 0 30px rgba(255, 215, 0, 0.9),
            0 0 60px rgba(255, 165, 0, 0.7),
            0 0 90px rgba(255, 140, 0, 0.5),
            inset 0 -10px 20px rgba(255, 140, 0, 0.4);
    }
}

/* Moon */
.moon {
    position: absolute;
    width: 70px;
    height: 70px;
    background: #F4F4F4;
    border-radius: 50%;
    top: 20px;
    right: 30px;
    box-shadow: 0 0 20px rgba(244, 244, 244, 0.6), 0 0 40px rgba(244, 244, 244, 0.4);
    z-index: 1;
}

.moon:before {
    content: '';
    position: absolute;
    width: 60px;
    height: 60px;
    background: rgba(0, 0, 0, 0.1);
    border-radius: 50%;
    top: 5px;
    left: 15px;
}

/* Stars */
.stars {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 1;
    opacity: 0.7;
}

.star {
    position: absolute;
    width: 2px;
    height: 2px;
    background: white;
    border-radius: 50%;
    animation: twinkle 2s ease-in-out infinite;
}

@keyframes twinkle {
    0%, 100% { opacity: 0.3; }
    50% { opacity: 1; }
}

/* Pastikan konten tetap di atas dengan kontras yang baik */
#user-section #user-detail {
    position: relative;
    z-index: 100 !important;
}

#user-section .avatar {
    position: relative;
    z-index: 100;
}

/* Text color adjustment dengan shadow untuk keterbacaan */
.time-night #user-name,
.time-night #user-role {
    color: white !important;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
}

.time-morning #user-name,
.time-morning #user-role {
    color: #1a1a1a !important;
    text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.5);
}

.time-noon #user-name,
.time-noon #user-role {
    color: #2d2d2d !important;
    text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.3);
}

.time-afternoon #user-name,
.time-afternoon #user-role {
    color: #1a1a1a !important;
    text-shadow: 1px 1px 3px rgba(255, 255, 255, 0.4);
}

/* ===== Rekap Presensi Modern ===== */
.rekap-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 16px;
  padding: 0 10px;
}

.rekap-card {
  width: 90px;
  height: 100px;
  background: #fff;
  border-radius: 16px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.07);
  transition: all 0.25s ease;
  text-align: center;
  flex-shrink: 0;
}

.rekap-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 6px 14px rgba(0, 0, 0, 0.1);
}

.rekap-icon-box {
  width: 42px;
  height: 42px;
  border-radius: 10px;
  display: flex;
  justify-content: center;
  align-items: center;
  color: #fff;
  font-size: 1rem;
  font-weight: 700;
  margin-bottom: 6px;
}

.rekap-label {
  font-size: 0.82rem;
  font-weight: 600;
  color: #333;
}

/* ===== Warna Tiap Kategori ===== */
.bg-hadir {
  background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
}
.bg-izin {
  background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
}
.bg-sakit {
  background: linear-gradient(135deg, #facc15 0%, #eab308 100%);
  color: #000 !important;
}
.bg-cuti {
  background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
}
.bg-terlambat {
  background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

@media (max-width: 576px) {
  .rekap-container {
    flex-wrap: nowrap;
    justify-content: flex-start;
    overflow-x: auto;
    overflow-y: hidden;
    padding: 10px;
    gap: 12px;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    -ms-overflow-style: none;
  }
  
  .rekap-container::-webkit-scrollbar {
    display: none;
  }
  
  .rekap-card {
    width: 75px;
    height: 85px;
  }
  .rekap-icon-box {
    width: 36px;
    height: 36px;
    font-size: 0.9rem;
  }
  .rekap-label {
    font-size: 0.75rem;
  }
}

/* Style untuk preview foto */
.foto-preview {
    cursor: pointer;
    transition: transform 0.2s ease;
}

.foto-preview:hover {
    transform: scale(1.05);
}

/* Modal Lightbox */
.lightbox-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9);
    animation: fadeIn 0.3s;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.lightbox-content {
    position: relative;
    margin: auto;
    padding: 20px;
    width: 90%;
    max-width: 500px;
    top: 50%;
    transform: translateY(-50%);
    animation: zoomIn 0.3s;
}

@keyframes zoomIn {
    from { transform: translateY(-50%) scale(0.5); }
    to { transform: translateY(-50%) scale(1); }
}

.lightbox-content img {
    width: 100%;
    border-radius: 10px;
    box-shadow: 0 5px 30px rgba(0, 0, 0, 0.5);
}

.lightbox-close {
    position: absolute;
    top: -10px;
    right: 10px;
    color: #fff;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
    z-index: 10000;
    transition: color 0.3s;
}

.lightbox-close:hover {
    color: #ff4d4d;
}

.lightbox-info {
    color: #fff;
    text-align: center;
    margin-top: 15px;
    font-size: 16px;
}

.lightbox-info .badge {
    font-size: 14px;
    margin: 5px;
}
</style>

<!-- Tambahkan SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Tambahkan Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<audio id="clickSound" src="https://assets.mixkit.co/sfx/preview/mixkit-select-click-1109.mp3"></audio>
<audio id="logoutSound" src="https://assets.mixkit.co/sfx/preview/mixkit-door-lock-click-1126.mp3"></audio>

<!-- Modal Lightbox untuk Preview Foto -->
<div id="lightboxModal" class="lightbox-modal" onclick="closeLightbox(event)">
    <div class="lightbox-content">
        <span class="lightbox-close" onclick="closeLightbox(event)">&times;</span>
        <img id="lightboxImage" src="" alt="Preview Foto">
        <div class="lightbox-info">
            <div id="lightboxTitle"></div>
            <div id="lightboxTime"></div>
        </div>
    </div>
</div>

<script>
    // Dynamic Header Based on Time
    function updateHeaderBackground() {
        const userSection = document.getElementById('user-section');
        const currentHour = new Date().getHours();
        
        // Remove all time classes
        userSection.classList.remove('time-morning', 'time-noon', 'time-afternoon', 'time-night');
        
        // Remove existing weather elements
        const existingClouds = userSection.querySelector('.clouds');
        const existingSun = userSection.querySelector('.sun');
        const existingMoon = userSection.querySelector('.moon');
        const existingStars = userSection.querySelector('.stars');
        
        if (existingClouds) existingClouds.remove();
        if (existingSun) existingSun.remove();
        if (existingMoon) existingMoon.remove();
        if (existingStars) existingStars.remove();
        
        // Pagi (05:00 - 10:59)
        if (currentHour >= 5 && currentHour < 11) {
            userSection.classList.add('time-morning');
            
            // Add clouds
            const clouds = document.createElement('div');
            clouds.className = 'clouds';
            clouds.innerHTML = `
                <div class="cloud cloud-1"></div>
                <div class="cloud cloud-2"></div>
                <div class="cloud cloud-3"></div>
            `;
            userSection.insertBefore(clouds, userSection.firstChild);
        }
        // Siang (11:00 - 14:59)
        else if (currentHour >= 11 && currentHour < 15) {
            userSection.classList.add('time-noon');
            
            // Add sun
            const sun = document.createElement('div');
            sun.className = 'sun';
            userSection.insertBefore(sun, userSection.firstChild);
        }
        // Sore (15:00 - 17:59)
        else if (currentHour >= 15 && currentHour < 18) {
            userSection.classList.add('time-afternoon');
            
            // Add clouds with sunset
            const clouds = document.createElement('div');
            clouds.className = 'clouds';
            clouds.innerHTML = `
                <div class="cloud cloud-1"></div>
                <div class="cloud cloud-2"></div>
            `;
            userSection.insertBefore(clouds, userSection.firstChild);
        }
        // Malam (18:00 - 04:59)
        else {
            userSection.classList.add('time-night');
            
            // Add moon
            const moon = document.createElement('div');
            moon.className = 'moon';
            userSection.insertBefore(moon, userSection.firstChild);
            
            // Add stars
            const stars = document.createElement('div');
            stars.className = 'stars';
            
            // Generate random stars
            for (let i = 0; i < 50; i++) {
                const star = document.createElement('div');
                star.className = 'star';
                star.style.left = Math.random() * 100 + '%';
                star.style.top = Math.random() * 100 + '%';
                star.style.animationDelay = Math.random() * 2 + 's';
                stars.appendChild(star);
            }
            
            userSection.insertBefore(stars, userSection.firstChild);
        }
    }
    
    // Run on page load
    document.addEventListener('DOMContentLoaded', updateHeaderBackground);
    
    // Update every minute
    setInterval(updateHeaderBackground, 60000);

    function confirmLogout(event) {
        event.preventDefault();

        // Suara klik
        document.getElementById("clickSound").play();

        Swal.fire({
            title: 'Yakin ingin keluar?',
            text: "Anda akan mengakhiri sesi ini",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Keluar!',
            cancelButtonText: 'Batal',
            showClass: {
                popup: 'animate__animated animate__zoomIn animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__zoomOut animate__faster'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("logoutSound").play();

                setTimeout(() => {
                    window.location.href = "/logout";
                }, 500);
            }
        });

        return false;
    }

    // Fungsi untuk membuka preview foto
    function openLightbox(imageSrc, title, time) {
        document.getElementById('lightboxImage').src = imageSrc;
        document.getElementById('lightboxTitle').innerHTML = '<strong>' + title + '</strong>';
        document.getElementById('lightboxTime').innerHTML = '<span class="badge badge-info">' + time + '</span>';
        document.getElementById('lightboxModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    // Fungsi untuk menutup preview foto
    function closeLightbox(event) {
        if (event.target.id === 'lightboxModal' || event.target.className === 'lightbox-close') {
            document.getElementById('lightboxModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }

    // Tutup dengan tombol ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            document.getElementById('lightboxModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
</script>

<div class="section" id="user-section">
    <div id="user-detail">
        <div class="avatar">
            @if(!empty(Auth::guard('karyawan')->user()->foto))
                @php
                    $path = Storage::url('uploads/karyawan/' . Auth::guard('karyawan')->user()->foto);
                @endphp
                <img src="{{ url($path) }}" alt="avatar" class="imaged w64" style="height:60px">
            @else
                <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" alt="avatar" class="imaged w64 rounded" style="height:60px">
            @endif
        </div>
        <div id="user-info">
            <h2 id="user-name">{{ Auth::guard('karyawan')->user()->nama_lengkap }}</h2>
            <span id="user-role">{{ Auth::guard('karyawan')->user()->jabatan }}</span>
        </div>
    </div>
</div>

<div class="section" id="menu-section">
    <div class="card">
        <div class="card-body text-center">
            <div class="list-menu">
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="editprofile" class="primary" style="font-size: 40px;">
                            <ion-icon name="person-sharp"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        <span class="text-center">Profil</span>
                    </div>
                </div>
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="/presensi/izin" class="green" style="font-size: 40px;">
                            <ion-icon name="calendar-number"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        <span class="text-center">Cuti</span>
                    </div>
                </div>
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="/presensi/histori" class="warning" style="font-size: 40px;">
                            <ion-icon name="document-text"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        <span class="text-center">Histori</span>
                    </div>
                </div>
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="/presensi/create" class="orange" style="font-size: 40px;">
                            <ion-icon name="camera"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        Kamera
                    </div>
                </div>
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="/logout" class="danger" style="font-size: 40px;" onclick="return confirmLogout(event)">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        <span class="text-center">Keluar</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section mt-2" id="presence-section">
    <div class="todaypresence">
        <div class="row">
            <div class="col-6">
                <div class="card gradasigreen">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence">
                                @if ($presensihariini != null)
                                    @php
                                        $path = Storage::url('uploads/absensi/'.$presensihariini->foto_in);
                                    @endphp
                                    <img src="{{ $path }}" 
                                         alt="avatar" 
                                         class="imaged w64 rounded foto-preview" 
                                         onclick="openLightbox('{{ $path }}', 'Foto Masuk', '{{ $presensihariini->jam_in }}')">
                                @else
                                    <ion-icon name="camera"></ion-icon>
                                @endif
                            </div>
                            <div class="presencedetail">
                                <h4 class="presencetitle">Masuk</h4>
                                <span>{{ $presensihariini != null ? $presensihariini->jam_in : 'Belum Absen'}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card gradasired">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence">
                                @if ($presensihariini != null && $presensihariini->foto_out != null)
                                    @php
                                        $path = Storage::url('uploads/absensi/'.$presensihariini->foto_out);
                                    @endphp
                                    <img src="{{ $path }}" 
                                         alt="avatar" 
                                         class="imaged w64 rounded foto-preview" 
                                         onclick="openLightbox('{{ $path }}', 'Foto Pulang', '{{ $presensihariini->jam_out }}')">
                                @else
                                    <ion-icon name="camera"></ion-icon>
                                @endif
                            </div>
                            <div class="presencedetail">
                                <h4 class="presencetitle">Pulang</h4>
                                <span>{{ $presensihariini != null && $presensihariini->jam_out != null ? $presensihariini->jam_out : 'Belum Absen' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="rekappresensi" class="mt-4">
        <h5 class="text-center fw-semibold mb-4">
            Rekap Presensi Bulan {{ $namabulan[$bulanini] }} {{ $tahunini }}
        </h5>

        <div class="rekap-container">
            <div class="rekap-card">
                <div class="rekap-icon-box bg-hadir shadow-sm">
                    {{ $rekappresensi->jmlhadir ?? 0 }}
                </div>
                <div class="rekap-label">Hadir</div>
            </div>

            <div class="rekap-card">
                <div class="rekap-icon-box bg-izin shadow-sm">
                    {{ $rekapizin->jmlizin ?? 0 }}
                </div>
                <div class="rekap-label">Izin</div>
            </div>

            <div class="rekap-card">
                <div class="rekap-icon-box bg-sakit shadow-sm">
                    {{ $rekapizin->jmlsakit ?? 0 }}
                </div>
                <div class="rekap-label">Sakit</div>
            </div>

            <div class="rekap-card">
                <div class="rekap-icon-box bg-cuti shadow-sm">
                    {{ $rekapizin->jmlcuti ?? 0 }}
                </div>
                <div class="rekap-label">Cuti</div>
            </div>

            <div class="rekap-card">
                <div class="rekap-icon-box bg-terlambat shadow-sm">
                    {{ $rekappresensi->jmlterlambat ?? 0 }}
                </div>
                <div class="rekap-label">Terlambat</div>
            </div>
        </div>
    </div>

    <div class="presencetab mt-2">
        <div class="tab-pane fade show active" id="pilled" role="tabpanel">
            <ul class="nav nav-tabs style1" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                        Bulan Ini
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
                        Leaderboard
                    </a>
                </li>
            </ul>
        </div>
        <div class="tab-content mt-2" style="margin-bottom:100px;">
            <div class="tab-pane fade show active" id="home" role="tabpanel">
                <ul class="listview image-listview">
                    @foreach ($histroribulanini as $d)
                        @php 
                            $path = Storage::url('uploads/absensi/'.$d->foto_in);
                            $tanggal = date('d-m-Y', strtotime($d->tgl_presensi));
                        @endphp
                        <li>
                            <div class="item">
                                <div class="icon-box bg-primary">
                                    <img src="{{ $path }}" 
                                         alt="avatar" 
                                         class="imaged w64 foto-preview" 
                                         onclick="openLightbox('{{ $path }}', 'Absen {{ $tanggal }}', '{{ $d->jam_in }}')">
                                </div>
                                <div class="in">
                                    <div>{{ $tanggal }}</div>
                                    <span class="badge badge-success">{{ $d->jam_in }}</span>
                                    <span class="badge badge-danger">{{ $d->jam_out != null ? $d->jam_out : 'Belum Absen' }}</span>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="tab-pane fade" id="profile" role="tabpanel">
                <ul class="listview image-listview">
                    @foreach ($leaderboard as $d)
                        <li>
                            <div class="item">
                                @if(!empty($d->foto))
                                    @php
                                        $fotoPath = Storage::url('uploads/karyawan/' . $d->foto);
                                    @endphp
                                    <img src="{{ url($fotoPath) }}" alt="image" class="image">
                                @else
                                    <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" alt="image" class="image">
                                @endif
                                <div class="in">
                                    <div>
                                        <b>{{ $d->nama_lengkap }}</b><br>
                                        <small class="text-muted">{{ $d->jabatan }}</small>
                                    </div>
                                    <span class="badge {{ $d->jam_in < '07:30' ? 'bg-success' : 'bg-danger' }}">{{ $d->jam_in }}</span>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection