<form method="POST" action="/admin/{{ $admin->id }}/update" id="frmEditAdmin">
  @csrf
  <div class="row g-3">

    <!-- Nama -->
    <div class="col-12">
      <div class="input-group">
        <span class="input-group-text"><i class="bi bi-person"></i></span>
        <input type="text" name="name" id="name_edit" class="form-control" placeholder="Nama Lengkap" value="{{ $admin->name }}" autocomplete="off">
      </div>
    </div>

    <!-- Email -->
    <div class="col-12">
      <div class="input-group">
        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
        <input type="email" name="email" id="email_edit" class="form-control" placeholder="Email" value="{{ $admin->email }}" autocomplete="off">
      </div>
    </div>

    <!-- Password -->
    <div class="col-12">
      <div class="input-group">
        <span class="input-group-text"><i class="bi bi-lock"></i></span>
        <input type="password" name="password" id="password_edit" class="form-control" placeholder="Masukkan Password Baru (Kosongkan jika tidak diubah)">
      </div>
      <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
    </div>

  </div>

  <div class="modal-footer mt-3">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1"></i> Tutup</button>
    <button type="submit" class="btn btn-primary"><i class="bi bi-save2 me-1"></i> Update</button>
  </div>
</form>