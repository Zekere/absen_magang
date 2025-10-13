@extends('layout.admin.template')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<div class="container-fluid">
  
  <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
      <div class ="col">
        <h3 class="fw-bold mb-4">Dashboard</h3>
      </div>
    </div>
  </div>
   
</div>

<div class="page-body">
  <div class="row row-cols-1 row-cols-md-3 row-cols-lg-5 g-3">
    
    <!-- Card 1 -->
    <div class="col">
      <div class="card card-sm">
        <div class="card-body text-center">
          <span class="bg-success text-white avatar d-flex align-items-center justify-content-center mx-auto">
            <!-- SVG -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round"
                 class="icon icon-tabler icon-tabler-fingerprint">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M18.9 7a8 8 0 0 1 1.1 5v1a6 6 0 0 0 .8 3" />
              <path d="M8 11a4 4 0 0 1 8 0v1a10 10 0 0 0 2 6" />
              <path d="M12 11v2a14 14 0 0 0 2.5 8" />
              <path d="M8 15a18 18 0 0 0 1.8 6" />
              <path d="M4.9 19a22 22 0 0 1 -.9 -7v-1a8 8 0 0 1 12 -6.95" />
            </svg>
          </span>
          <div class="font-weight-medium mt-2">{{ $jmlkaryawan }}</div>
          <div class="text-secondary">Jumlah Karyawan</div>
        </div>
      </div>
    </div>

    <!-- Card 2 -->
    <div class="col">
      <div class="card card-sm">
        <div class="card-body text-center">
          <span class="bg-primary text-white avatar d-flex align-items-center justify-content-center mx-auto">
            <!-- SVG -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round"
                 class="icon icon-tabler icon-tabler-users">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
              <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
              <path d="M16 3.13a4 4 0 0 1 0 7.75" />
              <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
            </svg>
          </span>
          <div class="font-weight-medium mt-2">{{ $rekappresensi -> jmlhadir ?? 0  }}</div>
          <div class="text-secondary">Karyawan Hadir</div>
        </div>
      </div>
    </div>

    <!-- Card 3 -->
    <div class="col">
      <div class="card card-sm">
        <div class="card-body text-center">
          <span class="bg-warning text-white avatar d-flex align-items-center justify-content-center mx-auto">
            <!-- Icon izin -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round"
                 class="icon icon-tabler icon-tabler-file-text">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M14 3v4a1 1 0 0 0 1 1h4" />
              <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
              <path d="M9 9l1 0" /><path d="M9 13l6 0" /><path d="M9 17l6 0" />
            </svg>
          </span>
          <div class="font-weight-medium mt-2">{{ $rekapizin->jmlizin ?? 0 }}</div>
          <div class="text-secondary">Karyawan Izin</div>
        </div>
      </div>
    </div>

    <!-- Card 4 -->
    <div class="col">
      <div class="card card-sm">
        <div class="card-body text-center">
          <span class="bg-danger text-white avatar d-flex align-items-center justify-content-center mx-auto">
            <!-- Icon sakit -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round"
                 class="icon icon-tabler icon-tabler-mood-sick">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M12 21a9 9 0 1 1 0 -18a9 9 0 0 1 0 18z" />
              <path d="M9 10h-.01" /><path d="M15 10h-.01" />
              <path d="M8 16l1 -1l1.5 1l1.5 -1l1.5 1l1.5 -1l1 1" />
            </svg>
          </span>
          <div class="font-weight-medium mt-2">{{ $rekapizin->jmlsakit ?? 0 }}</div>
          <div class="text-secondary">Karyawan Sakit</div>
        </div>
      </div>
    </div>

    <!-- Card 5 -->
    <div class="col">
      <div class="card card-sm">
        <div class="card-body text-center">
          <span class="bg-dark text-white avatar d-flex align-items-center justify-content-center mx-auto">
            <!-- Icon alpha -->
           <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-alarm"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 13m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M12 10l0 3l2 0" /><path d="M7 4l-2.75 2" /><path d="M17 4l2.75 2" /></svg>
          </span>
          <div class="font-weight-medium mt-2">{{ $rekappresensi -> jmlhadir ?? 0}}</div>
          <div class="text-secondary">Karyawan Terlambat</div>
        </div>
      </div>
    </div>

  </div>
</div>



@endsection
