@extends('layout.admin.template')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
  /* Custom styles untuk halaman konfigurasi */
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
      <i class="bi bi-gear-fill"></i> Konfigurasi
    </h3>
    <h5 class="mb-0">Pengaturan Lokasi Kantor & Radius Presensi</h5>
  </div>

  <!-- Content Section -->
  <div class="row justify-content-center">
    <div class="col-12 col-lg-8 col-xl-6">
      <div class="config-card">
        <!-- Card Header -->
        <div class="card-header-custom">
          <h4>
            <i class="bi bi-pin-map-fill"></i>
            Lokasi Kantor
          </h4>
        </div>

        <!-- Card Body -->
        <div class="card-body p-4">
          <!-- Info Box -->
          <div class="info-box">
            <i class="bi bi-info-circle-fill"></i>
            <strong>Informasi:</strong>
            <p class="mt-1">Atur lokasi kantor dan radius jangkauan untuk sistem presensi karyawan.</p>
          </div>

          <!-- Form -->
          <form action="{{ url('/konfigurasi/updatelokasikantor') }}" method="POST" id="configForm">
            @csrf
            
            <!-- Lokasi Kantor Input -->
            <div class="input-group input-group-custom">
              <span class="input-group-text">
                <i class="bi bi-geo-alt-fill"></i>
              </span>
              <input 
                type="text" 
                name="lokasi_kantor" 
                class="form-control" 
                placeholder="Contoh: -7.250445, 112.768845"
                value="{{ $lok_kantor->lokasi_kantor ?? '' }}"
                required
              >
            </div>
            <small class="text-muted d-block mb-3">
              <i class="bi bi-lightbulb"></i> Format: Latitude, Longitude (gunakan Google Maps untuk mendapatkan koordinat)
            </small>

            <!-- Radius Input -->
            <div class="input-group input-group-custom">
              <span class="input-group-text">
                <i class="bi bi-bullseye"></i>
              </span>
              <input 
                type="number" 
                name="radius" 
                class="form-control" 
                placeholder="Masukkan radius dalam meter"
                value="{{ $lok_kantor->radius ?? '' }}"
                min="1"
                required
              >
            </div>
            <small class="text-muted d-block mb-4">
              <i class="bi bi-lightbulb"></i> Radius dalam meter (contoh: 100 untuk 100 meter)
            </small>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary btn-save w-100">
              <i class="bi bi-check-circle-fill me-2"></i>
              Simpan Konfigurasi
            </button>
          </form>
        </div>
      </div>

      <!-- Additional Info Card (Optional) -->
      <div class="config-card mt-4">
        <div class="card-body p-4">
          <h5 class="mb-3">
            <i class="bi bi-question-circle-fill text-primary"></i>
            Cara Menggunakan
          </h5>
          <ol class="mb-0">
            <li class="mb-2">Buka Google Maps dan cari lokasi kantor Anda</li>
            <li class="mb-2">Klik kanan pada lokasi, pilih koordinat untuk menyalin</li>
            <li class="mb-2">Paste koordinat ke field "Lokasi Kantor"</li>
            <li class="mb-2">Tentukan radius jangkauan dalam meter</li>
            <li>Klik "Simpan Konfigurasi"</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Form validation dan feedback
  document.getElementById('configForm').addEventListener('submit', function(e) {
    const lokasiInput = document.querySelector('input[name="lokasi_kantor"]');
    const radiusInput = document.querySelector('input[name="radius"]');
    
    // Validasi format koordinat (sederhana)
    const koordinatPattern = /^-?\d+\.?\d*,\s*-?\d+\.?\d*$/;
    if (!koordinatPattern.test(lokasiInput.value.trim())) {
      e.preventDefault();
      alert('Format lokasi tidak valid! Gunakan format: Latitude, Longitude\nContoh: -7.250445, 112.768845');
      lokasiInput.focus();
      return false;
    }
    
    // Validasi radius
    if (radiusInput.value <= 0) {
      e.preventDefault();
      alert('Radius harus lebih dari 0 meter!');
      radiusInput.focus();
      return false;
    }
  });
</script>

@endsection