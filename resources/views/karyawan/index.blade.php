@extends('layout.admin.template')
@section('content')

<!-- Styles -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<div class="container-fluid">
  <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div class="col">
      <h3 class="fw-bold mb-4">Dashboard</h3>
    </div>
  </div>

  <div class="page-body">
    <div class="container-xl">
      <div class="row">
        <div class="col-12">
          <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
              <h4 class="mb-0 fw-bold">
                <i class="bi bi-people-fill me-2"></i> Data Karyawan
              </h4>
              <a href="#" class="btn btn-light btn-sm fw-semibold" id="btn-tambahkaryawan">
                <i class="bi bi-plus-circle me-1"></i> Tambah Data
              </a>
            </div>

            <div class="card-body">
              <div class="table-responsive">
                <!-- Search -->
                <form action="/karyawan" method="GET" class="mb-4">
                  <div class="row g-2 align-items-center">
                    <div class="col-md-5">
                      <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                        <input type="text" name="nama_karyawan" id="nama_karyawan"
                          class="form-control" placeholder="Cari Nama Karyawan"
                          value="{{ request('nama_karyawan') }}">
                      </div>
                    </div>

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

                    <div class="col-md-2 d-grid">
                      <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i> Search
                      </button>
                    </div>
                  </div>
                </form>

                <!-- Table -->
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
                      @php $path = Storage::url('uploads/karyawan/'.$d->foto); @endphp
                      <tr>
                        <td>{{ $loop->iteration + $karyawan->firstItem() -1 }}</td>
                        <td>{{ $d->nik }}</td>
                        <td class="fw-semibold">{{ $d->nama_lengkap }}</td>
                        <td><span class="badge bg-info px-3 py-2">{{ $d->jabatan }}</span></td>
                        <td>{{ $d->no_hp }}</td>
                        <td>
                          @if(empty($d->foto))
                            <img src="{{ asset('assets/img/image.png') }}"
                              class="rounded-circle border shadow-sm"
                              style="width: 50px; height: 50px; object-fit: cover;">
                          @else
                            <img src="{{ url($path) }}" alt="Foto Karyawan"
                              class="rounded-circle border shadow-sm"
                              style="width: 50px; height: 50px; object-fit: cover;">
                          @endif
                        </td>
                        <td><span class="badge bg-success px-3 py-2">{{ $d->nama_dept }}</span></td>
                        <td>
                          <div class="d-flex justify-content-center gap-2">
                           

                            <a href="#" class="edit" nik="{{ $d->nik }}">
                              <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ url('/karyawan'.$d->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
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

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah Data Karyawan -->
<div class="modal fade" id="modal-inputkaryawan" tabindex="-1" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow-lg rounded-3">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold"><i class="bi bi-person-plus-fill me-2"></i> Tambah Data Karyawan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form method="POST" enctype="multipart/form-data" id="frmkaryawan">
        @csrf
        <div class="modal-body">
          <div class="row g-3">

            <!-- NIK -->
            <div class="col-12">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                <input type="text" name="nik" id="nik" class="form-control" placeholder="Masukkan NIK" autocomplete="off">
              </div>
            </div>

            <!-- Nama Lengkap -->
            <div class="col-12">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" placeholder="Nama Lengkap" autocomplete="off">
              </div>
            </div>

            <!-- Jabatan -->
            <div class="col-12">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-briefcase"></i></span>
                <input type="text" name="jabatan" id="jabatan" class="form-control" placeholder="Jabatan" autocomplete="off">
              </div>
            </div>

            <!-- No HP -->
            <div class="col-12">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                <input type="text" name="no_hp" id="no_hp" class="form-control" placeholder="Nomor HP" autocomplete="off">
              </div>
            </div>

            <!-- Password -->
            <div class="col-12">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan Password">
              </div>
              <small class="text-muted">Password akan otomatis di-hash di server sebelum disimpan.</small>
            </div>

            <!-- Foto -->
            <div class="col-12">
              <label for="foto" class="form-label fw-semibold mb-1"><i class="bi bi-image me-1"></i> Upload Foto</label>
              <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
              <small class="text-muted">Format: JPG, JPEG, PNG | Maksimal 2MB</small>
            </div>

            <!-- Preview Foto -->
            <div class="col-12 text-center mt-2">
              <img id="Foto" src="{{ asset('assets/img/image.png') }}" alt="Preview Foto"
                class="rounded-circle border shadow-sm" style="width: 80px; height: 80px; object-fit: cover;">
            </div>

            <!-- Departemen -->
            <div class="col-12">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-diagram-3"></i></span>
                <select name="kode_dept" id="kode_dept_form" class="form-select">
                  <option value="">Pilih Departemen</option>
                  @foreach ($departemen as $dept)
                    <option value="{{ $dept->kode_dept }}">{{ $dept->nama_dept }}</option>
                  @endforeach
                </select>
              </div>
            </div>

          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1"></i> Tutup</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-save2 me-1"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- MODAL EDIT -->
<div class="modal fade" id="modal-editkaryawan" tabindex="-1" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow-lg rounded-3">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold"><i class="bi bi-person-plus-fill me-2"></i> Edit Data Karyawan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="loadeditform"> 

      </div>
      
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function() {
  const $modal = $('#modal-inputkaryawan');
  const defaultPreview = "{{ asset('assets/img/image.png') }}";

  $('#btn-tambahkaryawan').on('click', function(e) {
    e.preventDefault();
    $('#frmkaryawan')[0].reset();
    $('#Foto').attr('src', defaultPreview);
    $('.is-invalid').removeClass('is-invalid');
    $modal.modal('show');
  });

  

  $('#foto').on('change', function(e) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(ev) { $('#Foto').attr('src', ev.target.result); };
      reader.readAsDataURL(file);
    } else { $('#Foto').attr('src', defaultPreview); }
  });

  $(".edit").click(function() {
    var nik = $(this).attr('nik');
    $.ajax({
        type: 'POST'
        , url: '/karyawan/edit'
        , cache: false
        , data: {
            _token: "{{  csrf_token() }}"
            , nik: nik
        }
        , success:function(respond){
            $("#loadeditform").html(respond);
        }
    })
    $("#modal-editkaryawan").modal("show");
  });

  $('#frmkaryawan').on('submit', function(e) {
    e.preventDefault();

    const $nik = $('#nik'), $nama = $('#nama_lengkap'), $jab = $('#jabatan'),
          $hp = $('#no_hp'), $pass = $('#password'), $dept = $('#kode_dept_form');

    const checks = [
      [$nik, 'NIK wajib diisi.'],
      [$nama, 'Nama Lengkap wajib diisi.'],
      [$jab, 'Jabatan wajib diisi.'],
      [$hp, 'Nomor HP wajib diisi.'],
      [$pass, 'Password wajib diisi.'],
      [$dept, 'Silakan pilih Departemen.']
    ];

    for (let [f, msg] of checks) {
      if (!f.val().trim()) {
        f.addClass('is-invalid');
        Swal.fire({icon:'warning',title:'Form Belum Lengkap',text:msg}).then(()=>f.focus());
        return false;
      }
    }

    // Kirim data pakai AJAX
    let formData = new FormData(this);
    $.ajax({
      url: '/karyawan/store',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function(res) {
        Swal.fire({icon:'success',title:'Berhasil',text:'Data karyawan berhasil disimpan!',timer:2000,showConfirmButton:false});
        $modal.modal('hide');
        $('#frmkaryawan')[0].reset();
        $('#Foto').attr('src', defaultPreview);
        setTimeout(()=>location.reload(), 2000);
      },
      error: function(err) {
        Swal.fire({icon:'error',title:'Gagal',text:'Terjadi kesalahan saat menyimpan data!'});
      }
    });
  });

  @if(session('success'))
    Swal.fire({icon:'success',title:'Berhasil',text:{!! json_encode(session('success')) !!},timer:2200,showConfirmButton:false});
  @endif
});
</script>
@endpush