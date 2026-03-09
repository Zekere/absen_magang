@extends('layout.admin.template')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
  .config-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 25px;
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
  }
  .config-header h3 { font-weight: 700; margin-bottom: 5px; font-size: 1.75rem; }
  .config-header h5 { font-weight: 400; opacity: 0.9; font-size: 1rem; }

  /* Modal lebih lebar untuk face scan */
  #modal-inputkaryawan .modal-dialog { max-width: 580px; }

  /* ── Face Capture Section (sama persis dengan register) ── */
  .face-capture-section {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 12px;
    padding: 20px;
    margin-top: 4px;
    border: 2px solid #dee2e6;
    text-align: center;
  }
  .face-capture-section h5 {
    font-size: 15px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 6px;
    text-align: left;
  }
  .face-capture-section p {
    font-size: 12px;
    color: #64748b;
    margin-bottom: 14px;
    text-align: left;
  }

  /* Lingkaran kamera — identik dengan register */
  .face-preview {
    position: relative;
    width: 200px;
    height: 200px;
    margin: 0 auto 16px;
    border-radius: 50%;
    overflow: hidden;
    background: #0f172a;
    border: 4px solid #667eea;
    box-shadow: 0 4px 20px rgba(102, 126, 234, 0.35);
  }
  .face-preview video,
  .face-preview canvas {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    min-width: 100%; min-height: 100%;
    width: auto; height: auto;
    object-fit: cover;
  }
  .face-preview canvas { display: none; }
  .face-overlay {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    border-radius: 50%;
    border: 3px solid rgba(102, 126, 234, 0.6);
    pointer-events: none;
    animation: pulse-ring 2s ease-in-out infinite;
  }
  @keyframes pulse-ring {
    0%, 100% { box-shadow: 0 0 0 0   rgba(102,126,234,0.4); }
    50%       { box-shadow: 0 0 0 10px rgba(102,126,234,0);   }
  }

  /* Tombol kamera — identik dengan register */
  .btn-capture {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border: none;
    padding: 9px 20px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin: 4px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .btn-capture:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102,126,234,.4); }

  /* Status wajah — identik dengan register */
  .face-status {
    margin-top: 12px;
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .face-status.pending { background: #fff3cd; color: #856404; border: 1px solid #ffc107; }
  .face-status.success { background: #d1e7dd; color: #0a3622; border: 1px solid #198754; }
</style>

<div class="container-fluid mt-4 px-3 px-md-4">
  <div class="config-header">
    <h3 class="mb-1"><i class="bi bi-people-fill me-2"></i> Karyawan</h3>
    <h5 class="mb-0">Pengaturan Data Karyawan</h5>
  </div>

  <div class="page-body">
    <div class="container-xl">
      <div class="row">
        <div class="col-12">
          <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
              <h4 class="mb-0 fw-bold">Data Karyawan</h4>
              <a href="#" class="btn btn-light btn-sm fw-semibold" id="btn-tambahkaryawan">
                <i class="bi bi-plus-circle me-1"></i> Tambah Data
              </a>
            </div>

            <div class="card-body">
              <div class="table-responsive">

                <!-- Filter -->
                <form action="/karyawan" method="GET" id="filterForm" class="mb-4">
                  <div class="row g-2 align-items-center">
                    <div class="col-md-2">
                      <label class="form-label fw-semibold">Show entries:</label>
                      <select name="per_page" id="per_page" class="form-select">
                        <option value="5"   {{ request('per_page')=='5'   ?'selected':'' }}>5</option>
                        <option value="10"  {{ request('per_page')=='10'  ?'selected':'' }}>10</option>
                        <option value="25"  {{ request('per_page')=='25'  ?'selected':'' }}>25</option>
                        <option value="50"  {{ request('per_page')=='50'  ?'selected':'' }}>50</option>
                        <option value="all" {{ request('per_page')=='all' ?'selected':'' }}>All</option>
                      </select>
                    </div>
                    <div class="col-md-4">
                      <label class="form-label fw-semibold">Cari Nama:</label>
                      <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                        <input type="text" name="nama_karyawan" class="form-control"
                          placeholder="Cari Nama Karyawan" value="{{ request('nama_karyawan') }}">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <label class="form-label fw-semibold">Departemen:</label>
                      <select name="kode_dept" class="form-select">
                        <option value="">Semua Departemen</option>
                        @foreach ($departemen as $dept)
                          <option value="{{ $dept->kode_dept }}"
                            {{ request('kode_dept')==$dept->kode_dept ? 'selected':'' }}>
                            {{ $dept->nama_dept }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-2 d-grid" style="margin-top:32px;">
                      <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i> Search
                      </button>
                    </div>
                  </div>
                </form>

                <div class="mb-3">
                  <p class="text-muted mb-0">
                    <strong>Total Data:</strong>
                    @if(request('per_page')=='all')
                      Menampilkan semua {{ $karyawan->total() }} data
                    @else
                      Menampilkan {{ $karyawan->firstItem()??0 }} - {{ $karyawan->lastItem()??0 }}
                      dari {{ $karyawan->total() }} data
                    @endif
                  </p>
                </div>

                <!-- Tabel -->
                <table class="table table-hover align-middle text-center">
                  <thead class="table-primary">
                    <tr>
                      <th>No</th><th>NIK</th><th>Nama</th><th>Jabatan</th>
                      <th>No HP</th><th>Foto</th><th>Departemen</th><th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($karyawan as $d)
                      @php $path = Storage::url('uploads/karyawan/'.$d->foto); @endphp
                      <tr>
                        <td>
                          @if(request('per_page')=='all')
                            {{ $loop->iteration }}
                          @else
                            {{ $loop->iteration + $karyawan->firstItem() - 1 }}
                          @endif
                        </td>
                        <td>{{ $d->nik }}</td>
                        <td class="fw-semibold">{{ $d->nama_lengkap }}</td>
                        <td><span class="badge bg-info px-3 py-2">{{ $d->jabatan }}</span></td>
                        <td>{{ $d->no_hp }}</td>
                        <td>
                          @if(empty($d->foto))
                            <img src="{{ asset('assets/img/image.png') }}"
                              class="rounded-circle border shadow-sm"
                              style="width:50px;height:50px;object-fit:cover;">
                          @else
                            <img src="{{ url($path) }}"
                              class="rounded-circle border shadow-sm"
                              style="width:50px;height:50px;object-fit:cover;">
                          @endif
                        </td>
                        <td><span class="badge bg-success px-3 py-2">{{ $d->nama_dept }}</span></td>
                        <td>
                          <div class="d-flex justify-content-center gap-2">
                            <a href="javascript:void(0)" class="edit btn btn-info btn-sm"
                              data-nik="{{ $d->nik }}" title="Edit">
                              <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="/karyawan/{{ $d->nik }}/delete" method="POST" class="form-delete">
                              @csrf
                              <button type="button" class="btn btn-sm btn-danger delete-confirm">
                                <i class="bi bi-trash-fill"></i>
                              </button>
                            </form>
                          </div>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="8" class="text-center py-4">
                          <i class="bi bi-inbox" style="font-size:3rem;color:#ccc;"></i>
                          <p class="mt-2 text-muted">Tidak ada data karyawan</p>
                        </td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>

                @if(request('per_page')!='all')
                  <div class="d-flex justify-content-center mt-3">
                    {{ $karyawan->appends(request()->query())->links('pagination::bootstrap-5') }}
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ============================================================ -->
<!-- MODAL TAMBAH KARYAWAN + FACE SCAN                           -->
<!-- ============================================================ -->
<div class="modal fade" id="modal-inputkaryawan" tabindex="-1" aria-hidden="true"
  data-bs-backdrop="false" data-bs-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow-lg rounded-3">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold">
          <i class="bi bi-person-plus-fill me-2"></i> Tambah Data Karyawan
        </h5>
        <button type="button" class="btn-close btn-close-white" id="btn-close-tambah"
          data-bs-dismiss="modal"></button>
      </div>

      <form method="POST" enctype="multipart/form-data" id="frmkaryawan">
        @csrf
        <!-- Hidden input hasil scan wajah (base64) — sama dengan register -->
        <input type="hidden" id="face_data" name="face_data">

        <div class="modal-body" style="max-height:72vh;overflow-y:auto;">
          <div class="row g-3">

            <!-- NIK -->
            <div class="col-12">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                <input type="text" name="nik" id="m_nik" class="form-control"
                  placeholder="Masukkan NIK" autocomplete="off">
              </div>
            </div>

            <!-- Nama -->
            <div class="col-12">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" name="nama_lengkap" id="m_nama" class="form-control"
                  placeholder="Nama Lengkap" autocomplete="off">
              </div>
            </div>

            <!-- Jabatan -->
            <div class="col-12">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-briefcase"></i></span>
                <input type="text" name="jabatan" id="m_jabatan" class="form-control"
                  placeholder="Jabatan" autocomplete="off">
              </div>
            </div>

            <!-- No HP -->
            <div class="col-12">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                <input type="text" name="no_hp" id="m_nohp" class="form-control"
                  placeholder="Nomor HP" autocomplete="off">
              </div>
            </div>

            <!-- Password -->
            <div class="col-12">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" id="m_password" class="form-control"
                  placeholder="Password (min. 6 karakter)" minlength="6">
              </div>
              <small class="text-muted">Password akan di-hash otomatis sebelum disimpan.</small>
            </div>

            <!-- Departemen -->
            <div class="col-12">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-diagram-3"></i></span>
                <select name="kode_dept" id="m_dept" class="form-select">
                  <option value="">Pilih Departemen</option>
                  @foreach ($departemen as $dept)
                    <option value="{{ $dept->kode_dept }}">{{ $dept->nama_dept }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <!-- Foto Profil (opsional) -->
            <div class="col-12">
              <label class="form-label fw-semibold mb-1">
                <i class="bi bi-image me-1"></i> Foto Profil
                <small class="text-muted fw-normal">(opsional — jika kosong, foto wajah digunakan)</small>
              </label>
              <div class="d-flex align-items-center gap-3">
                <img id="Foto" src="{{ asset('assets/img/image.png') }}"
                  class="rounded-circle border shadow-sm"
                  style="width:60px;height:60px;object-fit:cover;flex-shrink:0;">
                <div class="flex-grow-1">
                  <input type="file" name="foto" id="m_foto" class="form-control" accept="image/*">
                  <small class="text-muted">JPG/PNG, maks 2MB.</small>
                </div>
              </div>
            </div>

            <!-- ===================================================== -->
            <!-- FACE CAPTURE — identik dengan register.blade.php       -->
            <!-- ===================================================== -->
            <div class="col-12">
              <div class="face-capture-section">
                <h5>
                  <i class="bi bi-camera-fill me-2"></i>Verifikasi Wajah
                  <span class="badge bg-danger ms-1" style="font-size:10px;vertical-align:middle;">Wajib</span>
                </h5>
                <p>Posisikan wajah dalam lingkaran dan pastikan pencahayaan cukup</p>

                <div class="face-preview">
                  <video id="m_camera" autoplay playsinline></video>
                  <canvas id="m_canvas"></canvas>
                  <div class="face-overlay"></div>
                </div>

                <div>
                  <button type="button" class="btn-capture" id="m_startCamera">
                    <i class="bi bi-camera-video me-2"></i>Nyalakan Kamera
                  </button>
                  <button type="button" class="btn-capture" id="m_captureBtn" style="display:none;">
                    <i class="bi bi-camera me-2"></i>Ambil Foto
                  </button>
                  <button type="button" class="btn-capture" id="m_retakeBtn" style="display:none;">
                    <i class="bi bi-arrow-clockwise me-2"></i>Foto Ulang
                  </button>
                </div>

                <div class="face-status pending" id="m_faceStatus">
                  <i class="bi bi-exclamation-circle me-2"></i>Belum diverifikasi
                </div>
              </div>
            </div>
            <!-- ===================================================== -->

          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id="btn-cancel-tambah" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Tutup
          </button>
          <!-- Disabled sampai wajah diverifikasi — sama dengan register -->
          <button type="submit" class="btn btn-primary" id="m_submitBtn" disabled>
            <i class="bi bi-save2 me-1"></i> Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL EDIT -->
<div class="modal fade" id="modal-editkaryawan" tabindex="-1" aria-hidden="true"
  data-bs-backdrop="false" data-bs-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow-lg rounded-3">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold">
          <i class="bi bi-pencil-square me-2"></i> Edit Data Karyawan
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="loadeditform"></div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const defaultPreview = "{{ asset('assets/img/image.png') }}";
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  const modalTambahEl = document.getElementById('modal-inputkaryawan');
  const modalEditEl   = document.getElementById('modal-editkaryawan');
  const bsModalTambah = new bootstrap.Modal(modalTambahEl, {});
  const bsModalEdit   = new bootstrap.Modal(modalEditEl, {});

  // ── Filter per_page ──
  document.getElementById('per_page')?.addEventListener('change', () => {
    document.getElementById('filterForm').submit();
  });

  // ── Buka modal tambah ──
  document.getElementById('btn-tambahkaryawan').addEventListener('click', function (e) {
    e.preventDefault();
    resetFormTambah();
    bsModalTambah.show();
  });

  // ── Preview foto profil ──
  document.getElementById('m_foto').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = ev => document.getElementById('Foto').src = ev.target.result;
      reader.readAsDataURL(file);
    } else {
      document.getElementById('Foto').src = defaultPreview;
    }
  });

  // ============================================================
  // FACE SCAN — logika identik dengan register.blade.php
  // ============================================================
  let stream       = null;
  let faceVerified = false;

  const camera        = document.getElementById('m_camera');
  const canvas        = document.getElementById('m_canvas');
  const ctx           = canvas.getContext('2d');
  const startCameraBtn= document.getElementById('m_startCamera');
  const captureBtn    = document.getElementById('m_captureBtn');
  const retakeBtn     = document.getElementById('m_retakeBtn');
  const faceStatus    = document.getElementById('m_faceStatus');
  const submitBtn     = document.getElementById('m_submitBtn');
  const faceDataInput = document.getElementById('face_data');

  // Nyalakan Kamera
  startCameraBtn.addEventListener('click', async function () {
    try {
      stream = await navigator.mediaDevices.getUserMedia({
        video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } }
      });
      camera.srcObject = stream;
      camera.style.display     = 'block';
      canvas.style.display     = 'none';
      startCameraBtn.style.display = 'none';
      captureBtn.style.display = 'inline-flex';
      retakeBtn.style.display  = 'none';
    } catch (err) {
      Swal.fire({
        icon: 'error',
        title: 'Kamera Tidak Bisa Dibuka',
        text: 'Pastikan browser diberi izin akses kamera.',
        confirmButtonColor: '#667eea'
      });
    }
  });

  // Ambil Foto
  captureBtn.addEventListener('click', function () {
    canvas.width  = camera.videoWidth;
    canvas.height = camera.videoHeight;
    ctx.drawImage(camera, 0, 0);

    const imageData     = canvas.toDataURL('image/jpeg', 0.8);
    faceDataInput.value = imageData;

    camera.style.display     = 'none';
    canvas.style.display     = 'block';
    captureBtn.style.display = 'none';
    retakeBtn.style.display  = 'inline-flex';

    faceVerified = true;
    faceStatus.className = 'face-status success';
    faceStatus.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>Wajah terverifikasi';

    // Enable tombol Simpan — sama seperti register
    submitBtn.disabled = false;

    // Matikan kamera setelah capture
    stopCamera();
  });

  // Foto Ulang
  retakeBtn.addEventListener('click', function () {
    faceVerified        = false;
    faceDataInput.value = '';
    submitBtn.disabled  = true;

    faceStatus.className = 'face-status pending';
    faceStatus.innerHTML = '<i class="bi bi-exclamation-circle me-2"></i>Belum diverifikasi';

    canvas.style.display = 'none';
    retakeBtn.style.display = 'none';

    // Nyalakan kamera ulang — sama seperti register (startCamera.click())
    startCameraBtn.click();
  });

  function stopCamera() {
    if (stream) {
      stream.getTracks().forEach(t => t.stop());
      stream = null;
    }
  }

  // Stop kamera saat modal ditutup
  document.getElementById('btn-close-tambah')?.addEventListener('click', stopCamera);
  document.getElementById('btn-cancel-tambah')?.addEventListener('click', stopCamera);
  modalTambahEl.addEventListener('hidden.bs.modal', stopCamera);

  function resetFormTambah() {
    document.getElementById('frmkaryawan').reset();
    document.getElementById('Foto').src = defaultPreview;
    faceDataInput.value  = '';
    faceVerified         = false;
    submitBtn.disabled   = true;
    stopCamera();

    // Reset UI face scan
    camera.style.display         = 'none';
    canvas.style.display         = 'none';
    startCameraBtn.style.display = 'inline-flex';
    captureBtn.style.display     = 'none';
    retakeBtn.style.display      = 'none';
    faceStatus.className = 'face-status pending';
    faceStatus.innerHTML = '<i class="bi bi-exclamation-circle me-2"></i>Belum diverifikasi';

    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
  }

  // ============================================================
  // SUBMIT — dengan validasi wajah wajib
  // ============================================================
  document.getElementById('frmkaryawan').addEventListener('submit', async function (e) {
    e.preventDefault();

    const checks = [
      ['m_nik',      'NIK wajib diisi.'],
      ['m_nama',     'Nama Lengkap wajib diisi.'],
      ['m_jabatan',  'Jabatan wajib diisi.'],
      ['m_nohp',     'Nomor HP wajib diisi.'],
      ['m_password', 'Password wajib diisi.'],
      ['m_dept',     'Silakan pilih Departemen.'],
    ];

    for (let [id, msg] of checks) {
      const field = document.getElementById(id);
      if (!field || !field.value.toString().trim()) {
        field?.classList.add('is-invalid');
        Swal.fire({ icon: 'warning', title: 'Form Belum Lengkap', text: msg })
          .then(() => field?.focus());
        return;
      }
      field.classList.remove('is-invalid');
    }

    // Pengaman ekstra wajah (tombol seharusnya sudah disabled)
    if (!faceVerified || !faceDataInput.value) {
      Swal.fire({
        icon: 'warning',
        title: 'Verifikasi Wajah Belum Dilakukan',
        text: 'Harap verifikasi wajah karyawan terlebih dahulu!',
        confirmButtonColor: '#667eea'
      });
      return;
    }

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

    try {
      const response = await fetch('/karyawan/store', {
        method: 'POST',
        body: new FormData(this),
        headers: { 'X-CSRF-TOKEN': csrfToken }
      });

      if (response.ok || response.redirected) {
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: 'Data karyawan berhasil disimpan!',
          timer: 2000,
          showConfirmButton: false
        });
        bsModalTambah.hide();
        resetFormTambah();
        setTimeout(() => location.reload(), 1200);
      } else {
        throw new Error('Server error');
      }
    } catch (err) {
      Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan saat menyimpan data!' });
      submitBtn.disabled = false;
      submitBtn.innerHTML = '<i class="bi bi-save2 me-1"></i> Simpan';
    }
  });

  // ── Edit ──
  document.addEventListener('click', function (e) {
    const el = e.target.closest('.edit');
    if (!el) return;
    e.preventDefault();
    const nik = el.getAttribute('data-nik');
    if (!nik) return;

    const fd = new FormData();
    fd.append('_token', csrfToken);
    fd.append('nik', nik);

    fetch('/karyawan/edit', { method: 'POST', body: fd })
      .then(r => r.text())
      .then(html => {
        document.getElementById('loadeditform').innerHTML = html;
        bsModalEdit.show();
      })
      .catch(() => Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat memuat form edit.' }));
  });

  // ── Hapus ──
  document.addEventListener('click', function (e) {
    const el = e.target.closest('.delete-confirm');
    if (!el) return;
    e.preventDefault();
    const form = el.closest('form');
    Swal.fire({
      title: 'Anda Yakin?',
      text: 'Data yang dihapus tidak dapat dikembalikan!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal',
    }).then(result => {
      if (result.isConfirmed) form.submit();
      else Swal.fire({ icon: 'info', title: 'Dibatalkan', text: 'Data aman 😊' });
    });
  });

  // ── Session Flash ──
  @if(session('success'))
    Swal.fire({ icon:'success', title:'Berhasil', text:{!! json_encode(session('success')) !!}, timer:2200, showConfirmButton:false });
  @endif
  @if(session('warning'))
    Swal.fire({ icon:'warning', title:'Peringatan', text:{!! json_encode(session('warning')) !!}, timer:2200, showConfirmButton:false });
  @endif
});
</script>

@endsection