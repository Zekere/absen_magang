@extends('layout.presensi')

@section('content')

<style>
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
</style>

<!-- Tambahkan SweetAlert2 -->
<!-- Tambahkan SweetAlert2 -->
<!-- Tambahkan SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Tambahkan Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<audio id="clickSound" src="https://assets.mixkit.co/sfx/preview/mixkit-select-click-1109.mp3"></audio>
<audio id="logoutSound" src="https://assets.mixkit.co/sfx/preview/mixkit-door-lock-click-1126.mp3"></audio>

<script>
    function confirmLogout(event) {
        event.preventDefault();

        // Suara klik
        document.getElementById("clickSound").play();

        Swal.fire({
            title: 'Yakin ingin keluar?',
            text: "Anda akan mengakhi sesi ini",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Keluar!',
            cancelButtonText: 'Batal',
            // Efek popup dari tengah
            showClass: {
                popup: 'animate__animated animate__zoomIn animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__zoomOut animate__faster'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Suara logout
                document.getElementById("logoutSound").play();

                setTimeout(() => {
                    window.location.href = "/logout";
                }, 500);
            }
        });

        return false;
    }
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
    <img src="{{ url($path) }}" alt="avatar" class="imaged w64 " style="height:60px">
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
                                <a href="" class="green" style="font-size: 40px;">
                                    <ion-icon name="person-sharp"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                <span class="text-center">Profil</span>
                            </div>
                        </div>
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="" class="danger" style="font-size: 40px;">
                                    <ion-icon name="calendar-number"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                <span class="text-center">Cuti</span>
                            </div>
                        </div>
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="" class="warning" style="font-size: 40px;">
                                    <ion-icon name="document-text"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                <span class="text-center">Histori</span>
                            </div>
                        </div>
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="" class="orange" style="font-size: 40px;">
                                    <ion-icon name="location"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                Lokasi
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
                                      <img src="{{ $path }}" alt="avatar" class="imaged w64 rounded">
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
                                         @if ($presensihariini != null && $presensihariini-> foto_out != null) 
                                       @php
                                       $path = Storage::url('uploads/absensi/'.$presensihariini->foto_out);
                                       @endphp
                                      <img src="{{ $path }}" alt="avatar" class="imaged w64 rounded">
                                      @else
                                      <ion-icon name="camera"></ion-icon>
                                      @endif
                                    </div>
                                    <div class="presencedetail">
                                        <h4 class="presencetitle">Pulang</h4>
                                        <span>{{ $presensihariini != null && $presensihariini-> jam_out != null ? $presensihariini-> jam_out : 'Belum Absen' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div id="rekappresensi">
               <h3>Rekap Presensi Bulan {{ $namabulan [$bulanini] }} tahun {{ $tahunini }}</h3>

             <div class="row">
    <div class="col-3">
        <div class="card h-100 text-center">
            <div class="card-body d-flex flex-column justify-content-center align-items-center p-2" style="position: relative;">
                <span class="badge badge-primary" style="position: absolute; top:5px; right:10px; font-size: 0.7rem;">
                    {{ $rekappresensi->jmlhadir }}
                </span>
                <ion-icon name="accessibility-outline" class="text-primary mb-1" style="font-size: 1.6rem;"></ion-icon>
                <span style="font-size: 0.8rem; font-weight: 500;">Hadir</span>
            </div>
        </div>
    </div>

    <div class="col-3">
        <div class="card h-100 text-center">
            <div class="card-body d-flex flex-column justify-content-center align-items-center p-2" style="position: relative;">
                <span class="badge badge-primary" style="position: absolute; top:5px; right:10px; font-size: 0.7rem;">
                   {{$rekapizin->jmlizin ?? 0 }}
                </span>
                <ion-icon name="newspaper-outline" class="text-success mb-1" style="font-size: 1.6rem;"></ion-icon>
                <span style="font-size: 0.8rem; font-weight: 500;">Izin</span>
            </div>
        </div>
    </div>

    <div class="col-3">
        <div class="card h-100 text-center">
            <div class="card-body d-flex flex-column justify-content-center align-items-center p-2" style="position: relative;">
                <span class="badge badge-primary" style="position: absolute; top:5px; right:10px; font-size: 0.7rem;">
                {{$rekapizin->jmlsakit?? 0 }}
                </span>
                <ion-icon name="medkit-outline" class="text-warning mb-1" style="font-size: 1.6rem;"></ion-icon>
                <span style="font-size: 0.8rem; font-weight: 500;">Sakit</span>
            </div>
        </div>
    </div>

    <div class="col-3">
        <div class="card h-100 text-center">
            <div class="card-body d-flex flex-column justify-content-center align-items-center p-2" style="position: relative;">
                <span class="badge badge-primary" style="position: absolute; top:5px; right:10px; font-size: 0.7rem;">
                    
                    {{ $rekappresensi->jmlterlambat }}
                </span>
                <ion-icon name="alarm-outline" class="text-danger mb-1" style="font-size: 1.6rem;"></ion-icon>
                <span style="font-size: 0.8rem; font-weight: 500;">Terlambat</span>
            </div>
        </div>
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
                            @endphp
  <li>
                                <div class="item">
                                    <div class="icon-box bg-primary">
                                        <img src="{{ $path }}" alt="avatar" class="imaged w64">
                                    </div>
                                    <div class="in">
                                        <div>{{ date ("d-m-Y", strtotime($d->tgl_presensi)) }}</div>
                                        <span class="badge badge-success">{{ $d->jam_in }}</span>
                                            <span class="badge badge-danger">{{ $presensihariini != null && $d->jam_out != null ? $d -> jam_out :'Belum Absen' }}</span>
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
                                    <img src=" {{ asset ('assets/img/sample/avatar/avatar1.jpg')}}" alt="image" class="image">
                                    <div class="in">
                                        <div>
                                        <b>{{ $d->nama_lengkap }}</b><br>
                                            <small class="text-muted">{{ $d->jabatan }}</small>
                                        </div>
                                        <span class="badge {{ $d->jam_in < "07:30" ? "bg-success" : "bg-danger" }}">{{ $d->jam_in }}</span>
                                    </div>
                                </div>
                            </li>
                            @endforeach

                            <li>
                            
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection