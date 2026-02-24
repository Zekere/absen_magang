@extends('layout.admin.template')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
  /* Custom styles untuk halaman jam kerja */
 .config-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 25px;
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
  }

  .config-header h3 {
    font-weight: 700;
    margin-bottom: 5px;
    font-size: 1.75rem;
  }

  .config-header h5 {
    font-weight: 400;
    opacity: 0.9;
    font-size: 1rem;
  }

  .config-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    overflow: hidden;
  }

  .config-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
  }

  .card-header-custom {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border: none;
  }

  .card-header-custom h4 {
    margin: 0;
    font-weight: 600;
    font-size: 1.25rem;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .input-group-custom {
    margin-bottom: 20px;
  }

  .input-group-custom .input-group-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    width: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
  }

  .input-group-custom .form-control {
    border: 2px solid #e0e0e0;
    padding: 12px 15px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
  }

  .input-group-custom .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
  }

  .btn-save {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 12px;
    font-weight: 600;
    font-size: 1rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
  }

  .btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
  }

  .info-box {
    background: #f8f9fa;
    border-left: 4px solid #667eea;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
  }

  .info-box i {
    color: #667eea;
    margin-right: 10px;
  }

  .info-box p {
    margin: 0;
    color: #6c757d;
    font-size: 0.9rem;
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    .config-header {
      padding: 20px;
      margin-bottom: 20px;
    }

    .config-header h3 {
      font-size: 1.5rem;
    }

    .config-header h5 {
      font-size: 0.9rem;
    }

    .card-header-custom {
      padding: 15px;
    }

    .card-header-custom h4 {
      font-size: 1.1rem;
    }

    .input-group-custom .input-group-text {
      width: 40px;
      font-size: 1rem;
    }

    .input-group-custom .form-control {
      padding: 10px 12px;
      font-size: 0.9rem;
    }

    .btn-save {
      padding: 10px;
      font-size: 0.95rem;
    }
  }

  @media (max-width: 576px) {
    .config-header h3 {
      font-size: 1.25rem;
    }

    .config-header h5 {
      font-size: 0.85rem;
    }
  }
</style>

<div class="container-fluid mt-4 px-3 px-md-4">
  <!-- Header Section -->
  <div class="config-header">
    <h3 class="mb-1">
      <i class="bi bi-clock-history"></i> Konfigurasi Jam Kerja
    </h3>
    <h5 class="mb-0">Pengaturan Jam Masuk, Pulang & Validasi Waktu Absensi</h5>
  </div>

  <!-- Content Section -->
  <div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-8">
      <div class="config-card">
        <!-- Card Header -->
        <div class="card-header-custom">
          <h4>
            <i class="bi bi-clock-fill"></i>
            Pengaturan Jam Kerja
          </h4>
        </div>

        <!-- Card Body -->
        <div class="card-body p-4">
          <!-- Alert Messages -->
          @if (Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="bi bi-check-circle-fill me-2"></i>
              {{ Session::get('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif
          @if (Session::get('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
              <i class="bi bi-exclamation-triangle-fill me-2"></i>
              {{ Session::get('warning') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif
          @if (Session::get('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="bi bi-x-circle-fill me-2"></i>
              {{ Session::get('error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          <!-- Info Box -->
          <div class="info-box">
            <i class="bi bi-info-circle-fill"></i>
            <strong>Informasi:</strong>
            <p class="mt-1">Atur jam masuk, jam pulang, dan validasi waktu absensi karyawan.</p>
          </div>

          <!-- Form -->
          <form action="{{ url('/jamkerja/update') }}" method="POST" id="jamKerjaForm">
            @csrf
            
            <div class="row">
              <!-- Jam Masuk -->
              <div class="col-md-6">
                <label class="form-label fw-bold">
                  <i class="bi bi-sunrise-fill text-warning"></i> Jam Masuk
                </label>
                <div class="input-group input-group-custom">
                  <span class="input-group-text">
                    <i class="bi bi-clock"></i>
                  </span>
                  <input 
                    type="time" 
                    name="jam_masuk" 
                    class="form-control" 
                    value="{{ $jamKerja ? $jamKerja->jam_masuk : '07:30:00' }}"
                    required
                  >
                </div>
                <small class="text-muted d-block mb-3">
                  Waktu standar masuk kerja
                </small>
              </div>

              <!-- Jam Pulang -->
              <div class="col-md-6">
                <label class="form-label fw-bold">
                  <i class="bi bi-sunset-fill text-danger"></i> Jam Pulang
                </label>
                <div class="input-group input-group-custom">
                  <span class="input-group-text">
                    <i class="bi bi-clock"></i>
                  </span>
                  <input 
                    type="time" 
                    name="jam_pulang" 
                    class="form-control" 
                    value="{{ $jamKerja ? $jamKerja->jam_pulang : '16:00:00' }}"
                    required
                  >
                </div>
                <small class="text-muted d-block mb-3">
                  Waktu standar pulang kerja
                </small>
              </div>
            </div>

            <div class="row">
              <!-- Toleransi Keterlambatan -->
              <div class="col-md-6">
                <label class="form-label fw-bold">
                  <i class="bi bi-hourglass-split text-info"></i> Toleransi Keterlambatan
                </label>
                <div class="input-group input-group-custom">
                  <span class="input-group-text">
                    <i class="bi bi-clock-history"></i>
                  </span>
                  <input 
                    type="number" 
                    name="toleransi_keterlambatan" 
                    class="form-control" 
                    value="{{ $jamKerja ? $jamKerja->toleransi_keterlambatan : 15 }}"
                    min="0"
                    placeholder="15"
                    required
                  >
                  <span class="input-group-text bg-light">menit</span>
                </div>
                <small class="text-muted d-block mb-3">
                  Toleransi waktu terlambat
                </small>
              </div>

              <!-- Batas Absen Pulang Sebelumnya -->
              <div class="col-md-6">
                <label class="form-label fw-bold">
                  <i class="bi bi-alarm text-success"></i> Batas Absen Pulang
                </label>
                <div class="input-group input-group-custom">
                  <span class="input-group-text">
                    <i class="bi bi-stopwatch"></i>
                  </span>
                  <input 
                    type="number" 
                    name="batas_absen_pulang_sebelum" 
                    class="form-control" 
                    value="{{ $jamKerja ? $jamKerja->batas_absen_pulang_sebelum : 60 }}"
                    min="0"
                    placeholder="60"
                    required
                  >
                  <span class="input-group-text bg-light">menit</span>
                </div>
                <small class="text-muted d-block mb-3">
                  Berapa menit sebelum jam pulang boleh absen pulang
                </small>
              </div>
            </div>

            <div class="row">
              <!-- Waktu Mulai Absen Masuk -->
              <div class="col-md-6">
                <label class="form-label fw-bold">
                  <i class="bi bi-arrow-down-circle text-primary"></i> Waktu Mulai Absen
                </label>
                <div class="input-group input-group-custom">
                  <span class="input-group-text">
                    <i class="bi bi-clock"></i>
                  </span>
                  <input 
                    type="time" 
                    name="batas_absen_masuk_awal_display" 
                    id="batas_absen_masuk_awal_display"
                    class="form-control" 
                    value="{{ $jamKerja ? sprintf('%02d:%02d', floor($jamKerja->batas_absen_masuk_awal / 60), $jamKerja->batas_absen_masuk_awal % 60) : '06:00' }}"
                    required
                  >
                  <input 
                    type="hidden" 
                    name="batas_absen_masuk_awal" 
                    id="batas_absen_masuk_awal"
                    value="{{ $jamKerja ? $jamKerja->batas_absen_masuk_awal : 360 }}"
                  >
                </div>
                <small class="text-muted d-block mb-3">
                  Waktu paling awal bisa absen masuk
                </small>
              </div>

              <!-- Waktu Akhir Absen Masuk -->
              <div class="col-md-6">
                <label class="form-label fw-bold">
                  <i class="bi bi-arrow-up-circle text-danger"></i> Waktu Akhir Absen
                </label>
                <div class="input-group input-group-custom">
                  <span class="input-group-text">
                    <i class="bi bi-clock"></i>
                  </span>
                  <input 
                    type="time" 
                    name="batas_absen_masuk_akhir_display"
                    id="batas_absen_masuk_akhir_display"
                    class="form-control" 
                    value="{{ $jamKerja ? sprintf('%02d:%02d', floor($jamKerja->batas_absen_masuk_akhir / 60), $jamKerja->batas_absen_masuk_akhir % 60) : '17:00' }}"
                    required
                  >
                  <input 
                    type="hidden" 
                    name="batas_absen_masuk_akhir"
                    id="batas_absen_masuk_akhir"
                    value="{{ $jamKerja ? $jamKerja->batas_absen_masuk_akhir : 1020 }}"
                  >
                </div>
                <small class="text-muted d-block mb-3">
                  Waktu maksimal bisa absen masuk
                </small>
              </div>
            </div>

            <!-- Preview Info Box -->
            <div class="alert alert-info mt-3" role="alert">
              <i class="bi bi-lightbulb-fill me-2"></i>
              <strong>Contoh Penggunaan:</strong>
              <ul class="mb-0 mt-2">
                <li>Jam Masuk: <strong>07:30</strong>, Jam Pulang: <strong>16:00</strong></li>
                <li>Karyawan bisa absen masuk dari <strong>06:00 - 17:00</strong></li>
                <li>Karyawan bisa absen pulang mulai <strong>15:00</strong> (1 jam sebelum jam pulang)</li>
                <li>Toleransi terlambat: <strong>15 menit</strong> (sampai jam 07:45 masih diterima)</li>
              </ul>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary btn-save w-100 mt-3">
              <i class="bi bi-check-circle-fill me-2"></i>
              Simpan Pengaturan Jam Kerja
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Convert time input ke menit untuk batas absen
  document.getElementById('batas_absen_masuk_awal_display').addEventListener('change', function() {
    const time = this.value.split(':');
    const minutes = parseInt(time[0]) * 60 + parseInt(time[1]);
    document.getElementById('batas_absen_masuk_awal').value = minutes;
  });

  document.getElementById('batas_absen_masuk_akhir_display').addEventListener('change', function() {
    const time = this.value.split(':');
    const minutes = parseInt(time[0]) * 60 + parseInt(time[1]);
    document.getElementById('batas_absen_masuk_akhir').value = minutes;
  });

  // Form validation untuk jam kerja
  document.getElementById('jamKerjaForm').addEventListener('submit', function(e) {
    const jamMasuk = document.querySelector('input[name="jam_masuk"]').value;
    const jamPulang = document.querySelector('input[name="jam_pulang"]').value;
    
    if (jamPulang <= jamMasuk) {
      e.preventDefault();
      alert('Jam pulang harus lebih besar dari jam masuk!');
      return false;
    }
    
    const batasAwal = parseInt(document.getElementById('batas_absen_masuk_awal').value);
    const batasAkhir = parseInt(document.getElementById('batas_absen_masuk_akhir').value);
    
    if (batasAwal >= batasAkhir) {
      e.preventDefault();
      alert('Waktu mulai absen harus lebih awal dari waktu akhir absen!');
      return false;
    }
  });
</script>

@endsection