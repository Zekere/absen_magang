@extends('layout.admin.template')

@section('content')

<!-- CSS Dependencies -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
  /* Custom Responsive Styles */
  @media (max-width: 768px) {
    .card-laporan {
      margin-bottom: 20px;
    }
    
    .btn-action-group .btn {
      margin-bottom: 10px;
    }
    
    .form-select, .form-control {
      font-size: 14px;
    }
  }
  
  @media (max-width: 576px) {
    .container-fluid {
      padding-left: 15px;
      padding-right: 15px;
    }
    
    h3.fw-bold {
      font-size: 1.5rem;
    }
    
    .card-body {
      padding: 1rem;
    }
  }
  
  /* Animation for card */
  .card-laporan {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  
  .card-laporan:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
  }
  
  /* Custom button styles */
  .btn-cetak {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    transition: all 0.3s ease;
  }
  
  .btn-cetak:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
  }
  
  .btn-excel {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    border: none;
    transition: all 0.3s ease;
  }
  
  .btn-excel:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(17, 153, 142, 0.4);
  }
  
  /* Icon styling */
  .form-label i {
    margin-right: 8px;
    color: #667eea;
  }
  
  /* Select styling */
  .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
  }
</style>

<div class="container-fluid mt-3 mt-md-5">
  <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-3 pb-md-4">
    <div class="col">
      <h3 class="fw-bold mb-2 mb-md-4">
        <i class="bi bi-file-earmark-bar-graph me-2"></i>Laporan Presensi
      </h3>
      <p class="text-muted d-none d-md-block">Cetak atau export laporan presensi karyawan</p>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="container-xl">
    <div class="row justify-content-center">
      <!-- Card Laporan - Responsive pada semua ukuran layar -->
      <div class="col-12 col-md-10 col-lg-8 col-xl-6">
        <div class="card shadow-lg border-0 rounded-3 card-laporan">
          <div class="card-header bg-gradient text-white py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h5 class="mb-0 fw-bold">
              <i class="bi bi-calendar-check me-2"></i>Form Laporan Presensi
            </h5>
          </div>
          
          <div class="card-body p-3 p-md-4">
            <form id="formLaporan" action="/presensi/cetaklaporan" target="_blank" method="POST">
              @csrf
              
              {{-- Pilih Bulan --}}
              <div class="mb-3 mb-md-4">
                <label for="bulan" class="form-label fw-semibold">
                  <i class="bi bi-calendar-month"></i>Bulan
                </label>
                <select name="bulan" id="bulan" class="form-select form-select-lg">
                  <option value="">-- Pilih Bulan --</option>
                  @for ($i=1; $i<=12; $i++)
                    <option value="{{ $i }}" {{ (int)date("m") == $i ? 'selected' : '' }}>
                      {{ $namabulan[$i] }}
                    </option>
                  @endfor
                </select>
              </div>

              {{-- Pilih Tahun --}}
              <div class="mb-3 mb-md-4">
                <label for="tahun" class="form-label fw-semibold">
                  <i class="bi bi-calendar-event"></i>Tahun
                </label>
                <select name="tahun" id="tahun" class="form-select form-select-lg">
                  <option value="">-- Pilih Tahun --</option>
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
              <div class="mb-4">
                <label for="nik" class="form-label fw-semibold">
                  <i class="bi bi-person-badge"></i>Karyawan
                </label>
                <select name="nik" id="nik" class="form-select form-select-lg">
                  <option value="">-- Pilih Karyawan --</option>
                  @foreach($karyawan as $k)
                    <option value="{{ $k->nik }}">{{ $k->nik }} - {{ $k->nama_lengkap }}</option>
                  @endforeach
                </select>
                <div class="form-text">
                  <i class="bi bi-info-circle me-1"></i>Pilih karyawan yang akan dicetak laporannya
                </div>
              </div>

              {{-- Action Buttons - Responsive --}}
              <div class="row g-2 g-md-3 mt-2 btn-action-group">
                <div class="col-12 col-sm-6">
                  <button type="submit" name="cetak" class="btn btn-cetak text-white w-100 py-2 py-md-3">
                    <i class="bi bi-printer-fill me-2"></i>Cetak PDF
                  </button>
                </div>
                <div class="col-12 col-sm-6">
                  <button type="submit" name="exportexcel" class="btn btn-excel text-white w-100 py-2 py-md-3">
                    <i class="bi bi-file-earmark-excel-fill me-2"></i>Export Excel
                  </button>
                </div>
              </div>
            </form>
          </div>
          
          <div class="card-footer bg-light text-center py-3 d-none d-md-block">
            <small class="text-muted">
              <i class="bi bi-shield-check me-1"></i>
              Data laporan akan dibuat berdasarkan filter yang Anda pilih
            </small>
          </div>
        </div>
      </div>
      
      {{-- Info Card - Tampil hanya di desktop --}}
      <div class="col-12 col-lg-4 d-none d-lg-block">
        <div class="card shadow-sm border-0 rounded-3">
          <div class="card-header bg-info text-white">
            <h6 class="mb-0 fw-bold">
              <i class="bi bi-info-circle-fill me-2"></i>Informasi
            </h6>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <h6 class="fw-bold text-primary">
                <i class="bi bi-file-pdf me-2"></i>Format PDF
              </h6>
              <p class="small text-muted mb-0">
                Laporan akan dibuat dalam format PDF yang siap untuk dicetak atau disimpan.
              </p>
            </div>
            
            <hr>
            
            <div class="mb-3">
              <h6 class="fw-bold text-success">
                <i class="bi bi-file-excel me-2"></i>Format Excel
              </h6>
              <p class="small text-muted mb-0">
                Laporan dalam format Excel dapat diedit dan dianalisis lebih lanjut.
              </p>
            </div>
            
            <hr>
            
            <div>
              <h6 class="fw-bold text-warning">
                <i class="bi bi-clock-history me-2"></i>Periode Data
              </h6>
              <p class="small text-muted mb-0">
                Pilih bulan dan tahun untuk menampilkan data presensi pada periode tersebut.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- JS Dependencies -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- ✅ Validasi Form dengan SweetAlert2 -->
<script>
document.getElementById('formLaporan').addEventListener('submit', function(e) {
    const bulan = document.getElementById('bulan').value;
    const tahun = document.getElementById('tahun').value;
    const nik = document.getElementById('nik').value;
    
    // Validasi Bulan
    if (bulan === '') {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Bulan Belum Dipilih',
            text: 'Mohon pilih bulan terlebih dahulu!',
            confirmButtonColor: '#667eea',
            confirmButtonText: 'OK',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        });
        return false;
    }
    
    // Validasi Tahun
    if (tahun === '') {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Tahun Belum Dipilih',
            text: 'Mohon pilih tahun terlebih dahulu!',
            confirmButtonColor: '#667eea',
            confirmButtonText: 'OK',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        });
        return false;
    }
    
    // Validasi Karyawan
    if (nik === '') {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Karyawan Belum Dipilih',
            html: '<p>Mohon pilih karyawan terlebih dahulu sebelum mencetak laporan!</p>',
            confirmButtonColor: '#667eea',
            confirmButtonText: '<i class="bi bi-check-circle me-2"></i>Oke, Saya Pilih Dulu',
            background: '#fefefe',
            color: '#333',
            showClass: {
                popup: 'animate__animated animate__zoomIn'
            },
            hideClass: {
                popup: 'animate__animated animate__zoomOut'
            }
        });
        return false;
    }
    
    // Tampilkan loading saat submit
    Swal.fire({
        title: 'Memproses...',
        html: 'Mohon tunggu, laporan sedang dibuat',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Auto close loading setelah 2 detik
    setTimeout(() => {
        Swal.close();
    }, 2000);
});

// Add tooltip on hover for better UX
document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('.form-select');
    selects.forEach(select => {
        select.addEventListener('focus', function() {
            this.classList.add('shadow-sm');
        });
        select.addEventListener('blur', function() {
            this.classList.remove('shadow-sm');
        });
    });
});
</script>

@endsection