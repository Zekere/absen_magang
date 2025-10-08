@extends('layout.presensi')

@section('header')
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Histori Presensi</div>
    <div class="right"></div>
</div>
@endsection

@section('content')
<div class="container-fluid" style="margin-top: 70px; margin-bottom: 100px; padding-bottom: 20px;">
    
    <!-- Real-time Clock Card -->
    <div class="card border-0 shadow-sm mb-4 clock-card">
        <div class="card-body text-center py-4">
            <div class="clock-icon mb-3">
                <ion-icon name="time-outline"></ion-icon>
            </div>
            <h4 id="today-date" class="fw-bold mb-2 date-text"></h4>
            <h3 id="today-time" class="time-text mb-0"></h3>
            <small class="text-muted mt-2 d-block">Waktu Real-time</small>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card border-0 shadow-sm mb-4 filter-card">
        <div class="card-header bg-white border-0 py-3">
            <h6 class="fw-bold mb-0 d-flex align-items-center">
                <ion-icon name="filter-outline" class="me-2" style="font-size: 20px;"></ion-icon>
                Filter Periode
            </h6>
        </div>
        <div class="card-body px-4 py-4">
            <div class="row g-3">
                
                <!-- Filter Bulan -->
                <div class="col-12 mb-2">
                    <label class="form-label small fw-semibold text-dark mb-2 d-flex align-items-center">
                        <ion-icon name="calendar-outline" class="me-2" style="font-size: 18px;"></ion-icon>
                        Pilih Bulan
                    </label>
                    <div class="input-group input-group-custom">
                        <span class="input-group-text bg-light border-end-0">
                            <ion-icon name="calendar" style="font-size: 20px;"></ion-icon>
                        </span>
                        <select name="bulan" id="bulan" class="form-select border-start-0 select-custom">
                            <option value="">-- Pilih Bulan --</option>
                            @for ($i=1; $i<=12; $i++)
                            <option value="{{ $i }}" {{ (int)date("m") == $i ? 'selected' : '' }}>
                                {{ $namabulan[$i] }}
                            </option>
                            @endfor
                        </select>
                    </div>
                </div>

                <!-- Filter Tahun -->
                <div class="col-12 mb-2">
                    <label class="form-label small fw-semibold text-dark mb-2 d-flex align-items-center">
                        <ion-icon name="newspaper-outline" class="me-2" style="font-size: 18px;"></ion-icon>
                        Pilih Tahun
                    </label>
                    <div class="input-group input-group-custom">
                        <span class="input-group-text bg-light border-end-0">
                            <ion-icon name="newspaper" style="font-size: 20px;"></ion-icon>
                        </span>
                        <select name="tahun" id="tahun" class="form-select border-start-0 select-custom">
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
                </div>

                <!-- Tombol Search -->
                <div class="col-12 mt-3">
                    <button class="btn btn-primary w-100 py-3 fw-semibold btn-search" id="get">
                        <ion-icon name="search-outline" class="me-2" style="font-size: 20px;"></ion-icon>
                        Tampilkan Histori
                    </button>
                </div>

                <!-- Tombol Reset -->
                <div class="col-12 mt-2">
                    <button class="btn btn-outline-secondary w-100 py-2 btn-reset" id="reset">
                        <ion-icon name="refresh-outline" class="me-2" style="font-size: 18px;"></ion-icon>
                        Reset Filter
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div class="text-center py-5 d-none" id="loading">
        <div class="spinner-border text-primary mb-3" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="text-muted">Memuat data histori...</p>
    </div>

    <!-- Histori Results -->
    <div id="showhistori"></div>

</div>

<style>
/* Clock Card Styling */
.clock-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 20px;
    overflow: hidden;
    position: relative;
}

.clock-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: pulse 4s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 0.5; }
    50% { transform: scale(1.1); opacity: 0.8; }
}

.clock-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 70px;
    height: 70px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    backdrop-filter: blur(10px);
}

.clock-icon ion-icon {
    font-size: 40px;
    color: white;
}

.date-text {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.95);
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.time-text {
    font-size: 2.5rem;
    font-weight: 700;
    color: white;
    text-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    letter-spacing: 2px;
    font-family: 'Courier New', monospace;
}

/* Filter Card */
.card {
    border-radius: 16px;
}

.filter-card .card-body {
    padding: 1.5rem 1.25rem;
}

.card-header {
    padding: 1.25rem;
}

.form-label {
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.input-group-custom {
    margin-bottom: 0.5rem;
}

.input-group-text {
    border-radius: 12px 0 0 12px;
    color: #6b7280;
    padding: 0.75rem 1rem;
    background-color: #f9fafb;
    border: 1px solid #e5e7eb;
}

.select-custom {
    border-radius: 0 12px 12px 0;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
    height: 48px;
}

.select-custom:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
}

/* Spacing between elements */
.col-12.mb-2 {
    margin-bottom: 1rem !important;
}

.col-12.mt-3 {
    margin-top: 1.5rem !important;
}

.col-12.mt-2 {
    margin-top: 0.75rem !important;
}

/* Button Styling */
.btn-search {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-search:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.btn-search:active {
    transform: translateY(0);
}

.btn-reset {
    border-radius: 12px;
    border: 2px solid #e5e7eb;
    transition: all 0.3s ease;
}

.btn-reset:hover {
    background: #f3f4f6;
    border-color: #d1d5db;
    transform: translateY(-1px);
}

/* Loading Spinner */
.spinner-border {
    width: 3rem;
    height: 3rem;
}

/* Responsive */
@media (max-width: 576px) {
    .time-text {
        font-size: 2rem;
    }
    
    .date-text {
        font-size: 1rem;
    }
    
    .clock-icon {
        width: 60px;
        height: 60px;
    }
    
    .clock-icon ion-icon {
        font-size: 32px;
    }
}

/* Animation untuk hasil histori */
#showhistori {
    animation: fadeInUp 0.5s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

@endsection

@push('scripts')
<script>
$(function(){
    
    // AJAX Get Histori
    $("#get").click(function(e){
        e.preventDefault();
        
        var bulan = $("#bulan").val();
        var tahun = $("#tahun").val();
        
        // Validasi
        if(!bulan || !tahun) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: 'Silakan pilih bulan dan tahun terlebih dahulu.',
                confirmButtonColor: '#667eea'
            });
            return;
        }
        
        // Show loading
        $("#loading").removeClass('d-none');
        $("#showhistori").html('');
        
        $.ajax({
            type: 'POST',
            url: '/gethistori',
            data: {
                _token: "{{ csrf_token() }}",
                bulan: bulan,
                tahun: tahun 
            },
            cache: false,
            success: function(respond){
                $("#loading").addClass('d-none');
                $("#showhistori").html(respond);
            },
            error: function(){
                $("#loading").addClass('d-none');
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Gagal memuat data histori. Silakan coba lagi.',
                    confirmButtonColor: '#667eea'
                });
            }
        });
    });
    
    // Reset Filter
    $("#reset").click(function(e){
        e.preventDefault();
        $("#bulan").val('');
        $("#tahun").val('{{ date("Y") }}');
        $("#showhistori").html('');
    });

    // Real-time Clock dengan format Indonesia
    function updateDateTime(){
        const now = new Date();
        
        // Format tanggal
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric'
        };
        const dateStr = now.toLocaleDateString('id-ID', options);
        
        // Format waktu dengan detik
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const timeStr = `${hours}:${minutes}:${seconds}`;
        
        $("#today-date").text(dateStr);
        $("#today-time").text(timeStr);
    }

    // Update setiap detik
    setInterval(updateDateTime, 1000);
    updateDateTime();
    
    // Auto load data bulan dan tahun sekarang saat halaman dibuka
    setTimeout(function(){
        $("#get").click();
    }, 500);
});
</script>
@endpush