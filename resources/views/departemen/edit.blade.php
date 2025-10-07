<form action="/departemen/{{ $departemen->kode_dept }}/update" method="POST" id="frmkaryawan">
  @csrf
  <div class="modal-body">
    <div class="row g-3">

      <!-- NIK -->
      <div class="col-12">
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
          <input type="text" name="kode_dept"  value="{{ $departemen->kode_dept }}" id="kode_dept" class="form-control" placeholder="Masukkan NIK" autocomplete="off" readonly>
        </div>
      </div>

      <!-- Nama Lengkap -->
      <div class="col-12">
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-person"></i></span>
          <input type="text" name="nama_dept" value="{{ $departemen->nama_dept }}" id="nama_dept" class="form-control" placeholder="Masukkan Nama Departemen" autocomplete="off">
        </div>
      </div>

      <!-- Jabatan -->
     

  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
      <i class="bi bi-x-circle me-1"></i> Tutup
    </button>
    <button type="submit" class="btn btn-primary">
      <i class="bi bi-save2 me-1"></i> Simpan
    </button>
  </div>
</form>