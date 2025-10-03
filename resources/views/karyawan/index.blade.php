@extends('layout.admin.template')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container-fluid">
  <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <div class ="col">
      <h3 class="fw-bold mb-4">Dashboard</h3>
    </div>
    </div>
   
</div>

  <div class="page-body">
    <div class="container-xl">
      <div class="row">
        <div class="col-12">
          <div class="card shadow-lg border-0 rounded-3">
            <!-- Header Card -->
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
              <h4 class="mb-0 fw-bold">
                <i class="bi bi-people-fill me-2"></i> Data Karyawan
              </h4>
              <!-- Button Tambah Data -->
              <a href="#" class="btn btn-light btn-sm fw-semibold" id="btn-tambahkaryawan">
                <i class="bi bi-plus-circle me-1"></i> Tambah Data
              </a>
            </div>

            <!-- Body Card -->
            <div class="card-body">
              <div class="table-responsive">
                <form action="/karyawan" method="GET" class="mb-4">
                  <div class="row g-2 align-items-center">
                    <!-- Input Nama Karyawan -->
                    <div class="col-md-5">
                      <input type="text" name="nama_karyawan" id="nama_karyawan" 
                             class="form-control" placeholder="Cari Nama Karyawan"
                             value="{{ request('nama_karyawan') }}">
                    </div>

                    <!-- Dropdown Departemen -->
                    <div class="col-md-4">
                      <select name="kode_dept" id="kode_dept" class="form-select">
                        <option value="">Pilih Departemen</option>
                        @foreach ($departemen as $dept)
                          <option value="{{ $dept->kode_dept }}" 
                            {{ request('kode_dept') == $dept->kode_dept ? 'selected' : '' }}>
                            {{ $dept->nama_dept }}
                          </option>
                        @endforeach
                      </select>
                    </div>

                    <!-- Tombol Cari -->
                    <div class="col-md-2 d-grid">
                      <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i> Search
                      </button>
                    </div>
                  </div>
                </form>

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
                        <td>{{ $loop->iteration + $karyawan->firstItem() -1 }}</td>
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
                            <a href="{{ url('/karyawan/'.$d->id.'/edit') }}" class="btn btn-sm btn-warning d-inline-flex align-items-center">
                              <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ url('/karyawan/'.$d->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-danger d-inline-flex align-items-center">
                                <i class="bi bi-trash-fill"></i>
                              </button>
                            </form>
                          </div>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                  {{ $karyawan->links('pagination::bootstrap-5') }}
                </div>
              </div> <!-- table-responsive -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" id="modal-inputkaryawan" 
     data-bs-backdrop="false" data-bs-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Data Karyawan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="#" method="POST"></form>
        <div class="row">
            <div class="col-12">
                <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler.io/icons/icon/user -->
                                 <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-barcode"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7v-1a2 2 0 0 1 2 -2h2" /><path d="M4 17v1a2 2 0 0 0 2 2h2" /><path d="M16 4h2a2 2 0 0 1 2 2v1" /><path d="M16 20h2a2 2 0 0 0 2 -2v-1" /><path d="M5 11h1v2h-1z" /><path d="M10 11l0 2" /><path d="M14 11h1v2h-1z" /><path d="M19 11l0 2" /></svg>
                                </span>
                                <input type="text" value="" class="form-control" style="margin-right:24px;" placeholder="NIK">
                              </div>
            </div>
        </div>
        
      </div>
       <div class="modal-body">
        <form action="#" method="POST"></form>
        <div class="row">
            <div class="col-12">
                <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler.io/icons/icon/user -->
                                 <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-barcode"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7v-1a2 2 0 0 1 2 -2h2" /><path d="M4 17v1a2 2 0 0 0 2 2h2" /><path d="M16 4h2a2 2 0 0 1 2 2v1" /><path d="M16 20h2a2 2 0 0 0 2 -2v-1" /><path d="M5 11h1v2h-1z" /><path d="M10 11l0 2" /><path d="M14 11h1v2h-1z" /><path d="M19 11l0 2" /></svg>
                                </span>
                                <input type="text" value="" class="form-control" style="margin-right:24px;" placeholder="NIK">
                              </div>
            </div>
        </div>
        
      </div>
      
      

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>



@endsection

@push ('scripts')

<script >
    $(function()  {
$("#btn-tambahkaryawan").click(function(){
$("#modal-inputkaryawan").modal("show");
});
    });
</script>
@endpush
