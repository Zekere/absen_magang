@extends('layout.presensi')

@section('header')
<div class="appHeader bg-primary text-light shadow-sm">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle fw-semibold">Buat Pengajuan Izin</div>
    <div class="right">
        <ion-icon name="document-text-outline" style="font-size: 24px;"></ion-icon>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid" style="margin-top: 80px; margin-bottom: 100px; padding: 0 16px;">
    
    {{-- Info Card --}}
    <div class="info-card mb-3">
        <div class="info-icon">
            <ion-icon name="information-circle"></ion-icon>
        </div>
        <div class="info-content">
            <h6 class="mb-1 fw-bold">Informasi Pengajuan</h6>
            <p class="mb-0 small">Pastikan mengisi semua data dengan benar. Pengajuan akan diproses maksimal 1x24 jam.</p>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="form-card">
        <form method="POST" action="{{ url('/presensi/storeizin') }}" id="frmIzin" enctype="multipart/form-data">
            @csrf
            
            {{-- Tanggal Izin --}}
            <div class="form-group-modern">
                <label class="form-label">
                    <ion-icon name="calendar-outline"></ion-icon>
                    Tanggal Izin
                </label>
                <div class="input-wrapper">
                    <input type="date" 
                           id="tgl_izin" 
                           name="tgl_izin" 
                           class="form-control-modern" 
                           required
                           min="{{ date('Y-m-d') }}">
                </div>
            </div>

            {{-- Status --}}
            <div class="form-group-modern">
                <label class="form-label">
                    <ion-icon name="layers-outline"></ion-icon>
                    Jenis Pengajuan
                </label>
                <div class="radio-group">
                    <label class="radio-card">
                        <input type="radio" name="status" value="1" required>
                        <div class="radio-content">
                            <div class="radio-icon izin">
                                <ion-icon name="calendar-outline"></ion-icon>
                            </div>
                            <div class="radio-text">
                                <div class="radio-title">Izin</div>
                                <div class="radio-desc">Tidak dapat hadir</div>
                            </div>
                            <div class="radio-check">
                                <ion-icon name="checkmark-circle"></ion-icon>
                            </div>
                        </div>
                    </label>

                    <label class="radio-card">
                        <input type="radio" name="status" value="2" required>
                        <div class="radio-content">
                            <div class="radio-icon sakit">
                                <ion-icon name="medkit-outline"></ion-icon>
                            </div>
                            <div class="radio-text">
                                <div class="radio-title">Sakit</div>
                                <div class="radio-desc">Kondisi tidak sehat</div>
                            </div>
                            <div class="radio-check">
                                <ion-icon name="checkmark-circle"></ion-icon>
                            </div>
                        </div>
                    </label>

                    <label class="radio-card">
                        <input type="radio" name="status" value="3" required>
                        <div class="radio-content">
                            <div class="radio-icon cuti">
                                <ion-icon name="airplane-outline"></ion-icon>
                            </div>
                            <div class="radio-text">
                                <div class="radio-title">Cuti</div>
                                <div class="radio-desc">Libur terencana</div>
                            </div>
                            <div class="radio-check">
                                <ion-icon name="checkmark-circle"></ion-icon>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Keterangan --}}
            <div class="form-group-modern">
                <label class="form-label">
                    <ion-icon name="create-outline"></ion-icon>
                    Keterangan
                </label>
                <div class="input-wrapper">
                    <textarea name="keterangan" 
                              id="keterangan" 
                              class="form-control-modern textarea" 
                              rows="4" 
                              placeholder="Jelaskan alasan pengajuan Anda..."
                              required></textarea>
                    <div class="char-counter">
                        <span id="charCount">0</span>/200 karakter
                    </div>
                </div>
            </div>

            {{-- Upload Bukti --}}
            <div class="form-group-modern">
                <label class="form-label">
                    <ion-icon name="attach-outline"></ion-icon>
                    Lampiran Bukti <span class="optional">(opsional)</span>
                </label>
                <div class="upload-area" id="uploadArea">
                    <input type="file" 
                           name="bukti_surat" 
                           id="bukti_surat" 
                           accept="image/*,.pdf,.doc,.docx"
                           hidden>
                    <div class="upload-content" id="uploadContent">
                        <div class="upload-icon">
                            <ion-icon name="cloud-upload-outline"></ion-icon>
                        </div>
                        <div class="upload-text">
                            <div class="upload-title">Klik untuk upload file</div>
                            <div class="upload-desc">PNG, JPG, PDF, DOC (Max. 5MB)</div>
                        </div>
                    </div>
                </div>

                {{-- File Preview --}}
                <div class="file-preview-card" id="filePreviewCard" style="display: none;">
                    <div class="file-preview-image" id="imagePreviewContainer" style="display: none;">
                        <img id="previewImg" src="" alt="Preview">
                    </div>
                    <div class="file-info-modern">
                        <div class="file-icon" id="fileIcon">
                            <ion-icon name="document-outline"></ion-icon>
                        </div>
                        <div class="file-details">
                            <div class="file-name" id="fileName"></div>
                            <div class="file-meta">
                                <span id="fileSize"></span> • <span id="fileType"></span>
                            </div>
                        </div>
                        <button type="button" class="file-remove" id="removeFile">
                            <ion-icon name="close-circle"></ion-icon>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="form-group-modern">
                <button type="submit" class="btn-submit">
                    <ion-icon name="send-outline"></ion-icon>
                    Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* ===== Info Card ===== */
.info-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 16px;
    display: flex;
    gap: 12px;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
}

.info-icon {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.info-icon ion-icon {
    font-size: 24px;
    color: white;
}

.info-content {
    flex: 1;
}

.info-content h6 {
    color: white;
    font-size: 15px;
    margin: 0;
}

.info-content p {
    color: rgba(255, 255, 255, 0.9);
    font-size: 13px;
    line-height: 1.4;
}

/* ===== Form Card ===== */
.form-card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
}

/* ===== Form Group ===== */
.form-group-modern {
    margin-bottom: 24px;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 10px;
}

.form-label ion-icon {
    font-size: 18px;
    color: #3b82f6;
}

.optional {
    font-size: 12px;
    font-weight: 400;
    color: #6b7280;
}

/* ===== Input Modern ===== */
.input-wrapper {
    position: relative;
}

.form-control-modern {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 14px;
    color: #1a1a1a;
    transition: all 0.3s ease;
    background: #f9fafb;
}

.form-control-modern:focus {
    outline: none;
    border-color: #3b82f6;
    background: white;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-control-modern.textarea {
    resize: none;
    font-family: inherit;
}

.char-counter {
    text-align: right;
    font-size: 12px;
    color: #6b7280;
    margin-top: 6px;
}

/* ===== Radio Cards ===== */
.radio-group {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.radio-card {
    position: relative;
    cursor: pointer;
    display: block;
}

.radio-card input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.radio-content {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    background: #f9fafb;
    transition: all 0.3s ease;
}

.radio-card input[type="radio"]:checked ~ .radio-content {
    border-color: #3b82f6;
    background: #eff6ff;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.radio-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.radio-icon ion-icon {
    font-size: 24px;
    color: white;
}

.radio-icon.izin {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
}

.radio-icon.sakit {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.radio-icon.cuti {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
}

.radio-text {
    flex: 1;
}

.radio-title {
    font-size: 15px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 2px;
}

.radio-desc {
    font-size: 13px;
    color: #6b7280;
}

.radio-check {
    opacity: 0;
    transition: all 0.3s ease;
}

.radio-check ion-icon {
    font-size: 24px;
    color: #3b82f6;
}

.radio-card input[type="radio"]:checked ~ .radio-content .radio-check {
    opacity: 1;
    transform: scale(1);
}

/* ===== Upload Area ===== */
.upload-area {
    border: 2px dashed #d1d5db;
    border-radius: 12px;
    padding: 32px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f9fafb;
}

.upload-area:hover {
    border-color: #3b82f6;
    background: #eff6ff;
}

.upload-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 12px;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.upload-icon ion-icon {
    font-size: 32px;
    color: white;
}

.upload-title {
    font-size: 15px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 4px;
}

.upload-desc {
    font-size: 13px;
    color: #6b7280;
}

/* ===== File Preview ===== */
.file-preview-card {
    margin-top: 16px;
    border-radius: 12px;
    overflow: hidden;
    border: 2px solid #e5e7eb;
}

.file-preview-image {
    width: 100%;
    height: 200px;
    overflow: hidden;
    background: #f3f4f6;
}

.file-preview-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.file-info-modern {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: white;
}

.file-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.file-icon ion-icon {
    font-size: 24px;
    color: white;
}

.file-details {
    flex: 1;
}

.file-name {
    font-size: 14px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 4px;
    word-break: break-word;
}

.file-meta {
    font-size: 12px;
    color: #6b7280;
}

.file-remove {
    width: 36px;
    height: 36px;
    border: none;
    background: #fee2e2;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.file-remove ion-icon {
    font-size: 20px;
    color: #ef4444;
}

.file-remove:hover {
    background: #fecaca;
    transform: scale(1.1);
}

/* ===== Submit Button ===== */
.btn-submit {
    width: 100%;
    padding: 16px;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
}

.btn-submit:active {
    transform: translateY(0);
}

.btn-submit ion-icon {
    font-size: 20px;
}

/* ===== Responsive ===== */
@media (max-width: 576px) {
    .form-card {
        padding: 16px;
    }
    
    .upload-area {
        padding: 24px 16px;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('frmIzin');
    const fileInput = document.getElementById('bukti_surat');
    const uploadArea = document.getElementById('uploadArea');
    const filePreviewCard = document.getElementById('filePreviewCard');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const removeFileBtn = document.getElementById('removeFile');
    const keterangan = document.getElementById('keterangan');
    const charCount = document.getElementById('charCount');

    // Character counter
    keterangan.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = length;
        
        if (length > 200) {
            this.value = this.value.substring(0, 200);
            charCount.textContent = 200;
        }
    });

    // Upload area click
    uploadArea.addEventListener('click', function() {
        fileInput.click();
    });

    // File upload handler
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            // Validasi ukuran (max 5MB)
            const maxSize = 5 * 1024 * 1024;
            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar',
                    text: 'Ukuran file maksimal 5MB!',
                    confirmButtonColor: '#3b82f6'
                });
                fileInput.value = '';
                return;
            }

            // Validasi tipe file
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            if (!allowedTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Tipe File Tidak Valid',
                    text: 'Hanya file gambar, PDF, atau Word yang diperbolehkan!',
                    confirmButtonColor: '#3b82f6'
                });
                fileInput.value = '';
                return;
            }

            // Tampilkan preview
            showFilePreview(file);
        }
    });

    // Show file preview
    function showFilePreview(file) {
        uploadArea.style.display = 'none';
        filePreviewCard.style.display = 'block';

        // Set file info
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileSize').textContent = (file.size / 1024).toFixed(2) + ' KB';
        document.getElementById('fileType').textContent = file.type.split('/')[1].toUpperCase();

        // Preview image jika file adalah gambar
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                imagePreviewContainer.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            imagePreviewContainer.style.display = 'none';
        }
    }

    // Remove file
    removeFileBtn.addEventListener('click', function() {
        fileInput.value = '';
        uploadArea.style.display = 'block';
        filePreviewCard.style.display = 'none';
        imagePreviewContainer.style.display = 'none';
    });

    // Form validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const tgl_izin = document.getElementById('tgl_izin').value;
        const status = document.querySelector('input[name="status"]:checked');
        const keterangan = document.getElementById('keterangan').value.trim();

        if (!tgl_izin) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops!',
                text: 'Tanggal tidak boleh kosong!',
                confirmButtonColor: '#3b82f6'
            });
            return;
        }

        if (!status) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops!',
                text: 'Pilih jenis pengajuan terlebih dahulu!',
                confirmButtonColor: '#3b82f6'
            });
            return;
        }

        if (!keterangan) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops!',
                text: 'Keterangan tidak boleh kosong!',
                confirmButtonColor: '#3b82f6'
            });
            return;
        }

        // Show loading
        Swal.fire({
            title: 'Mengirim pengajuan...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Submit form
        form.submit();
    });
});
</script>
@endsection