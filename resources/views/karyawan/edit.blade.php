<form action="/karyawan/{{ $karyawan->nik }}/update" method="POST" enctype="multipart/form-data" id="frmkaryawan">
  @csrf
  <div class="modal-body">
    <div class="row g-3">

      <!-- NIK -->
      <div class="col-12">
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
          <input type="text" name="nik" readonly value="{{ $karyawan->nik }}" id="nik" class="form-control" placeholder="Masukkan NIK" autocomplete="off">
        </div>
      </div>

      <!-- Nama Lengkap -->
      <div class="col-12">
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-person"></i></span>
          <input type="text" name="nama_lengkap" value="{{ $karyawan->nama_lengkap }}" id="nama_lengkap" class="form-control" placeholder="Nama Lengkap" autocomplete="off">
        </div>
      </div>

      <!-- Jabatan -->
      <div class="col-12">
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-briefcase"></i></span>
          <input type="text" name="jabatan" value="{{ $karyawan->jabatan }}" id="jabatan" class="form-control" placeholder="Jabatan" autocomplete="off">
        </div>
      </div>

      <!-- No HP -->
      <div class="col-12">
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-telephone"></i></span>
          <input type="text" name="no_hp" value="{{ $karyawan->no_hp }}" id="no_hp" class="form-control" placeholder="Nomor HP" autocomplete="off">
        </div>
      </div>

      <!-- Password -->
      <div class="col-12">
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-lock"></i></span>
          <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan Password (opsional)">
        </div>
        <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
      </div>

      <!-- Foto -->
      <div class="col-12">
        <label for="foto" class="form-label fw-semibold mb-1">
          <i class="bi bi-image me-1"></i> Upload Foto
        </label>
        <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
        <small class="text-muted">Format: JPG, JPEG, PNG | Maksimal 2MB</small>
      </div>

      <!-- Preview Foto -->
      <div class="col-12 text-center mt-2">
        <img id="previewFoto"
          src="{{ $karyawan->foto ? asset('storage/uploads/karyawan/'.$karyawan->foto) : asset('assets/img/image.png') }}"
          alt="Preview Foto"
          class="rounded-circle border shadow-sm"
          style="width: 80px; height: 80px; object-fit: cover;">
      </div>

      <!-- Departemen -->
      <div class="col-12">
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-diagram-3"></i></span>
          <select name="kode_dept" id="kode_dept_form" class="form-select">
            <option value="">Pilih Departemen</option>
            @foreach ($departemen as $dept)
              <option value="{{ $dept->kode_dept }}" {{ $karyawan->kode_dept == $dept->kode_dept ? 'selected' : '' }}>
                {{ $dept->nama_dept }}
              </option>
            @endforeach
          </select>
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