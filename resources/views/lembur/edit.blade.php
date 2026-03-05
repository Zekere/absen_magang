@extends('layout.presensi')

@section('header')
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="/lembur" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Edit Lembur</div>
    <div class="right"></div>
</div>

<style>
    .container {
        padding-top: 70px;
        padding-bottom: 80px;
    }

    .form-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #333;
    }

    .form-group label .required {
        color: #dc3545;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #ffc107;
        box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.1);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .btn-submit {
        background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
        color: #333;
        border: none;
        padding: 14px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        width: 100%;
        margin-top: 10px;
    }

    .info-box {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 13px;
        color: #856404;
    }

    .custom-file-upload {
        display: inline-block;
        padding: 12px 20px;
        cursor: pointer;
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        text-align: center;
        width: 100%;
        transition: all 0.3s ease;
    }

    .custom-file-upload:hover {
        background: #e9ecef;
        border-color: #ffc107;
    }

    .custom-file-upload ion-icon {
        font-size: 32px;
        color: #ffc107;
    }

    input[type="file"] {
        display: none;
    }

    .preview-image, .current-image {
        max-width: 100%;
        max-height: 200px;
        margin-top: 10px;
        border-radius: 8px;
    }

    .current-foto {
        margin-top: 10px;
        text-align: center;
    }
</style>
@endsection

@section('content')
<div class="container">
    @if (Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ion-icon name="close-circle-outline" style="font-size: 20px; vertical-align: middle;"></ion-icon>
            {{ Session::get('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="form-card">
        <div class="info-box">
            <ion-icon name="information-circle-outline"></ion-icon>
            <strong>Info:</strong> Edit data lembur Anda. Foto lama akan diganti jika Anda upload foto baru.
        </div>

        <form action="/lembur/{{ $lembur->id }}/update" method="POST" enctype="multipart/form-data" id="formLembur">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Tanggal Lembur <span class="required">*</span></label>
                <input type="date" name="tanggal_lembur" class="form-control" required max="{{ date('Y-m-d') }}" value="{{ $lembur->tanggal_lembur }}">
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Jam Mulai <span class="required">*</span></label>
                        <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" required value="{{ $lembur->jam_mulai }}">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Jam Selesai <span class="required">*</span></label>
                        <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" required value="{{ $lembur->jam_selesai }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Keterangan <span class="required">*</span></label>
                <textarea name="keterangan" class="form-control" placeholder="Jelaskan pekerjaan yang dilakukan saat lembur (minimal 10 karakter)" required>{{ $lembur->keterangan }}</textarea>
            </div>

            <div class="form-group">
                <label>Bukti Foto</label>
                
                @if($lembur->bukti_foto)
                <div class="current-foto">
                    <p><strong>Foto Saat Ini:</strong></p>
                    <img src="{{ asset('storage/uploads/lembur/' . $lembur->bukti_foto) }}" class="current-image">
                    <p class="text-muted mt-2"><small>Upload foto baru jika ingin mengganti</small></p>
                </div>
                @endif

                <label for="bukti_foto" class="custom-file-upload">
                    <ion-icon name="cloud-upload-outline"></ion-icon>
                    <div>{{ $lembur->bukti_foto ? 'Ganti' : 'Upload' }} Foto Bukti</div>
                    <small>Maksimal 2MB (JPG, JPEG, PNG)</small>
                </label>
                <input type="file" id="bukti_foto" name="bukti_foto" accept="image/*" onchange="previewImage(event)">
                <img id="preview" class="preview-image" style="display: none;">
            </div>

            <button type="submit" class="btn-submit">
                <ion-icon name="save-outline"></ion-icon>
                Update Data Lembur
            </button>
        </form>
    </div>
</div>

<script>
    // Preview image
    function previewImage(event) {
        const preview = document.getElementById('preview');
        const file = event.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    }

    // Validasi jam
    document.getElementById('formLembur').addEventListener('submit', function(e) {
        const jamMulai = document.getElementById('jam_mulai').value;
        const jamSelesai = document.getElementById('jam_selesai').value;

        if (jamSelesai <= jamMulai) {
            e.preventDefault();
            alert('Jam selesai harus lebih besar dari jam mulai!');
            return false;
        }
    });
</script>
@endsection