@extends('layout.admin.template')
@section('content')

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
              <i class="bi bi-diagram-3-fill me-2"></i> Data Departemen
            </h4>
            <a href="#" class="btn btn-light btn-sm fw-semibold" id="btn-tambahdepartemen" data-bs-toggle="modal" data-bs-target="#modal-inputdepartemen" role="button" aria-pressed="false">
              <i class="bi bi-plus-circle me-1"></i> Tambah Data
            </a>
          </div>

          <div class="card-body">
            <div class="table-responsive">
              <!-- Search -->
              <form action="/departemen" method="GET" class="mb-4">
                <div class="row g-2 align-items-center">
                  <div class="col-md-5">
                    <div class="input-group">
                      <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                      <input type="text" name="nama_dept" id="nama_dept"
                        class="form-control" placeholder="Cari Nama Departemen"
                        value="{{ request('nama_dept') }}">
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
                    <th>Kode Departemen</th>
                    <th>Nama Departemen</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($departemen as $d)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $d->kode_dept }}</td>
                      <td class="fw-semibold">{{ $d->nama_dept }}</td>
                      <td>
                        <div class="d-flex justify-content-center gap-2">
                          <!-- Edit -->
                          <a href="javascript:void(0)" class="edit btn btn-info btn-sm" kode_dept="{{ $d->kode_dept }}" title="Edit">
                            <i class="bi bi-pencil-square"></i>
                          </a>

                          <!-- Hapus -->
                          <form action="/departemen/{{ $d->kode_dept }}/delete" method="POST">
                            @csrf
                            <a class="btn btn-sm btn-danger d-inline-flex align-items-center delete-confirm">
                              <i class="bi bi-trash-fill"></i>
                            </a>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah Data Departemen -->
<div class="modal fade" id="modal-inputdepartemen" tabindex="-1" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow-lg rounded-3">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold"><i class="bi bi-folder-plus me-2"></i> Tambah Data Departemen</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form method="POST" id="frmdepartemen">
        @csrf
        <div class="modal-body">
          <div class="row g-3">

            <!-- Kode Departemen -->
            <div class="col-12">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                <input type="text" name="kode_dept" id="kode_dept" class="form-control" placeholder="Masukkan Kode Departemen" autocomplete="off">
              </div>
            </div>

            <!-- Nama Departemen -->
            <div class="col-12">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-building"></i></span>
                <input type="text" name="nama_dept" id="nama_dept_input" class="form-control" placeholder="Masukkan Nama Departemen" autocomplete="off">
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
<div class="modal fade" id="modal-editdepartemen" tabindex="-1" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow-lg rounded-3">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold"><i class="bi bi-pencil-fill me-2"></i> Edit Data Departemen</h5>
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
  console.log('Dashboard Departemen - script loaded');

  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Bootstrap modal instances
  const modalTambahEl = document.getElementById('modal-inputdepartemen');
  const modalEditEl = document.getElementById('modal-editdepartemen');
  const bsModalTambah = new bootstrap.Modal(modalTambahEl, {});
  const bsModalEdit = new bootstrap.Modal(modalEditEl, {});

  // Jika tombol "Tambah Data" diklik (data-bs-toggle juga akan bekerja jika Bootstrap JS aktif)
  const btnTambah = document.getElementById('btn-tambahdepartemen');
  if (btnTambah) {
    btnTambah.addEventListener('click', function(e) {
      e.preventDefault();
      const frm = document.getElementById('frmdepartemen');
      if (frm) {
        frm.reset();
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
      }
      bsModalTambah.show();
    });
  }

  // Delegated listener untuk tombol Edit (menggunakan attribute kode_dept)
  document.addEventListener('click', function(e) {
    const el = e.target.closest('.edit');
    if (!el) return;
    e.preventDefault();
    const kode = el.getAttribute('kode_dept');
    if (!kode) {
      Swal.fire({ icon: 'error', title: 'Gagal', text: 'Kode Departemen tidak ditemukan.' });
      return;
    }
    console.log('Memuat form edit untuk Kode Departemen:', kode);

    // POST menggunakan FormData (mirip jQuery) supaya Laravel mudah menerima
    const fd = new FormData();
    fd.append('_token', csrfToken);
    fd.append('kode_dept', kode);

    fetch('/departemen/edit', {
      method: 'POST',
      body: fd,
    })
    .then(resp => {
      if (!resp.ok) throw new Error('Network response was not ok');
      return resp.text(); // server biasanya mengembalikan HTML berupa form edit
    })
    .then(html => {
      document.getElementById('loadeditform').innerHTML = html;
      // Show modal edit
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
        // Kirim form ke server untuk hapus data
        form.submit();
      } else {
        Swal.fire({ icon: 'info', title: 'Dibatalkan', text: 'Data anda aman 😊' });
      }
    });
  });

  // Submit tambah departemen dengan Fetch + FormData
  const frmDepartemen = document.getElementById('frmdepartemen');
  if (frmDepartemen) {
    frmDepartemen.addEventListener('submit', function(e) {
      e.preventDefault();

      // Validasi sederhana
      const checks = [
        ['kode_dept', 'Kode Departemen wajib diisi.'],
        ['nama_dept', 'Nama Departemen wajib diisi.']
      ];

      for (let [name, msg] of checks) {
        const field = frmDepartemen.querySelector('[name="'+name+'"]');
        if (!field) continue;
        if (!field.value || !field.value.toString().trim()) {
          field.classList.add('is-invalid');
          Swal.fire({ icon:'warning', title:'Form Belum Lengkap', text: msg }).then(() => field.focus());
          return;
        } else {
          field.classList.remove('is-invalid');
        }
      }

      const formData = new FormData(frmDepartemen);

      fetch('/departemen/store', {
        method: 'POST',
        body: formData
      })
      .then(resp => {
        if (!resp.ok) throw new Error('Network response not ok');
        // Bisa jadi server redirect atau return JSON; kita coba parse JSON, kalau gagal lanjut
        return resp.json().catch(() => ({ ok: true }));
      })
      .then(data => {
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: 'Data departemen berhasil disimpan!',
          timer: 2000,
          showConfirmButton: false
        });
        bsModalTambah.hide();
        frmDepartemen.reset();
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