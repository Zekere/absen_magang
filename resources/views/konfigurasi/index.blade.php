@extends('layout.admin.template')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid mt-4">
  <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-2">
    <div class="col">
      <h3 class="fw-bold mb-2">Konfigurasi </h3>
      <h5 class="text-muted mb-3"> Konfigurasi Lokasi Kantor</h5>
    </div>
  </div>

  <div class="page-body">
    <div class="container-xl">
      <div class="row">
        <div class="col-6">
          <div class="card shadow-sm">
            <div class="card-body">
            <form action="{{ url('/konfigurasi/updatelokasikantor') }}" method="POST">
    @csrf
    <div class="input-group mb-3">
        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
        <input type="text" name="lokasi_kantor" class="form-control" placeholder="Masukkan Lokasi Kantor"
            value="{{ $lok_kantor->lokasi_kantor ?? '' }}">
    </div>

    <div class="input-group mb-3">
        <span class="input-group-text"><i class="bi bi-bullseye"></i></span>
        <input type="text" name="radius" class="form-control" placeholder="Radius"
            value="{{ $lok_kantor->radius ?? '' }}">
    </div>

    <button type="submit" class="btn btn-primary w-100">Simpan</button>
</form>


            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- JS Dependencies -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

@endsection
