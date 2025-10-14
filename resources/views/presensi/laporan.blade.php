@extends('layout.admin.template')

@section('content')

<!-- CSS Dependencies -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<div class="container-fluid mt-5">
  <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div class="col">
      <h3 class="fw-bold mb-4">Laporan</h3>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="container-xl">
    <div class="row">
      <div class="col-6">
        <div class="card shadow-sm">
          <div class="card-body">
            <form id="formLaporan" action="/presensi/cetaklaporan" target="_blank" method="POST">
              @csrf
              
              {{-- Pilih Bulan --}}
              <div class="mb-3">
                <label for="bulan" class="form-label fw-semibold">Bulan</label>
                <select name="bulan" id="bulan" class="form-select">
                  <option value="">Pilih Bulan</option>
                  @for ($i=1; $i<=12; $i++)
                    <option value="{{ $i }}" {{ (int)date("m") == $i ? 'selected' : '' }}>
                      {{ $namabulan[$i] }}
                    </option>
                  @endfor
                </select>
              </div>

              {{-- Pilih Tahun --}}
              <div class="mb-3">
                <label for="tahun" class="form-label fw-semibold">Tahun</label>
                <select name="tahun" id="tahun" class="form-select">
                  <option value="">Pilih Tahun</option>
                  @php 
                    $tahunmulai = 2023; 
                    $tahunsekarang = date('Y');
                  @endphp
                  @for ($tahun = $tahunmulai; $tahun <= $tahunsekarang; $tahun++)
                    <option value="{{ $tahun }}" {{ $tahun == date('Y') ? 'selected' : '' }}>
                      {{ $tahun }}
                    </option>
                  @endfor
                </select>
              </div>

              {{-- Pilih Karyawan --}}
              <div class="mb-3">
                <label for="nik" class="form-label fw-semibold">Karyawan</label>
                <select name="nik" id="nik" class="form-select">
                  <option value="">Pilih Karyawan</option>
                  @foreach($karyawan as $k)
                    <option value="{{ $k->nik }}">{{ $k->nik }} - {{ $k->nama_lengkap }}</option>
                  @endforeach
                </select>
              </div>

              <div class="row mt-3">
                <div class="col-6">
                  <button type="submit" name="cetak" class="btn btn-primary w-100">
                    <i class="bi bi-printer"></i> Cetak
                  </button>
                </div>
                <div class="col-6">
                  <button type="submit" name="exportexcel" class="btn btn-success w-100">
                    <i class="bi bi-download"></i> Export to Excel
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- JS Dependencies -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- ✅ Validasi Pilihan Karyawan dengan SweetAlert -->
<script>
document.getElementById('formLaporan').addEventListener('submit', function(e) {
    const nik = document.getElementById('nik').value;
    if (nik === '') {
        e.preventDefault(); // batalkan submit

        Swal.fire({
            icon: 'warning',
            title: 'Oops...',
            text: 'Mohon pilih karyawan terlebih dahulu sebelum mencetak laporan!',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Oke, Saya Pilih Dulu',
            background: '#fefefe',
            color: '#333',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        });

        return false;
    }
});
</script>

@endsection
