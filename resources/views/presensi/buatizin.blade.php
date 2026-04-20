@extends('layout.presensi')

@section('header')
<div class="appHeader bg-primary text-light shadow-sm">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle fw-semibold">Buat Pengajuan</div>
    <div class="right">
        <ion-icon name="document-text-outline" style="font-size: 22px;"></ion-icon>
    </div>
</div>
@endsection

@section('content')

<div class="buat-wrap">

    {{-- ─── INFO BANNER ─── --}}
    <div class="buat-banner">
        <div class="buat-banner-icon">
            <ion-icon name="information-circle-outline"></ion-icon>
        </div>
        <div class="buat-banner-text">
            <div class="buat-banner-title">Informasi Pengajuan</div>
            <div class="buat-banner-sub">Isi semua data dengan benar. Pengajuan diproses maksimal 1×24 jam.</div>
        </div>
    </div>

    {{-- ─── FORM ─── --}}
    <div class="buat-card">
        <form method="POST" action="{{ url('/presensi/storeizin') }}" id="frmIzin" enctype="multipart/form-data">
            @csrf

            {{-- ── TANGGAL ── --}}
            <div class="buat-field">
                <div class="buat-label">
                    <ion-icon name="calendar-outline"></ion-icon>
                    Tanggal Izin
                </div>
                <input type="date"
                       id="tgl_izin"
                       name="tgl_izin"
                       class="buat-input"
                       min="{{ date('Y-m-d') }}"
                       required>
            </div>

            {{-- ── JENIS PENGAJUAN ── --}}
            <div class="buat-field">
                <div class="buat-label">
                    <ion-icon name="layers-outline"></ion-icon>
                    Jenis Pengajuan
                </div>
                <div class="buat-radio-group">

                    <label class="buat-radio-card" id="rc-izin">
                        <input type="radio" name="status" value="1" required>
                        <div class="buat-radio-inner">
                            <div class="buat-radio-icon ri-izin">
                                <ion-icon name="calendar-outline"></ion-icon>
                            </div>
                            <div class="buat-radio-meta">
                                <div class="buat-radio-title">Izin</div>
                                <div class="buat-radio-sub">Tidak dapat hadir</div>
                            </div>
                            <div class="buat-radio-check">
                                <ion-icon name="checkmark-circle"></ion-icon>
                            </div>
                        </div>
                    </label>

                    <label class="buat-radio-card" id="rc-sakit">
                        <input type="radio" name="status" value="2">
                        <div class="buat-radio-inner">
                            <div class="buat-radio-icon ri-sakit">
                                <ion-icon name="medkit-outline"></ion-icon>
                            </div>
                            <div class="buat-radio-meta">
                                <div class="buat-radio-title">Sakit</div>
                                <div class="buat-radio-sub">Kondisi tidak sehat</div>
                            </div>
                            <div class="buat-radio-check">
                                <ion-icon name="checkmark-circle"></ion-icon>
                            </div>
                        </div>
                    </label>

                    <label class="buat-radio-card" id="rc-cuti">
                        <input type="radio" name="status" value="3">
                        <div class="buat-radio-inner">
                            <div class="buat-radio-icon ri-cuti">
                                <ion-icon name="airplane-outline"></ion-icon>
                            </div>
                            <div class="buat-radio-meta">
                                <div class="buat-radio-title">Cuti</div>
                                <div class="buat-radio-sub">Libur terencana</div>
                            </div>
                            <div class="buat-radio-check">
                                <ion-icon name="checkmark-circle"></ion-icon>
                            </div>
                        </div>
                    </label>

                </div>
            </div>

            {{-- ── KETERANGAN ── --}}
            <div class="buat-field">
                <div class="buat-label">
                    <ion-icon name="create-outline"></ion-icon>
                    Keterangan
                </div>
                <textarea name="keterangan"
                          id="keterangan"
                          class="buat-input buat-textarea"
                          rows="4"
                          placeholder="Jelaskan alasan pengajuan Anda..."
                          required
                          maxlength="200"></textarea>
                <div class="buat-char">
                    <span id="charCount">0</span>/200 karakter
                </div>
            </div>

            {{-- ── UPLOAD BUKTI ── --}}
            <div class="buat-field">
                <div class="buat-label">
                    <ion-icon name="attach-outline"></ion-icon>
                    Lampiran Bukti
                    <span class="buat-optional">opsional</span>
                </div>

                {{-- Area upload --}}
                <div class="buat-upload" id="uploadArea">
                    <input type="file"
                           name="bukti_surat"
                           id="bukti_surat"
                           accept="image/*,.pdf,.doc,.docx"
                           hidden>
                    <div class="buat-upload-icon">
                        <ion-icon name="cloud-upload-outline"></ion-icon>
                    </div>
                    <div class="buat-upload-title">Ketuk untuk upload</div>
                    <div class="buat-upload-sub">PNG, JPG, PDF, DOC · Maks. 5MB</div>
                </div>

                {{-- Preview file --}}
                <div class="buat-preview" id="filePreview" style="display:none;">
                    <div class="buat-preview-img" id="previewImgWrap" style="display:none;">
                        <img id="previewImg" src="" alt="Preview">
                    </div>
                    <div class="buat-preview-info">
                        <div class="buat-preview-file-icon" id="fileIcon">
                            <ion-icon name="document-outline"></ion-icon>
                        </div>
                        <div class="buat-preview-meta">
                            <div class="buat-preview-name" id="fileName"></div>
                            <div class="buat-preview-size"><span id="fileSize"></span> · <span id="fileType"></span></div>
                        </div>
                        <button type="button" class="buat-preview-remove" id="removeFile">
                            <ion-icon name="close-circle"></ion-icon>
                        </button>
                    </div>
                </div>
            </div>

            {{-- ── SUBMIT ── --}}
            <button type="submit" class="buat-submit" id="submitBtn">
                <ion-icon name="send-outline"></ion-icon>
                Kirim Pengajuan
            </button>

        </form>
    </div>
</div>

{{-- ═══ STYLES ═══════════════════════════════════════ --}}
<style>
.buat-wrap {
    padding: 76px 14px 100px;
    max-width: 500px;
    margin: 0 auto;
    font-family: -apple-system, 'Segoe UI', sans-serif;
}

/* ─── BANNER ─── */
.buat-banner {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    background: #185FA5;
    border-radius: 16px;
    padding: 14px 16px;
    margin-bottom: 14px;
}

.buat-banner-icon {
    width: 38px;
    height: 38px;
    background: rgba(255,255,255,0.18);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.buat-banner-icon ion-icon { font-size: 22px; color: #fff; }

.buat-banner-title {
    font-size: 14px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 3px;
}
.buat-banner-sub {
    font-size: 12px;
    color: rgba(255,255,255,0.85);
    line-height: 1.45;
}

/* ─── FORM CARD ─── */
.buat-card {
    background: #fff;
    border-radius: 20px;
    border: 0.5px solid rgba(0,0,0,0.07);
    padding: 20px 18px;
}

/* ─── FIELD ─── */
.buat-field { margin-bottom: 22px; }

.buat-label {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 13px;
    font-weight: 700;
    color: #222;
    margin-bottom: 10px;
}
.buat-label ion-icon { font-size: 17px; color: #185FA5; }

.buat-optional {
    margin-left: 2px;
    font-size: 11px;
    font-weight: 500;
    color: #aaa;
    background: #f3f4f6;
    padding: 2px 8px;
    border-radius: 20px;
}

/* ─── INPUT / TEXTAREA ─── */
.buat-input {
    width: 100%;
    padding: 13px 14px;
    border: 1.5px solid #e5e7eb;
    border-radius: 14px;
    font-size: 14px;
    color: #111;
    background: #f8f9fa;
    font-family: inherit;
    outline: none;
    transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
    box-sizing: border-box;
}

.buat-input:focus {
    border-color: #185FA5;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(24,95,165,0.1);
}

.buat-textarea {
    resize: none;
    line-height: 1.55;
}

.buat-char {
    text-align: right;
    font-size: 11px;
    color: #aaa;
    margin-top: 5px;
}

/* ─── RADIO CARDS ─── */
.buat-radio-group {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.buat-radio-card {
    display: block;
    cursor: pointer;
    position: relative;
}

.buat-radio-card input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.buat-radio-inner {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 14px;
    border: 1.5px solid #e5e7eb;
    border-radius: 16px;
    background: #f8f9fa;
    transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
}

.buat-radio-card input[type="radio"]:checked ~ .buat-radio-inner {
    border-color: #185FA5;
    background: #EBF3FB;
    box-shadow: 0 0 0 3px rgba(24,95,165,0.1);
}

.buat-radio-icon {
    width: 46px;
    height: 46px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.buat-radio-icon ion-icon { font-size: 22px; color: #fff; }

.ri-izin  { background: #185FA5; }
.ri-sakit { background: #A32D2D; }
.ri-cuti  { background: #3B6D11; }

.buat-radio-meta { flex: 1; }
.buat-radio-title { font-size: 14px; font-weight: 700; color: #111; }
.buat-radio-sub   { font-size: 12px; color: #888; margin-top: 2px; }

.buat-radio-check {
    opacity: 0;
    transition: opacity 0.2s, transform 0.2s;
    transform: scale(0.7);
}
.buat-radio-check ion-icon { font-size: 22px; color: #185FA5; }

.buat-radio-card input[type="radio"]:checked ~ .buat-radio-inner .buat-radio-check {
    opacity: 1;
    transform: scale(1);
}

/* ─── UPLOAD ─── */
.buat-upload {
    border: 1.5px dashed #c8d5e0;
    border-radius: 16px;
    padding: 28px 16px;
    text-align: center;
    cursor: pointer;
    background: #f8f9fa;
    transition: border-color 0.2s, background 0.2s;
}

.buat-upload:hover {
    border-color: #185FA5;
    background: #EBF3FB;
}

.buat-upload-icon {
    width: 56px;
    height: 56px;
    background: #185FA5;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
}
.buat-upload-icon ion-icon { font-size: 28px; color: #fff; }

.buat-upload-title { font-size: 14px; font-weight: 700; color: #222; margin-bottom: 3px; }
.buat-upload-sub   { font-size: 12px; color: #aaa; }

/* ─── FILE PREVIEW ─── */
.buat-preview {
    margin-top: 12px;
    border-radius: 16px;
    border: 1.5px solid #e5e7eb;
    overflow: hidden;
}

.buat-preview-img {
    width: 100%;
    height: 180px;
    overflow: hidden;
    background: #f3f4f6;
}
.buat-preview-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.buat-preview-info {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 13px 14px;
    background: #fff;
}

.buat-preview-file-icon {
    width: 42px;
    height: 42px;
    background: #FAEEDA;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.buat-preview-file-icon ion-icon { font-size: 22px; color: #633806; }

.buat-preview-meta { flex: 1; min-width: 0; }
.buat-preview-name {
    font-size: 13px;
    font-weight: 700;
    color: #111;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 3px;
}
.buat-preview-size { font-size: 11px; color: #aaa; }

.buat-preview-remove {
    width: 34px;
    height: 34px;
    background: #FCEBEB;
    border: none;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    transition: background 0.15s;
}
.buat-preview-remove:active { background: #fdd; }
.buat-preview-remove ion-icon { font-size: 20px; color: #A32D2D; }

/* ─── SUBMIT ─── */
.buat-submit {
    width: 100%;
    padding: 15px;
    background: #185FA5;
    color: #fff;
    border: none;
    border-radius: 16px;
    font-size: 15px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    font-family: inherit;
    transition: opacity 0.15s, transform 0.15s;
    box-shadow: 0 4px 16px rgba(24,95,165,0.3);
}
.buat-submit:active  { opacity: 0.85; transform: scale(0.98); }
.buat-submit ion-icon { font-size: 20px; }

.buat-submit.loading {
    opacity: 0.7;
    pointer-events: none;
}

/* ─── RESPONSIVE ─── */
@media (max-width: 380px) {
    .buat-card    { padding: 16px 14px; }
    .buat-upload  { padding: 22px 12px; }
}
</style>

{{-- ═══ SCRIPT ═══════════════════════════════════════ --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form        = document.getElementById('frmIzin');
    const fileInput   = document.getElementById('bukti_surat');
    const uploadArea  = document.getElementById('uploadArea');
    const filePreview = document.getElementById('filePreview');
    const previewWrap = document.getElementById('previewImgWrap');
    const removeBtn   = document.getElementById('removeFile');
    const textarea    = document.getElementById('keterangan');
    const charCount   = document.getElementById('charCount');
    const submitBtn   = document.getElementById('submitBtn');

    /* ── Character counter ── */
    textarea.addEventListener('input', function () {
        charCount.textContent = this.value.length;
    });

    /* ── Upload area click ── */
    uploadArea.addEventListener('click', () => fileInput.click());

    /* ── File selected ── */
    fileInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const maxSize = 5 * 1024 * 1024;
        const allowed = ['image/jpeg','image/jpg','image/png','image/gif','image/webp',
                         'application/pdf','application/msword',
                         'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

        if (file.size > maxSize) {
            Swal.fire({ icon:'error', title:'File Terlalu Besar', text:'Maksimal ukuran file 5MB.', confirmButtonColor:'#185FA5' });
            fileInput.value = '';
            return;
        }

        if (!allowed.includes(file.type)) {
            Swal.fire({ icon:'error', title:'Format Tidak Valid', text:'Hanya gambar, PDF, atau Word yang diperbolehkan.', confirmButtonColor:'#185FA5' });
            fileInput.value = '';
            return;
        }

        showPreview(file);
    });

    function showPreview(file) {
        uploadArea.style.display = 'none';
        filePreview.style.display = 'block';

        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileSize').textContent = (file.size / 1024).toFixed(1) + ' KB';
        document.getElementById('fileType').textContent = file.name.split('.').pop().toUpperCase();

        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('previewImg').src = e.target.result;
                previewWrap.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            previewWrap.style.display = 'none';
        }
    }

    /* ── Remove file ── */
    removeBtn.addEventListener('click', function () {
        fileInput.value = '';
        filePreview.style.display = 'none';
        previewWrap.style.display = 'none';
        uploadArea.style.display = 'block';
    });

    /* ── Form submit ── */
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const tgl    = document.getElementById('tgl_izin').value;
        const status = document.querySelector('input[name="status"]:checked');
        const ket    = textarea.value.trim();

        if (!tgl) {
            Swal.fire({ icon:'warning', title:'Tanggal Kosong', text:'Pilih tanggal izin terlebih dahulu.', confirmButtonColor:'#185FA5' });
            return;
        }
        if (!status) {
            Swal.fire({ icon:'warning', title:'Jenis Belum Dipilih', text:'Pilih jenis pengajuan (Izin / Sakit / Cuti).', confirmButtonColor:'#185FA5' });
            return;
        }
        if (!ket) {
            Swal.fire({ icon:'warning', title:'Keterangan Kosong', text:'Isi keterangan pengajuan terlebih dahulu.', confirmButtonColor:'#185FA5' });
            return;
        }

        submitBtn.classList.add('loading');
        submitBtn.innerHTML = '<ion-icon name="hourglass-outline"></ion-icon> Mengirim...';

        Swal.fire({
            title: 'Mengirim Pengajuan...',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => Swal.showLoading()
        });

        form.submit();
    });
});
</script>

@endsection