@extends('layout.admin.template')
@section('content')

<!-- ======= Styles ======= -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid">
  <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div class="col">
      <h3 class="fw-bold mb-4">Departemen</h3>
    </div>
  </div>

  <div class="page-body">
    <div class="container-xl">
      <div class="row">
        <div class="col-12">
          <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
              <h4 class="mb-0 fw-bold">
                <i class="bi bi-people-fill me-2"></i> Data Departemen
              </h4>
              <button class="btn btn-light btn-sm fw-semibold" id="btn-tambahdepartemen">
                <i class="bi bi-plus-circle me-1"></i> Tambah Data
              </button>
            </div>

            <div class="card-body">
              <div class="table-responsive">
                <!-- Search -->
                <form action="/departemen" method="GET" class="mb-4">
                  <div class="row g-2 align-items-center">
                    <div class="col-md-5">
                      <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                        <input type="text" name="nama_dept" id="nama_dept" class="form-control" placeholder="Departemen" value="{{ request('nama_dept') }}">
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
                        <td>{{ $d->nama_dept }}</td>
                        <td>
                          <div class="d-flex justify-content-center gap-2">
                            <!-- Tombol Edit -->
                       <a href="javascript:void(0)" 
   class="btn btn-info btn-sm edit" 
   kode_dept="{{ $d->kode_dept }}" 
   title="Edit">
  <i class="bi bi-pencil-square"></i>
</a>


                            <!-- Tombol Hapus -->
                            <form action="/departemen/{{ $d->kode_dept }}/delete" method="POST" style="display:inline;">
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
</div>

<!-- ======= Modal Tambah ======= -->
<div class="modal fade" id="modal-inputdepartemen" tabindex="-1" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow-lg rounded-3">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold"><i class="bi bi-person-plus-fill me-2"></i> Tambah Data Departemen</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

     <form id="frmdepartemen" method="POST">
  @csrf
  <div class="modal-body">
    <div class="row g-3">
      <div class="col-12">
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
          <input type="text" name="kode_dept" id="kode_dept" class="form-control" placeholder="Masukkan Kode Departemen" autocomplete="off">
        </div>
      </div>

      <div class="col-12">
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-person"></i></span>
          <input type="text" name="nama_dept" id="nama_dept_input" class="form-control" placeholder="Masukkan Nama Departemen" autocomplete="off">
        </div>
      </div>
    </div>
  </div>

  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
      <i class="bi bi-x-circle me-1"></i> Tutup
    </button>
    <button type="submit" class="btn btn-primary">
      <i class="bi bi-save2 me-1"></i> Simpan
    </button>
  </div>
</form>
</div>
</div>
</div>


<!-- ======= Modal Edit ======= -->
<div class="modal fade" id="modal-editdepartemen" tabindex="-1" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow-lg rounded-3">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold"><i class="bi bi-pencil-fill me-2"></i> Edit Data Departemen</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="loadeditform"></div>
    </div>
  </div>
</div>

<!-- ======= Scripts ======= -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  console.log('Departemen page loaded');

  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  const frmdepartemen = document.getElementById('frmdepartemen');
  const modalTambahEl = document.getElementById('modal-inputdepartemen');
  const modalEditEl = document.getElementById('modal-editdepartemen');
  const bsModalTambah = new bootstrap.Modal(modalTambahEl);
  const bsModalEdit = new bootstrap.Modal(modalEditEl);

  // === Tombol "Tambah Data" ===
  document.getElementById('btn-tambahdepartemen').addEventListener('click', e => {
    e.preventDefault();
    frmdepartemen.reset();
    bsModalTambah.show();
  });

  // === Tombol Hapus ===
  document.querySelectorAll('.delete-confirm').forEach(btn => {
    btn.addEventListener('click', e => {
      e.preventDefault();
      const form = btn.closest('form');
      Swal.fire({
        title: 'Anda yakin?',
        text: 'Setelah dihapus, data tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then(result => {
        if (result.isConfirmed) form.submit();
      });
    });
  });

  // === Submit Tambah Departemen ===
  frmdepartemen.addEventListener('submit', async e => {
    e.preventDefault();

    const kode = frmdepartemen.querySelector('[name="kode_dept"]').value.trim();
    const nama = frmdepartemen.querySelector('[name="nama_dept"]').value.trim();

    if (!kode) {
      Swal.fire({ icon: 'warning', title: 'Oops!', text: 'Kode Departemen wajib diisi.' });
      return;
    }
    if (!nama) {
      Swal.fire({ icon: 'warning', title: 'Oops!', text: 'Nama Departemen wajib diisi.' });
      return;
    }

    try {
      const formData = new FormData(frmdepartemen);
      const response = await fetch('/departemen/store', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken },
        body: formData
      });

      let data = {};
      try { data = await response.json(); } catch { data = {}; }

      if (data.status === 'success') {
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: data.message || 'Data Departemen berhasil disimpan!',
          timer: 1800,
          showConfirmButton: false
        });
        bsModalTambah.hide();
        frmdepartemen.reset();
        setTimeout(() => location.reload(), 1000);
      } else if (response.redirected || response.ok) {
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: 'Data Departemen berhasil disimpan!',
          timer: 1800,
          showConfirmButton: false
        });
        bsModalTambah.hide();
        frmdepartemen.reset();
        setTimeout(() => location.reload(), 1000);
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Gagal',
          text: data.message || 'Terjadi kesalahan saat menyimpan data!'
        });
      }
    } catch (err) {
      console.error('Error:', err);
      Swal.fire({ icon: 'error', title: 'Error', text: 'Tidak dapat terhubung ke server!' });
    }
  });

  // === Tombol Edit Departemen ===
  // === Tombol Edit Departemen ===
document.querySelectorAll('.edit').forEach(btn => {
  btn.addEventListener('click', async e => {
    e.preventDefault();

    const kode = btn.dataset.kode || btn.getAttribute('kode_dept');
    console.log('Edit departemen:', kode);

    if (!kode) {
      console.error('Kode departemen tidak ditemukan.');
      return;
    }

    try {
      // Kirim kode_dept lewat POST body
      const formData = new FormData();
      formData.append('kode_dept', kode);

      const response = await fetch(`/departemen/edit`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken },
        body: formData
      });

      if (!response.ok) throw new Error('Gagal memuat form edit.');

      const html = await response.text();
      document.getElementById('loadeditform').innerHTML = html;
      bsModalEdit.show();

      // === Setelah form edit dimuat ===
      const formEdit = document.getElementById('frmeditdepartemen');
      if (formEdit) {
        formEdit.addEventListener('submit', async e => {
          e.preventDefault();
          const formData = new FormData(formEdit);

          try {
            const updateResponse = await fetch(`/departemen/${kode}/update`, {
              method: 'POST',
              headers: { 'X-CSRF-TOKEN': csrfToken },
              body: formData
            });

            const data = await updateResponse.json();

            if (data.success) {
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: data.message || 'Data departemen berhasil diperbarui.',
                timer: 1500,
                showConfirmButton: false
              });
              bsModalEdit.hide();

              if (typeof reloadTable === 'function') reloadTable();
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: data.message || 'Gagal memperbarui data.'
              });
            }
          } catch (err) {
            console.error('Error saat update:', err);
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Terjadi kesalahan saat memperbarui data.'
            });
          }
        });
      }
    } catch (error) {
      console.error(error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Gagal memuat form edit. Pastikan route dan controller sesuai.'
      });
    }
  });
});



  // === Notifikasi dari session backend ===
  @if(session('success'))
    Swal.fire({
      icon: 'success',
      title: 'Berhasil',
      text: {!! json_encode(session('success')) !!},
      timer: 2000,
      showConfirmButton: false
    });
  @endif

  @if(session('warning'))
    Swal.fire({
      icon: 'warning',
      title: 'Gagal',
      text: {!! json_encode(session('warning')) !!},
      timer: 2000,
      showConfirmButton: false
    });
  @endif
});
</script>


@endsection
