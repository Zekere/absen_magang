@extends('layout.admin.template')
@section('content')

<!-- ======= Styles ======= -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<div class="container-fluid mt-5">

  <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div class="col">
      <h3 class="fw-bold mb-4">Dashboard</h3>
    </div>
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
            <a href="#" class="btn btn-light btn-sm fw-semibold" id="btn-tambahkaryawan" data-bs-toggle="modal" data-bs-target="#modal-inputkaryawan" role="button" aria-pressed="false">
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
                          <!-- Edit: gunakan attribute nik -->
                          <a href="javascript:void(0)" class="edit btn btn-info btn-sm" data-nik="{{ $d->nik }}" title="Edit">
                            <i class="bi bi-pencil-square"></i>
                          </a>

                          <!-- Hapus -->
                          <form action="/karyawan/{{ $d->nik }}/delete" method="POST" class="form-delete">
                            @csrf
                            <button type="button" class="btn btn-sm btn-danger d-inline-flex align-items-center delete-confirm">
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

<!-- SCRIPTS: pastikan bundle (Popper included) dimuat sebelum script yang memanggil bootstrap.Modal -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  console.log('Dashboard Karyawan - script loaded');

  const defaultPreview = "{{ asset('assets/img/image.png') }}";
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Bootstrap modal instances
  const modalTambahEl = document.getElementById('modal-inputkaryawan');
  const modalEditEl = document.getElementById('modal-editkaryawan');
  const bsModalTambah = new bootstrap.Modal(modalTambahEl, {});
  const bsModalEdit = new bootstrap.Modal(modalEditEl, {});

  // Jika tombol "Tambah Data" diklik
  const btnTambah = document.getElementById('btn-tambahkaryawan');
  if (btnTambah) {
    btnTambah.addEventListener('click', function(e) {
      e.preventDefault();
      const frm = document.getElementById('frmkaryawan');
      if (frm) {
        frm.reset();
        document.getElementById('Foto').src = defaultPreview;
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
      }
      bsModalTambah.show();
    });
  }

  // Preview foto
  const inputFoto = document.getElementById('foto');
  if (inputFoto) {
    inputFoto.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(ev) { document.getElementById('Foto').src = ev.target.result; };
        reader.readAsDataURL(file);
      } else {
        document.getElementById('Foto').src = defaultPreview;
      }
    });
  }

  // Delegated listener untuk tombol Edit (menggunakan data-nik)
  document.addEventListener('click', function(e) {
    const el = e.target.closest('.edit');
    if (!el) return;
    e.preventDefault();
    const nik = el.getAttribute('data-nik');
    if (!nik) {
      Swal.fire({ icon: 'error', title: 'Gagal', text: 'NIK tidak ditemukan.' });
      return;
    }
    console.log('Memuat form edit untuk NIK:', nik);

    // POST menggunakan FormData
    const fd = new FormData();
    fd.append('_token', csrfToken);
    fd.append('nik', nik);

    fetch('/karyawan/edit', {
      method: 'POST',
      body: fd,
    })
    .then(resp => {
      if (!resp.ok) throw new Error('Network response was not ok');
      return resp.text();
    })
    .then(html => {
      document.getElementById('loadeditform').innerHTML = html;
      bsModalEdit.show();
    })
    .catch(err => {
      console.error(err);
      Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat memuat data edit.' });
    });
  });

  // Tombol Hapus dengan SweetAlert konfirmasi
  document.addEventListener('click', function(e) {
    const el = e.target.closest('.delete-confirm');
    if (!el) return;
    e.preventDefault();
    const form = el.closest('form');

    Swal.fire({
      title: 'Anda Yakin?',
      text: 'Setelah dihapus, Anda tidak dapat mengembalikannya lagi!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal',
      dangerMode: true,
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit();
      } else {
        Swal.fire({ icon: 'info', title: 'Dibatalkan', text: 'Data anda aman 😊' });
      }
    });
  });

  // Submit tambah karyawan dengan Fetch + FormData
  const frmKaryawan = document.getElementById('frmkaryawan');
  if (frmKaryawan) {
    frmKaryawan.addEventListener('submit', function(e) {
      e.preventDefault();

      // Validasi sederhana
      const checks = [
        ['nik', 'NIK wajib diisi.'],
        ['nama_lengkap', 'Nama Lengkap wajib diisi.'],
        ['jabatan', 'Jabatan wajib diisi.'],
        ['no_hp', 'Nomor HP wajib diisi.'],
        ['password', 'Password wajib diisi.'],
        ['kode_dept', 'Silakan pilih Departemen.']
      ];

      for (let [name, msg] of checks) {
        const field = frmKaryawan.querySelector('[name="'+name+'"]');
        if (!field) continue;
        if (!field.value || !field.value.toString().trim()) {
          field.classList.add('is-invalid');
          Swal.fire({ icon:'warning', title:'Form Belum Lengkap', text: msg }).then(() => field.focus());
          return;
        } else {
          field.classList.remove('is-invalid');
        }
      }

      const formData = new FormData(frmKaryawan);

      fetch('/karyawan/store', {
        method: 'POST',
        body: formData
      })
      .then(resp => {
        if (!resp.ok) throw new Error('Network response not ok');
        return resp.json().catch(() => ({ ok: true }));
      })
      .then(data => {
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: 'Data karyawan berhasil disimpan!',
          timer: 2000,
          showConfirmButton: false
        });
        bsModalTambah.hide();
        frmKaryawan.reset();
        document.getElementById('Foto').src = defaultPreview;
        setTimeout(() => location.reload(), 1200);
      })
      .catch(err => {
        console.error(err);
        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan saat menyimpan data!' });
      });
    });
  }

  // Menangani notifikasi dari session (jika ada)
  @if(session('success'))
    Swal.fire({
      icon: 'success',
      title: 'Berhasil',
      text: {!! json_encode(session('success')) !!},
      timer: 2200,
      showConfirmButton: false
    });
  @endif

  @if(session('warning'))
    Swal.fire({
      icon: 'warning',
      title: 'Peringatan',
      text: {!! json_encode(session('warning')) !!},
      timer: 2200,
      showConfirmButton: false
    });
  @endif

});
</script>

@endsection