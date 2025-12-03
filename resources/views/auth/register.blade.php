<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Register E-Presensi</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('assets/img/icon/puprlogo.png') }}" sizes="32x32">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/icon/puprlogo.png') }}">
  
  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/register.css') }}">
</head>
<body>
  <div class="register-container">
    <div class="register-card">
      <h2 class="register-title">Buat Akun Baru</h2>
      <p class="register-subtitle">Lengkapi data diri dan verifikasi wajah Anda</p>

      <!-- Alert -->
      <div id="alertContainer"></div>

      <form id="registerForm" method="POST" enctype="multipart/form-data">
        <!-- NIK -->
        <div class="mb-3">
          <label for="nik" class="form-label">NIK <span class="required">*</span></label>
          <div class="input-group">
            <input type="text" class="form-control" id="nik" name="nik" placeholder="Masukkan NIK" required>
            <i class="bi bi-credit-card-fill input-icon"></i>
          </div>
        </div>

        <!-- Nama Lengkap -->
        <div class="mb-3">
          <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="required">*</span></label>
          <div class="input-group">
            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan nama lengkap" required>
            <i class="bi bi-person-fill input-icon"></i>
          </div>
        </div>

        <!-- Jabatan -->
        <div class="mb-3">
          <label for="jabatan" class="form-label">Jabatan <span class="required">*</span></label>
          <div class="input-group">
            <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="Masukkan jabatan" required>
            <i class="bi bi-briefcase-fill input-icon"></i>
          </div>
        </div>

        <!-- Nomor HP -->
        <div class="mb-3">
          <label for="no_hp" class="form-label">Nomor HP <span class="required">*</span></label>
          <div class="input-group">
            <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="Masukkan nomor HP" required>
            <i class="bi bi-telephone-fill input-icon"></i>
          </div>
        </div>

        <!-- Password -->
        <div class="mb-3">
          <label for="password" class="form-label">Password <span class="required">*</span></label>
          <div class="input-group">
            <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 6 karakter" required minlength="6">
            <i class="bi bi-lock-fill input-icon"></i>
          </div>
        </div>

        <!-- Konfirmasi Password -->
        <div class="mb-3">
          <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="required">*</span></label>
          <div class="input-group">
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" required minlength="6">
            <i class="bi bi-lock-fill input-icon"></i>
          </div>
        </div>

        <!-- Departemen -->
        <div class="mb-3">
          <label for="kode_dept" class="form-label">Departemen <span class="required">*</span></label>
          <div class="input-group">
            <select class="form-control" id="kode_dept" name="kode_dept" required>
              <option value="">Pilih Departemen</option>
              @foreach($departemen as $dept)
                <option value="{{ $dept->kode_dept }}">{{ $dept->nama_dept }}</option>
              @endforeach
            </select>
            <i class="bi bi-building input-icon"></i>
          </div>
        </div>

        <!-- Upload Foto Profile -->
        <div class="mb-3">
          <label for="foto_profile" class="form-label">Foto Profil</label>
          <div class="upload-section">
            <div class="upload-preview" id="previewContainer">
              <img id="preview" src="" alt="Preview" style="display:none;">
              <div class="upload-placeholder" id="placeholder">
                <i class="bi bi-cloud-upload"></i>
                <p>Klik untuk upload foto profil</p>
                <small>Format: JPG, JPEG, PNG (Max 2MB)</small>
              </div>
            </div>
            <input type="file" class="form-control" id="foto_profile" name="foto_profile" accept="image/jpeg,image/jpg,image/png" style="display:none;">
            <button type="button" class="btn-upload" id="uploadBtn">
              <i class="bi bi-image me-2"></i>Pilih Foto Profil
            </button>
            <button type="button" class="btn-upload" id="removePhotoBtn" style="display:none; background:#dc3545; color: white;">
              <i class="bi bi-trash me-2"></i>Hapus Foto
            </button>
          </div>
        </div>

        <!-- Face Capture Section -->
        <div class="face-capture-section">
          <h5><i class="bi bi-camera-fill me-2"></i>Verifikasi Wajah</h5>
          <p>
            Posisikan wajah Anda dalam lingkaran dan pastikan pencahayaan cukup
          </p>
          
          <div class="face-preview">
            <video id="camera" autoplay playsinline></video>
            <canvas id="canvas"></canvas>
            <div class="face-overlay"></div>
          </div>

          <div>
            <button type="button" class="btn-capture" id="startCamera">
              <i class="bi bi-camera-video me-2"></i>Nyalakan Kamera
            </button>
            <button type="button" class="btn-capture" id="captureBtn" style="display:none;">
              <i class="bi bi-camera me-2"></i>Ambil Foto
            </button>
            <button type="button" class="btn-capture" id="retakeBtn" style="display:none;">
              <i class="bi bi-arrow-clockwise me-2"></i>Foto Ulang
            </button>
          </div>

          <div class="face-status pending" id="faceStatus">
            <i class="bi bi-exclamation-circle me-2"></i>Belum diverifikasi
          </div>
        </div>

        <!-- Hidden input untuk foto wajah -->
        <input type="hidden" id="face_data" name="face_data">

        <!-- Submit Button -->
        <button type="submit" class="btn-register" id="submitBtn" disabled>
          <i class="bi bi-person-plus-fill me-2"></i>Daftar Sekarang
        </button>
      </form>

      <div class="back-to-login">
        <a href="/"><i class="bi bi-arrow-left me-2"></i>Kembali ke Login</a>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

  <script>
    let stream = null;
    let faceVerified = false;

    const camera = document.getElementById('camera');
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');
    const startCameraBtn = document.getElementById('startCamera');
    const captureBtn = document.getElementById('captureBtn');
    const retakeBtn = document.getElementById('retakeBtn');
    const faceStatus = document.getElementById('faceStatus');
    const submitBtn = document.getElementById('submitBtn');
    const faceDataInput = document.getElementById('face_data');

    // Upload Foto Profil
    const uploadBtn = document.getElementById('uploadBtn');
    const fotoProfileInput = document.getElementById('foto_profile');
    const preview = document.getElementById('preview');
    const placeholder = document.getElementById('placeholder');
    const removePhotoBtn = document.getElementById('removePhotoBtn');

    uploadBtn.addEventListener('click', () => {
      fotoProfileInput.click();
    });

    fotoProfileInput.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (file) {
        // Validasi ukuran file (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
          showAlert('Ukuran file maksimal 2MB!', 'danger');
          fotoProfileInput.value = '';
          return;
        }

        // Validasi tipe file
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!validTypes.includes(file.type)) {
          showAlert('Format file harus JPG, JPEG, atau PNG!', 'danger');
          fotoProfileInput.value = '';
          return;
        }

        // Preview image
        const reader = new FileReader();
        reader.onload = (e) => {
          preview.src = e.target.result;
          preview.style.display = 'block';
          placeholder.style.display = 'none';
          removePhotoBtn.style.display = 'inline-block';
        };
        reader.readAsDataURL(file);
      }
    });

    removePhotoBtn.addEventListener('click', () => {
      fotoProfileInput.value = '';
      preview.src = '';
      preview.style.display = 'none';
      placeholder.style.display = 'block';
      removePhotoBtn.style.display = 'none';
    });

    // Start Camera
    startCameraBtn.addEventListener('click', async () => {
      try {
        stream = await navigator.mediaDevices.getUserMedia({ 
          video: { 
            facingMode: 'user',
            width: { ideal: 640 },
            height: { ideal: 480 }
          } 
        });
        camera.srcObject = stream;
        camera.style.display = 'block';
        canvas.style.display = 'none';
        startCameraBtn.style.display = 'none';
        captureBtn.style.display = 'inline-block';
      } catch (err) {
        showAlert('Tidak dapat mengakses kamera. Pastikan izin kamera diaktifkan.', 'danger');
      }
    });

    // Capture Photo
    captureBtn.addEventListener('click', () => {
      canvas.width = camera.videoWidth;
      canvas.height = camera.videoHeight;
      ctx.drawImage(camera, 0, 0);
      
      const imageData = canvas.toDataURL('image/jpeg', 0.8);
      faceDataInput.value = imageData;
      
      camera.style.display = 'none';
      canvas.style.display = 'block';
      captureBtn.style.display = 'none';
      retakeBtn.style.display = 'inline-block';
      
      faceVerified = true;
      faceStatus.className = 'face-status success';
      faceStatus.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>Wajah terverifikasi';
      submitBtn.disabled = false;
      
      // Stop camera
      if (stream) {
        stream.getTracks().forEach(track => track.stop());
      }
    });

    // Retake Photo
    retakeBtn.addEventListener('click', async () => {
      faceVerified = false;
      faceDataInput.value = '';
      faceStatus.className = 'face-status pending';
      faceStatus.innerHTML = '<i class="bi bi-exclamation-circle me-2"></i>Belum diverifikasi';
      submitBtn.disabled = true;
      
      canvas.style.display = 'none';
      retakeBtn.style.display = 'none';
      startCameraBtn.click();
    });

    // Form Submit
    document.getElementById('registerForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      
      // Validasi password
      const password = document.getElementById('password').value;
      const passwordConfirmation = document.getElementById('password_confirmation').value;
      
      if (password !== passwordConfirmation) {
        showAlert('Password dan konfirmasi password tidak sama!', 'danger');
        return;
      }

      if (!faceVerified) {
        showAlert('Harap verifikasi wajah terlebih dahulu!', 'danger');
        return;
      }

      submitBtn.disabled = true;
      submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mendaftar...';

      const formData = new FormData(e.target);
      
      try {
        const response = await fetch('/prosesregister', {
          method: 'POST',
          body: formData,
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
          }
        });

        const result = await response.json();

        if (result.success) {
          showAlert('Pendaftaran berhasil! Silakan login.', 'success');
          setTimeout(() => {
            window.location.href = '/';
          }, 2000);
        } else {
          showAlert(result.message || 'Pendaftaran gagal!', 'danger');
          submitBtn.disabled = false;
          submitBtn.innerHTML = '<i class="bi bi-person-plus-fill me-2"></i>Daftar Sekarang';
        }
      } catch (error) {
        showAlert('Terjadi kesalahan. Silakan coba lagi.', 'danger');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="bi bi-person-plus-fill me-2"></i>Daftar Sekarang';
      }
    });

    function showAlert(message, type) {
      const alertContainer = document.getElementById('alertContainer');
      alertContainer.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
          <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}-fill me-2"></i>${message}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      `;
    }

    // Stop camera on page unload
    window.addEventListener('beforeunload', () => {
      if (stream) {
        stream.getTracks().forEach(track => track.stop());
      }
    });
  </script>
</body>
</html>