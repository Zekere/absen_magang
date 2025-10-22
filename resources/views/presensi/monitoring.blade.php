@extends('layout.admin.template')
@section('content')


<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">


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
      <i class="bi bi-eye-fill"></i> Monitoring
    </h3>
        <h5 class="mb-0">Monitoring Presensi Karyawan</h5>

  
  </div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <button class="btn btn-outline-primary" type="button" id="btn-prev">
                                                <i class="bi bi-chevron-left"></i> Previous
                                            </button>
                                            <span class="input-icon-addon input-group-text">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-week">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                                                    <path d="M16 3v4" />
                                                    <path d="M8 3v4" />
                                                    <path d="M4 11h16" />
                                                    <path d="M7 14h.013" />
                                                    <path d="M10.01 14h.005" />
                                                    <path d="M13.01 14h.005" />
                                                    <path d="M16.015 14h.005" />
                                                    <path d="M13.015 17h.005" />
                                                    <path d="M7.01 17h.005" />
                                                    <path d="M10.01 17h.005" /></svg>
                                            </span>
                                            <input type="text" id="tanggal" value="{{  date("Y-m-d")}}" name="tanggal" class="form-control" placeholder="Tanggal Presensi" autocomplete="off">
                                            <button class="btn btn-outline-primary" type="button" id="btn-next">
                                                Next <i class="bi bi-chevron-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    {{-- Menggunakan class 'table-responsive' untuk tampilan mobile yang lebih baik --}}
                                    <div class="table-responsive">
                                        {{-- Menambahkan class 'table-bordered' untuk garis tepi yang lebih jelas --}}
                                        <table class="table table-striped table-hover table-bordered text-center">
                                            <thead>
                                                <tr>
                                                    {{-- Menyesuaikan lebar kolom untuk kerapihan --}}
                                                    <th style="width: 5%;">No</th>
                                                    <th style="width: 10%;">NIK</th>
                                                    <th style="width: 15%;">Nama Karyawan</th>
                                                    <th style="width: 15%;">Departemen</th>
                                                    <th style="width: 8%;">Jam Masuk</th>
                                                    <th style="width: 10%;">Foto Masuk</th>
                                                    <th style="width: 8%;">Jam Pulang</th>
                                                    <th style="width: 10%;">Foto Pulang</th>
                                                    <th style="width: 10%;">Keterangan</th>
                                                    <th style="width: 10%;">Maps</th>
                                                </tr>
                                            </thead>
                                            <tbody id="loadpresensi">
                                                <tr>
                                                    <td colspan="10" class="text-center">
                                                        <div class="spinner-border text-primary" role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-map" tabindex="-1" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow-lg rounded-3">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold"><i class="bi bi-geo-alt-fill me-2"></i> Lokasi Presensi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="loadmap">

      </div>

    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
  $("#tanggal").datepicker({ 
        autoclose: true, 
        todayHighlight: true,
        format:'yyyy-mm-dd'
  });

  function loadpresensi(){
     var tanggal = $("#tanggal").val();
    $.ajax({
        type:'POST',
        url:'/getpresensi',
        data:{
            _token:"{{ csrf_token() }}",
            tanggal: tanggal
        },
        cache:false,
        success:function(respond){
            $("#loadpresensi").html(respond);
        }
    });
  }

  // Event handler untuk perubahan tanggal manual
  $("#tanggal").change(function(e){
   loadpresensi();
  });

  // Event handler untuk tombol Previous
  $("#btn-prev").click(function(e){
    e.preventDefault();
    var currentDate = $("#tanggal").datepicker('getDate');
    currentDate.setDate(currentDate.getDate() - 1);
    $("#tanggal").datepicker('setDate', currentDate);
    loadpresensi();
  });

  // Event handler untuk tombol Next
  $("#btn-next").click(function(e){
    e.preventDefault();
    var currentDate = $("#tanggal").datepicker('getDate');
    currentDate.setDate(currentDate.getDate() + 1);
    $("#tanggal").datepicker('setDate', currentDate);
    loadpresensi();
  });

  // Load data presensi saat halaman pertama kali dibuka
  loadpresensi();
});
</script>
@endpush