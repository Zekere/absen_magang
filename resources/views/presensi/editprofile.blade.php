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
@endsection

@section('content')

<style>
    .avatar-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        margin: 20px auto;
        display: block;
        border: 4px solid #007bff;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .custom-file-upload {
        text-align: center;
        padding: 20px;
        margin: 15px 0;
        border: 2px dashed #ccc;
        border-radius: 8px;
        cursor: pointer;
    }

    .preview-container {
        text-align: center;
        margin-bottom: 20px;
    }

    #imagePreview {
        display: none;
        margin-top: 10px;
    }

    .btn-update {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        gap: 6px;
    }

    /* Tambahan biar tombol tidak ketutup nav */
    #appCapsule {
        padding-bottom: 100px;
    }

    .custom-file-upload input[type="file"] {
        display: none;
    }
</style>

<div class="row" style="margin-top:4rem">
    <div class="col">
        {{-- Pesan Sukses --}}
        @if(Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <strong>Berhasil!</strong> {{ Session::get('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        {{-- Pesan Error --}}
        @if(Session::get('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <strong>Gagal!</strong> {{ Session::get('error') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        {{-- Validasi --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <strong>Error!</strong>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif
    </div>
</div>

<div id="appCapsule">
    <form action="/presensi/{{ $karyawan->nik }}/updateprofile" method="POST" enctype="multipart/form-data" id="formProfile">
        @csrf
        <div class="col">
            <!-- Foto Profil Saat Ini -->
            <div class="preview-container">
                <div class="mb-2"><strong>Foto Profil Saat Ini:</strong></div>
                @if(!empty($karyawan->foto))
                    @php $path = Storage::url('uploads/karyawan/' . $karyawan->foto); @endphp
                    <img src="{{ url($path) }}" alt="Current Photo" class="avatar-preview" id="currentPhoto">
                @else
                    <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" alt="Default Photo" class="avatar-preview" id="currentPhoto">
                @endif
            </div>

            <!-- Preview Foto Baru -->
            <div class="preview-container" id="imagePreview">
                <div class="mb-2"><strong>Preview Foto Baru:</strong></div>
                <img src="" alt="New Photo Preview" class="avatar-preview" id="newPhotoPreview">
                <button type="button" class="btn btn-sm btn-danger mt-2" onclick="cancelUpload()">
                    <ion-icon name="close-outline"></ion-icon> Batalkan Foto Baru
                </button>
            </div>

            <!-- Input Nama -->
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="label">Nama Lengkap</label>
                    <input type="text" 
                           class="form-control @error('nama_lengkap') is-invalid @enderror" 
                           value="{{ old('nama_lengkap', $karyawan->nama_lengkap) }}" 
                           name="nama_lengkap" required>
                    @error('nama_lengkap')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Jabatan (readonly) -->
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="label">Jabatan</label>
                    <input type="text" class="form-control" value="{{ $karyawan->jabatan }}" disabled>
                    <input type="hidden" name="jabatan" value="{{ $karyawan->jabatan }}">
                </div>
            </div>

            <!-- Nomor HP -->
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="label">No. HP</label>
                    <input type="text" 
                           class="form-control @error('no_hp') is-invalid @enderror" 
                           value="{{ old('no_hp', $karyawan->no_hp) }}" 
                           name="no_hp">
                    @error('no_hp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Password Baru -->
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="label">Password Baru</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Kosongkan jika tidak diubah">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Minimal 6 karakter</small>
                </div>
            </div>

            <!-- Upload Foto Baru -->
            <div class="custom-file-upload">
                <input type="file" name="foto" id="fileuploadInput" accept="image/png, image/jpg, image/jpeg">
                <label for="fileuploadInput">
                    <span>
                        <ion-icon name="cloud-upload-outline" style="font-size: 48px;"></ion-icon><br>
                        <i>Tap untuk Upload Foto Baru</i><br>
                        <small>Format: JPG, JPEG, PNG (Max: 2MB)</small>
                    </span>
                </label>
            </div>

            <!-- Tombol Update -->
            <div class="form-group boxed mb-5">
                <div class="input-wrapper">
                    <button type="submit" class="btn btn-primary btn-block btn-lg btn-update">
                        <ion-icon name="save-outline"></ion-icon>
                        Simpan & Update Profile
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Script Preview Foto --}}
<script>
    const fileInput = document.getElementById("fileuploadInput");
    const imagePreview = document.getElementById("imagePreview");
    const newPhotoPreview = document.getElementById("newPhotoPreview");
    const currentPhoto = document.getElementById("currentPhoto");

    fileInput.addEventListener("change", function(event) {
        const file = event.target.files[0];
        if (file) {
            // cek ukuran max 2MB
            if(file.size > 2 * 1024 * 1024){
                alert("Ukuran foto maksimal 2MB!");
                fileInput.value = "";
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                newPhotoPreview.src = e.target.result;
                imagePreview.style.display = "block";
                currentPhoto.style.display = "none";
            };
            reader.readAsDataURL(file);
        }
    });

    function cancelUpload(){
        fileInput.value = "";
        imagePreview.style.display = "none";
        currentPhoto.style.display = "block";
    }
</script>
@endsection
