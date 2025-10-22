@extends('layout.admin.template')
@section('content')


<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">


<div class="container-fluid mt-5">

  <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div class="col">
      <h3 class="fw-bold mb-4">Monitoring Presensi</h3>
    </div>
  </div>

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