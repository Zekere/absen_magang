@extends('layout.presensi')

@section('header')
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="{{ route('lembur.index') }}" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Input Lembur</div>
    <div class="right"></div>
</div>

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

* { box-sizing: border-box; }
body, .wrapper { background: #f0f4ff !important; font-family: 'Plus Jakarta Sans', sans-serif; }

.appHeader {
    background: linear-gradient(135deg, #3b6ff0, #667eea) !important;
    border-bottom: none;
    box-shadow: 0 2px 20px rgba(59,111,240,0.3) !important;
}
.appHeader .pageTitle {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 700;
    font-size: 16px;
    color: #fff !important;
}

/* ─── Container ─── */
.lembur-form-container {
    padding: 76px 16px 110px;
    max-width: 520px;
    margin: 0 auto;
}

/* ─── Alert ─── */
.ep-alert {
    border-radius: 12px;
    padding: 12px 16px;
    margin-bottom: 14px;
    font-size: 13px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}
.ep-alert ion-icon { font-size: 18px; flex-shrink: 0; }
.ep-alert.error { background: #fff1f2; color: #dc2626; border: 1px solid #fecdd3; }

/* ─── Info Banner ─── */
.info-banner {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 14px;
    padding: 13px 15px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 16px;
}
.info-banner ion-icon { font-size: 18px; color: #3b6ff0; flex-shrink: 0; margin-top: 1px; }
.info-banner-text { font-size: 12px; color: #1d4ed8; line-height: 1.5; }
.info-banner-text strong { font-weight: 700; }

/* ─── Form Card ─── */
.form-card {
    background: #fff;
    border-radius: 20px;
    border: 1px solid rgba(99,130,220,0.12);
    box-shadow: 0 2px 16px rgba(59,111,240,0.08);
    overflow: hidden;
    margin-bottom: 14px;
}
.form-card-header {
    padding: 14px 18px;
    border-bottom: 1px solid rgba(99,130,220,0.1);
    display: flex;
    align-items: center;
    gap: 10px;
}
.form-card-icon {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    background: linear-gradient(135deg, #3b6ff0, #667eea);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.form-card-icon ion-icon { font-size: 16px; color: #fff; }
.form-card-icon.purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
.form-card-icon.green  { background: linear-gradient(135deg, #22c55e, #16a34a); }
.form-card-title { font-size: 13px; font-weight: 700; color: #1e2a4a; }
.form-card-body  { padding: 16px 18px; }

/* ─── Field ─── */
.field-wrap { margin-bottom: 16px; }
.field-wrap:last-child { margin-bottom: 0; }

.field-label {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 7px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.field-label .req { color: #dc2626; }

.field-input {
    width: 100%;
    padding: 12px 14px;
    border: 1.5px solid rgba(99,130,220,0.2);
    border-radius: 12px;
    font-size: 14px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #1e2a4a;
    background: #f8faff;
    outline: none;
    transition: all .2s;
    -webkit-appearance: none;
}
.field-input:focus {
    border-color: #3b6ff0;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(59,111,240,0.1);
}
.field-input.is-invalid { border-color: #dc2626; }
.field-hint  { font-size: 11px; color: #94a3b8; margin-top: 5px; }
.field-error { font-size: 11px; color: #dc2626; font-weight: 600; margin-top: 4px; display: flex; align-items: center; gap: 4px; }
.field-error ion-icon { font-size: 12px; }

/* ─── Time Grid ─── */
.time-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

/* ─── Duration Preview ─── */
.duration-preview {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    border-radius: 12px;
    padding: 13px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 12px;
}
.duration-preview ion-icon { font-size: 20px; color: rgba(255,255,255,0.8); flex-shrink: 0; }
.duration-preview-lbl { font-size: 11px; font-weight: 700; color: rgba(255,255,255,0.7); text-transform: uppercase; letter-spacing: 0.4px; }
.duration-preview-val { font-size: 18px; font-weight: 800; color: #fff; line-height: 1; }

/* ─── Upload Zone ─── */
.upload-zone {
    border: 2px dashed rgba(59,111,240,0.25);
    border-radius: 14px;
    padding: 22px 16px;
    text-align: center;
    cursor: pointer;
    transition: all .2s;
    background: #f8faff;
    display: block;
}
.upload-zone:hover { border-color: #3b6ff0; background: #eff6ff; }
.upload-zone ion-icon { font-size: 32px; color: #3b6ff0; display: block; margin: 0 auto 8px; }
.upload-zone-title { font-size: 13px; font-weight: 700; color: #3b6ff0; margin-bottom: 3px; }
.upload-zone-sub   { font-size: 11px; color: #94a3b8; }
input[type="file"] { display: none; }

.preview-wrap {
    display: none;
    margin-top: 12px;
    position: relative;
}
.preview-img {
    width: 100%;
    max-height: 200px;
    object-fit: cover;
    border-radius: 12px;
    border: 1px solid rgba(99,130,220,0.15);
}
.preview-remove {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: rgba(220,38,38,0.85);
    color: #fff;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    backdrop-filter: blur(4px);
}

/* ─── Submit Button ─── */
.btn-submit {
    width: 100%;
    padding: 15px;
    border-radius: 14px;
    background: linear-gradient(135deg, #3b6ff0, #667eea);
    color: #fff;
    font-size: 15px;
    font-weight: 800;
    font-family: 'Plus Jakarta Sans', sans-serif;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    box-shadow: 0 5px 20px rgba(59,111,240,0.35);
    transition: all .2s;
}
.btn-submit ion-icon { font-size: 19px; }
.btn-submit:hover  { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(59,111,240,0.45); }
.btn-submit:active { transform: translateY(0); }
.btn-submit:disabled { opacity: .5; cursor: not-allowed; transform: none; }
</style>
@endsection

@section('content')
<div class="lembur-form-container">

    {{-- Alert Error --}}
    @if(Session::get('error'))
        <div class="ep-alert error">
            <ion-icon name="alert-circle-outline"></ion-icon>
            <span>{{ Session::get('error') }}</span>
        </div>
    @endif

    {{-- Info Banner --}}
    <div class="info-banner">
        <ion-icon name="information-circle-outline"></ion-icon>
        <div class="info-banner-text">
            <strong>Info:</strong> Isi form dengan data lembur yang sebenarnya.
            Foto bukti bersifat opsional namun disarankan sebagai dokumentasi.
        </div>
    </div>

    <form action="{{ route('lembur.store') }}" method="POST"
          enctype="multipart/form-data" id="formLembur">
        @csrf

        {{-- ── Kartu 1: Tanggal ── --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <ion-icon name="calendar-outline"></ion-icon>
                </div>
                <span class="form-card-title">Tanggal Lembur</span>
            </div>
            <div class="form-card-body">
                <div class="field-wrap">
                    <div class="field-label">
                        Pilih Tanggal <span class="req">*</span>
                    </div>
                    <input type="date"
                           name="tanggal_lembur"
                           class="field-input @error('tanggal_lembur') is-invalid @enderror"
                           required
                           max="{{ date('Y-m-d') }}"
                           value="{{ old('tanggal_lembur', date('Y-m-d')) }}">
                    @error('tanggal_lembur')
                        <div class="field-error">
                            <ion-icon name="alert-circle-outline"></ion-icon>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- ── Kartu 2: Jam ── --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon purple">
                    <ion-icon name="time-outline"></ion-icon>
                </div>
                <span class="form-card-title">Jam Lembur</span>
            </div>
            <div class="form-card-body">
                <div class="time-grid">
                    <div class="field-wrap">
                        <div class="field-label">Jam Mulai <span class="req">*</span></div>
                        <input type="time"
                               name="jam_mulai"
                               id="jam_mulai"
                               class="field-input @error('jam_mulai') is-invalid @enderror"
                               required
                               value="{{ old('jam_mulai') }}"
                               oninput="hitungDurasi()">
                        @error('jam_mulai')
                            <div class="field-error">
                                <ion-icon name="alert-circle-outline"></ion-icon>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="field-wrap">
                        <div class="field-label">Jam Selesai <span class="req">*</span></div>
                        <input type="time"
                               name="jam_selesai"
                               id="jam_selesai"
                               class="field-input @error('jam_selesai') is-invalid @enderror"
                               required
                               value="{{ old('jam_selesai') }}"
                               oninput="hitungDurasi()">
                        @error('jam_selesai')
                            <div class="field-error">
                                <ion-icon name="alert-circle-outline"></ion-icon>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                {{-- Duration Preview --}}
                <div class="duration-preview" id="durationPreview" style="display:none">
                    <ion-icon name="hourglass-outline"></ion-icon>
                    <div>
                        <div class="duration-preview-lbl">Total Durasi Lembur</div>
                        <div class="duration-preview-val" id="durationText">0 jam 0 menit</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Kartu 3: Keterangan ── --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon green">
                    <ion-icon name="document-text-outline"></ion-icon>
                </div>
                <span class="form-card-title">Keterangan</span>
            </div>
            <div class="form-card-body">
                <div class="field-wrap">
                    <div class="field-label">Pekerjaan yang dilakukan <span class="req">*</span></div>
                    <textarea name="keterangan"
                              class="field-input @error('keterangan') is-invalid @enderror"
                              style="min-height:100px;resize:vertical"
                              placeholder="Contoh: Menyelesaikan laporan bulanan, meeting dengan klien, dll."
                              required>{{ old('keterangan') }}</textarea>
                    <div class="field-hint">Minimal 10 karakter. Jelaskan pekerjaan yang dilakukan.</div>
                    @error('keterangan')
                        <div class="field-error">
                            <ion-icon name="alert-circle-outline"></ion-icon>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- ── Kartu 4: Bukti Foto ── --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon" style="background:linear-gradient(135deg,#f59e0b,#d97706)">
                    <ion-icon name="image-outline"></ion-icon>
                </div>
                <span class="form-card-title">Bukti Foto <span style="font-weight:500;color:#94a3b8">(Opsional)</span></span>
            </div>
            <div class="form-card-body">
                <label for="bukti_foto" class="upload-zone" id="uploadZone">
                    <ion-icon name="cloud-upload-outline"></ion-icon>
                    <div class="upload-zone-title">Tap untuk upload foto</div>
                    <div class="upload-zone-sub">JPG, JPEG, PNG · Maks. 2 MB</div>
                </label>
                <input type="file" id="bukti_foto" name="bukti_foto"
                       accept="image/jpg,image/jpeg,image/png"
                       onchange="previewImage(event)">

                <div class="preview-wrap" id="previewWrap">
                    <img id="previewImg" class="preview-img" src="" alt="Preview">
                    <button type="button" class="preview-remove" onclick="removeImage()">✕</button>
                </div>

                @error('bukti_foto')
                    <div class="field-error" style="margin-top:8px">
                        <ion-icon name="alert-circle-outline"></ion-icon>
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        {{-- ── Submit ── --}}
        <button type="submit" class="btn-submit" id="submitBtn">
            <ion-icon name="save-outline"></ion-icon>
            Simpan Data Lembur
        </button>

    </form>
</div>

<script>
/* ── Hitung durasi live ── */
function hitungDurasi() {
    const mulai   = document.getElementById('jam_mulai').value;
    const selesai = document.getElementById('jam_selesai').value;
    const preview = document.getElementById('durationPreview');
    const text    = document.getElementById('durationText');

    if (mulai && selesai && selesai > mulai) {
        const [hM, mM] = mulai.split(':').map(Number);
        const [hS, mS] = selesai.split(':').map(Number);
        const totalMenit = (hS * 60 + mS) - (hM * 60 + mM);
        const jam   = Math.floor(totalMenit / 60);
        const menit = totalMenit % 60;
        text.textContent = jam + ' jam ' + menit + ' menit';
        preview.style.display = 'flex';
    } else {
        preview.style.display = 'none';
    }
}

/* ── Preview foto ── */
function previewImage(event) {
    const file = event.target.files[0];
    if (!file) return;
    if (file.size > 2 * 1024 * 1024) {
        alert('Ukuran foto maksimal 2 MB!');
        event.target.value = '';
        return;
    }
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('previewWrap').style.display = 'block';
        document.getElementById('uploadZone').style.display  = 'none';
    };
    reader.readAsDataURL(file);
}

function removeImage() {
    document.getElementById('bukti_foto').value  = '';
    document.getElementById('previewImg').src     = '';
    document.getElementById('previewWrap').style.display = 'none';
    document.getElementById('uploadZone').style.display  = 'block';
}

/* ── Validasi sebelum submit ── */
document.getElementById('formLembur').addEventListener('submit', function(e) {
    const mulai   = document.getElementById('jam_mulai').value;
    const selesai = document.getElementById('jam_selesai').value;

    if (!mulai || !selesai) {
        e.preventDefault();
        alert('Jam mulai dan jam selesai wajib diisi!');
        return;
    }
    if (selesai <= mulai) {
        e.preventDefault();
        alert('Jam selesai harus lebih besar dari jam mulai!');
        return;
    }

    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<ion-icon name="hourglass-outline"></ion-icon> Menyimpan...';
});

/* ── Jalankan saat halaman load (untuk old value) ── */
document.addEventListener('DOMContentLoaded', hitungDurasi);
</script>
@endsection