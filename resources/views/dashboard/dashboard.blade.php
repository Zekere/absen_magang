@extends('layout.presensi')

@section('content')

<style>

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

/* ===== Responsif untuk HP ===== */
@media (max-width: 576px) {
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


    .logout {
        position: absolute;
        color: white;
        font-size: 35px;
        top: 10px;
        right: 5px;
        z-index: 1000;
        text-decoration: none;
    }

    .logout:hover {
        color: #ff4d4d;
        transition: color 0.3s ease;
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
                popup: 'animate_animated animatezoomIn animate_faster'
            },
            hideClass: {
                popup: 'animate_animated animatezoomOut animate_faster'
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
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    }

    // Fungsi untuk menutup preview foto
    function closeLightbox(event) {
        if (event.target.id === 'lightboxModal' || event.target.className === 'lightbox-close') {
            document.getElementById('lightboxModal').style.display = 'none';
            document.body.style.overflow = 'auto'; // Enable scrolling
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
    <a href="/logout" class="logout" onclick="return confirmLogout(event)">
        <ion-icon name="log-out-outline"></ion-icon>
    </a>

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
    {{-- HADIR --}}
    <div class="rekap-card">
      <div class="rekap-icon-box bg-hadir shadow-sm">
        {{ $rekappresensi->jmlhadir ?? 0 }}
      </div>
      <div class="rekap-label">Hadir</div>
    </div>

    {{-- IZIN --}}
    <div class="rekap-card">
      <div class="rekap-icon-box bg-izin shadow-sm">
        {{ $rekapizin->jmlizin ?? 0 }}
      </div>
      <div class="rekap-label">Izin</div>
    </div>

    {{-- SAKIT --}}
    <div class="rekap-card">
      <div class="rekap-icon-box bg-sakit shadow-sm">
        {{ $rekapizin->jmlsakit ?? 0 }}
      </div>
      <div class="rekap-label">Sakit</div>
    </div>

    {{-- CUTI --}}
    <div class="rekap-card">
      <div class="rekap-icon-box bg-cuti shadow-sm">
        {{ $rekapizin->jmlcuti ?? 0 }}
      </div>
      <div class="rekap-label">Cuti</div>
    </div>

    {{-- TERLAMBAT --}}
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
    <!-- Tab Bulan Ini -->
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

    <!-- Tab Leaderboard -->
    <div class="tab-pane fade" id="profile" role="tabpanel">
        <ul class="listview image-listview">
            @foreach ($leaderboard as $d)
                <li>
                    <div class="item">
                        <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" alt="image" class="image">
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