@extends('layout.admin.template')
@section('content')

<!-- ======= Styles ======= -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid mt-5">

  <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div class="col">
      <h3 class="fw-bold mb-4">Data Admin</h3>
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
              <i class="bi bi-person-badge-fill me-2"></i> Data Admin
            </h4>
            <a href="#" class="btn btn-light btn-sm fw-semibold" id="btn-tambahadmin" data-bs-toggle="modal" data-bs-target="#modal-inputadmin" role="button" aria-pressed="false">
              <i class="bi bi-plus-circle me-1"></i> Tambah Data
            </a>
          </div>

          <div class="card-body">
            <div class="table-responsive">
              <!-- Search -->
              <form action="/" method="GET" class="mb-4">
                <div class="row g-2 align-items-center">
                  <div class="col-md-8">
                    <div class="input-group">
                      <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                      <input type="text" name="nama_admin" id="nama_admin"
                        class="form-control" placeholder="Cari Nama Admin"
                        value="{{ request('nama_admin') }}">
                    </div>
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
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Tanggal Dibuat</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($admin as $d)
                    <tr>
                      <td>{{ $loop->iteration + $admin->firstItem() -1 }}</td>
                      <td class="fw-semibold">{{ $d->name }}</td>
                      <td>{{ $d->email }}</td>
                      <td>{{ $d->created_at ? \Carbon\Carbon::parse($d->created_at)->format('d M Y H:i') : '-' }}</td>
                      <td>
                        <div class="d-flex justify-content-center gap-2">
                          <!-- Edit: gunakan attribute id -->
                          <a href="javascript:void(0)" class="edit btn btn-info btn-sm" data-id="{{ $d->id }}" title="Edit">
                            <i class="bi bi-pencil-square"></i>
                          </a>

                          <!-- Hapus -->
                          <form action="/admin/{{ $d->id }}/delete" method="POST" class="form-delete">
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
                {{ $admin->links('pagination::bootstrap-5') }}
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah Data Admin -->
<div class="modal fade" id="modal-inputadmin" tabindex="-1" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow-lg rounded-3">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold"><i class="bi bi-person-plus-fill me-2"></i> Tambah Data Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form method="POST" id="frmadmin">
        @csrf
        <div class="modal-body">
          <div class="row g-3">

            <!-- Nama -->
            <div class="col-12">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" name="name" id="name" class="form-control" placeholder="Nama Lengkap" autocomplete="off">
              </div>
            </div>

            <!-- Email -->
            <div class="col-12">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" id="email" class="form-control" placeholder="Email" autocomplete="off">
              </div>
            </div>

            <!-- Password -->
            <div class="col-12">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan Password">
              </div>
              <small class="text-muted">Masukan Password minimal 8 Karakter.</small>
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
<div class="modal fade" id="modal-editadmin" tabindex="-1" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow-lg rounded-3">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold"><i class="bi bi-person-plus-fill me-2"></i> Edit Data Admin</h5>
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
  console.log('Dashboard Admin - script loaded');

  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Bootstrap modal instances
  const modalTambahEl = document.getElementById('modal-inputadmin');
  const modalEditEl = document.getElementById('modal-editadmin');
  const bsModalTambah = new bootstrap.Modal(modalTambahEl, {});
  const bsModalEdit = new bootstrap.Modal(modalEditEl, {});

  // Jika tombol "Tambah Data" diklik
  const btnTambah = document.getElementById('btn-tambahadmin');
  if (btnTambah) {
    btnTambah.addEventListener('click', function(e) {
      e.preventDefault();
      const frm = document.getElementById('frmadmin');
      if (frm) {
        frm.reset();
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
      }
      bsModalTambah.show();
    });
  }

  // Delegated listener untuk tombol Edit (menggunakan data-id)
  document.addEventListener('click', function(e) {
    const el = e.target.closest('.edit');
    if (!el) return;
    e.preventDefault();
    const id = el.getAttribute('data-id');
    if (!id) {
      Swal.fire({ icon: 'error', title: 'Gagal', text: 'ID tidak ditemukan.' });
      return;
    }
    console.log('Memuat form edit untuk ID:', id);

    // POST menggunakan FormData
    const fd = new FormData();
    fd.append('_token', csrfToken);
    fd.append('id', id);

    fetch('/admin/edit', {
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

  // Submit tambah admin dengan Fetch + FormData
  const frmAdmin = document.getElementById('frmadmin');
  if (frmAdmin) {
    frmAdmin.addEventListener('submit', function(e) {
      e.preventDefault();

      // Validasi sederhana
      const checks = [
        ['name', 'Nama wajib diisi.'],
        ['email', 'Email wajib diisi.'],
        ['password', 'Password wajib diisi.']
      ];

      for (let [name, msg] of checks) {
        const field = frmAdmin.querySelector('[name="'+name+'"]');
        if (!field) continue;
        if (!field.value || !field.value.toString().trim()) {
          field.classList.add('is-invalid');
          Swal.fire({ icon:'warning', title:'Form Belum Lengkap', text: msg }).then(() => field.focus());
          return;
        } else {
          field.classList.remove('is-invalid');
        }
      }

      const formData = new FormData(frmAdmin);

      fetch('/admin/store', {
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
          text: 'Data admin berhasil disimpan!',
          timer: 2000,
          showConfirmButton: false
        });
        bsModalTambah.hide();
        frmAdmin.reset();
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