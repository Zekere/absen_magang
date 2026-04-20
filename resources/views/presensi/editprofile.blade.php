@extends('layout.presensi')

@section('header')
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Edit Profile</div>
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
    color: #ffffff !important;
}

/* ─── Container ─── */
.ep-container {
    padding: 20px 16px 120px;
    max-width: 520px;
    margin: 0 auto;
}

/* ─── Alert ─── */
.ep-alert {
    border-radius: 12px;
    padding: 12px 16px;
    margin-bottom: 16px;
    font-size: 13px;
    font-weight: 600;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    border: none;
}
.ep-alert ion-icon { font-size: 18px; flex-shrink: 0; margin-top: 1px; }
.ep-alert.success { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
.ep-alert.error   { background: #fff1f2; color: #dc2626; border: 1px solid #fecdd3; }
.ep-alert ul { margin: 4px 0 0 16px; padding: 0; }
.ep-alert li { font-size: 12px; font-weight: 500; }

/* ─── Avatar Section ─── */
.avatar-section {
    background: #fff;
    border-radius: 20px;
    border: 1px solid rgba(99,130,220,0.15);
    box-shadow: 0 2px 16px rgba(59,111,240,0.08);
    padding: 24px 20px 20px;
    margin-bottom: 16px;
    text-align: center;
}
.avatar-wrap {
    position: relative;
    width: 100px;
    height: 100px;
    margin: 0 auto 16px;
}
.avatar-img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #fff;
    box-shadow: 0 4px 20px rgba(59,111,240,0.2);
}
.avatar-ring {
    position: absolute;
    inset: -4px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b6ff0, #667eea);
    z-index: -1;
}
.avatar-name {
    font-size: 16px;
    font-weight: 800;
    color: #1e2a4a;
    margin-bottom: 3px;
}
.avatar-nik {
    font-size: 12px;
    color: #64748b;
    font-weight: 500;
    background: #f0f4ff;
    display: inline-block;
    padding: 3px 12px;
    border-radius: 20px;
    margin-bottom: 16px;
}
.upload-zone {
    border: 2px dashed rgba(59,111,240,0.3);
    border-radius: 14px;
    padding: 16px;
    cursor: pointer;
    transition: all .2s;
    background: #f8faff;
}
.upload-zone:hover { border-color: #3b6ff0; background: #eff6ff; }
.upload-zone input[type="file"] { display: none; }
.upload-zone ion-icon { font-size: 28px; color: #3b6ff0; display: block; margin: 0 auto 6px; }
.upload-zone-txt { font-size: 12px; font-weight: 600; color: #3b6ff0; margin-bottom: 2px; }
.upload-zone-sub { font-size: 11px; color: #94a3b8; }

.preview-new {
    display: none;
    margin-top: 14px;
}
.preview-new-img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #3b6ff0;
    box-shadow: 0 4px 14px rgba(59,111,240,0.25);
    margin-bottom: 8px;
}
.preview-new-lbl { font-size: 11px; font-weight: 600; color: #64748b; margin-bottom: 8px; }
.btn-cancel-photo {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 5px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    background: #fff1f2;
    color: #dc2626;
    border: 1px solid #fecdd3;
    cursor: pointer;
    font-family: 'Plus Jakarta Sans', sans-serif;
    transition: all .2s;
}
.btn-cancel-photo:hover { background: #fecdd3; }
.btn-cancel-photo ion-icon { font-size: 13px; }

/* ─── Form Card ─── */
.form-card {
    background: #fff;
    border-radius: 20px;
    border: 1px solid rgba(99,130,220,0.15);
    box-shadow: 0 2px 16px rgba(59,111,240,0.08);
    overflow: hidden;
    margin-bottom: 14px;
}
.form-card-header {
    padding: 14px 18px 10px;
    border-bottom: 1px solid rgba(99,130,220,0.1);
    display: flex;
    align-items: center;
    gap: 9px;
}
.form-card-icon {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    background: linear-gradient(135deg, #3b6ff0, #667eea);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.form-card-icon ion-icon { font-size: 15px; color: #fff; }
.form-card-title { font-size: 13px; font-weight: 700; color: #1e2a4a; }

.form-card-body { padding: 16px 18px; }

/* ─── Field ─── */
.field-wrap { margin-bottom: 16px; }
.field-wrap:last-child { margin-bottom: 0; }

.field-label {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.field-label ion-icon { font-size: 13px; }

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
}
.field-input:focus {
    border-color: #3b6ff0;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(59,111,240,0.1);
}
.field-input.readonly {
    background: #f1f5f9;
    color: #94a3b8;
    cursor: not-allowed;
    font-weight: 600;
}
.field-input.is-invalid { border-color: #dc2626; }
.field-error { font-size: 11px; color: #dc2626; font-weight: 600; margin-top: 4px; display: flex; align-items: center; gap: 4px; }
.field-hint  { font-size: 11px; color: #94a3b8; margin-top: 4px; }

.readonly-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #f1f5f9;
    border: 1px solid rgba(99,130,220,0.2);
    border-radius: 8px;
    padding: 4px 10px;
    font-size: 11px;
    color: #94a3b8;
    font-weight: 600;
    margin-top: 5px;
}
.readonly-badge ion-icon { font-size: 12px; }

/* password eye toggle */
.pw-wrap { position: relative; }
.pw-wrap .field-input { padding-right: 44px; }
.pw-eye {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    color: #94a3b8;
    padding: 0;
    display: flex;
    align-items: center;
}
.pw-eye ion-icon { font-size: 18px; }
.pw-eye:hover { color: #3b6ff0; }

/* ─── Submit Button ─── */
.btn-submit-wrap { margin-top: 6px; }
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
.btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(59,111,240,0.45); }
.btn-submit:active { transform: translateY(0); }
</style>
@endsection

@section('content')
<div class="ep-container">

    {{-- ── Alerts ── --}}
    @if(Session::get('success'))
        <div class="ep-alert success">
            <ion-icon name="checkmark-circle-outline"></ion-icon>
            <span>{{ Session::get('success') }}</span>
        </div>
    @endif

    @if(Session::get('error'))
        <div class="ep-alert error">
            <ion-icon name="alert-circle-outline"></ion-icon>
            <span>{{ Session::get('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="ep-alert error">
            <ion-icon name="alert-circle-outline"></ion-icon>
            <div>
                <div style="font-weight:700;margin-bottom:4px">Terdapat kesalahan:</div>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="/presensi/{{ $karyawan->nik }}/updateprofile"
          method="POST" enctype="multipart/form-data" id="formProfile">
        @csrf

        {{-- ── Avatar Card ── --}}
        <div class="avatar-section">
            <div class="avatar-wrap">
                <div class="avatar-ring"></div>
                @if(!empty($karyawan->foto))
                    @php $path = Storage::url('uploads/karyawan/' . $karyawan->foto); @endphp
                    <img src="{{ url($path) }}" class="avatar-img" id="currentPhoto" alt="Foto">
                @else
                    <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" class="avatar-img" id="currentPhoto" alt="Foto">
                @endif
            </div>
            <div class="avatar-name">{{ $karyawan->nama_lengkap }}</div>
            <div class="avatar-nik">NIK: {{ $karyawan->nik }}</div>

            {{-- Upload Zone --}}
            <label class="upload-zone" for="fileuploadInput">
                <input type="file" name="foto" id="fileuploadInput"
                       accept="image/png, image/jpg, image/jpeg">
                <ion-icon name="cloud-upload-outline"></ion-icon>
                <div class="upload-zone-txt">Tap untuk ganti foto profil</div>
                <div class="upload-zone-sub">JPG, JPEG, PNG · Maks. 2 MB</div>
            </label>

            {{-- Preview foto baru --}}
            <div class="preview-new" id="previewNew">
                <div class="preview-new-lbl">Preview foto baru</div>
                <img src="" class="preview-new-img" id="newPhotoPreview" alt="Preview">
                <br>
                <button type="button" class="btn-cancel-photo" onclick="cancelUpload()">
                    <ion-icon name="close-circle-outline"></ion-icon>
                    Batalkan
                </button>
            </div>
        </div>

        {{-- ── Info Pribadi ── --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <ion-icon name="person-outline"></ion-icon>
                </div>
                <span class="form-card-title">Informasi Pribadi</span>
            </div>
            <div class="form-card-body">

                {{-- NIK (readonly) --}}
                <div class="field-wrap">
                    <div class="field-label">
                        <ion-icon name="id-card-outline"></ion-icon>
                        NIK / ID Karyawan
                    </div>
                    <input type="text" class="field-input readonly"
                           value="{{ $karyawan->nik }}" disabled>
                    <div class="readonly-badge">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        Tidak dapat diubah
                    </div>
                </div>

                {{-- Nama Lengkap --}}
                <div class="field-wrap">
                    <div class="field-label">
                        <ion-icon name="text-outline"></ion-icon>
                        Nama Lengkap
                    </div>
                    <input type="text"
                           class="field-input @error('nama_lengkap') is-invalid @enderror"
                           name="nama_lengkap"
                           value="{{ old('nama_lengkap', $karyawan->nama_lengkap) }}"
                           placeholder="Masukkan nama lengkap"
                           required>
                    @error('nama_lengkap')
                        <div class="field-error">
                            <ion-icon name="alert-circle-outline"></ion-icon>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- No. HP --}}
                <div class="field-wrap">
                    <div class="field-label">
                        <ion-icon name="call-outline"></ion-icon>
                        No. HP
                    </div>
                    <input type="text"
                           class="field-input @error('no_hp') is-invalid @enderror"
                           name="no_hp"
                           value="{{ old('no_hp', $karyawan->no_hp) }}"
                           placeholder="Contoh: 08123456789">
                    @error('no_hp')
                        <div class="field-error">
                            <ion-icon name="alert-circle-outline"></ion-icon>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

            </div>
        </div>

        {{-- ── Info Pekerjaan ── --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <ion-icon name="briefcase-outline"></ion-icon>
                </div>
                <span class="form-card-title">Informasi Pekerjaan</span>
            </div>
            <div class="form-card-body">

                {{-- Jabatan (readonly) --}}
                <div class="field-wrap">
                    <div class="field-label">
                        <ion-icon name="ribbon-outline"></ion-icon>
                        Jabatan
                    </div>
                    <input type="text" class="field-input readonly"
                           value="{{ $karyawan->jabatan }}" disabled>
                    <input type="hidden" name="jabatan" value="{{ $karyawan->jabatan }}">
                    <div class="readonly-badge">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        Diatur oleh admin
                    </div>
                </div>

                {{-- Departemen (readonly) --}}
                <div class="field-wrap">
                    <div class="field-label">
                        <ion-icon name="business-outline"></ion-icon>
                        Departemen
                    </div>
                    <input type="text" class="field-input readonly"
                           value="{{ $karyawan->departemen->nama_dept ?? '-' }}"disabled>
                    <input type="hidden" name="departemen" value="{{ $karyawan->departemen }}">
                    <div class="readonly-badge">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        Diatur oleh admin
                    </div>
                </div>

            </div>
        </div>

        {{-- ── Keamanan Akun ── --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <ion-icon name="shield-checkmark-outline"></ion-icon>
                </div>
                <span class="form-card-title">Keamanan Akun</span>
            </div>
            <div class="form-card-body">

                {{-- Password --}}
                <div class="field-wrap">
                    <div class="field-label">
                        <ion-icon name="key-outline"></ion-icon>
                        Password Baru
                    </div>
                    <div class="pw-wrap">
                        <input type="password"
                               class="field-input @error('password') is-invalid @enderror"
                               name="password"
                               id="passwordField"
                               placeholder="Kosongkan jika tidak diubah">
                        <button type="button" class="pw-eye" id="togglePw" onclick="togglePassword()">
                            <ion-icon name="eye-outline" id="eyeIcon"></ion-icon>
                        </button>
                    </div>
                    @error('password')
                        <div class="field-error">
                            <ion-icon name="alert-circle-outline"></ion-icon>
                            {{ $message }}
                        </div>
                    @enderror
                    <div class="field-hint">Minimal 6 karakter. Kosongkan jika tidak ingin mengubah password.</div>
                </div>

            </div>
        </div>

        {{-- ── Submit ── --}}
        <div class="btn-submit-wrap">
            <button type="submit" class="btn-submit">
                <ion-icon name="save-outline"></ion-icon>
                Simpan Perubahan
            </button>
        </div>

    </form>
</div>

<script>
/* ── Preview foto baru ── */
const fileInput      = document.getElementById('fileuploadInput');
const previewNew     = document.getElementById('previewNew');
const newPhotoPreview= document.getElementById('newPhotoPreview');
const currentPhoto   = document.getElementById('currentPhoto');

fileInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    if (file.size > 2 * 1024 * 1024) {
        alert('Ukuran foto maksimal 2 MB!');
        fileInput.value = '';
        return;
    }
    const reader = new FileReader();
    reader.onload = function(ev) {
        newPhotoPreview.src = ev.target.result;
        previewNew.style.display = 'block';
        currentPhoto.src = ev.target.result; // update ring avatar juga
    };
    reader.readAsDataURL(file);
});

function cancelUpload() {
    fileInput.value = '';
    previewNew.style.display = 'none';
    // kembalikan foto asli
    @if(!empty($karyawan->foto))
        currentPhoto.src = '{{ url(Storage::url("uploads/karyawan/" . $karyawan->foto)) }}';
    @else
        currentPhoto.src = '{{ asset("assets/img/sample/avatar/avatar1.jpg") }}';
    @endif
}

/* ── Toggle password visibility ── */
function togglePassword() {
    const field   = document.getElementById('passwordField');
    const icon    = document.getElementById('eyeIcon');
    const isHidden = field.type === 'password';
    field.type = isHidden ? 'text' : 'password';
    icon.setAttribute('name', isHidden ? 'eye-off-outline' : 'eye-outline');
}
</script>
@endsection