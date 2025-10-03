@extends('layout.admin.template')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<div class="container-fluid">
  <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <div class ="col">
      <h3 class="fw-bold mb-4">Data Karyawan</h3>
    </div>
    </div>
  
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg border-0 rounded-3">
                    <!-- Header Card -->
                    <div class="card-header bg-primary text-white py-3">
                        <h4 class="mb-0 fw-bold">
                            <i class="bi bi-people-fill me-2"></i> Daftar Karyawan
                        </h4>
                    </div>

                    <!-- Body Card -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle text-center">
                                <thead class="table-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        <th>No HP</th>
                                        <th>Foto</th>
                                        <th>Departemen</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($karyawan as $d)
                                    @php
                                        $path = Storage::url('uploads/karyawan/'.$d->foto);
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $d->nik }}</td>
                                        <td class="fw-semibold">{{ $d->nama_lengkap }}</td>
                                        <td>
                                            <span class="badge bg-info px-3 py-2">{{ $d->jabatan }}</span>
                                        </td>
                                        <td>{{ $d->no_hp }}</td>
                                        <td>
                                            @if(empty($d->foto))
                                                <img src="{{ asset('assets/img/image.png') }}" 
                                                     class="rounded-circle border shadow-sm" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <img src="{{ url($path) }}" 
                                                     alt="Foto Karyawan"
                                                     class="rounded-circle border shadow-sm" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-success px-3 py-2">{{ $d->nama_dept }}</span>
                                        </td>
                                       <td>
    <div class="d-flex justify-content-center gap-2">
        <a href="#" class="btn btn-sm btn-warning d-inline-flex align-items-center">
            <i class="bi bi-pencil-square me-1"></i> 
        </a>
        <a href="#" class="btn btn-sm btn-danger d-inline-flex align-items-center">
            <i class="bi bi-trash-fill me-1"></i> 
        </a>
    </div>
</td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> <!-- table-responsive -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection