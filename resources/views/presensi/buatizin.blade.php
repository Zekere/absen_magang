@extends('layout.presensi')

@section('header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Form Izin</div>
</div>
@endsection

@section('content')
<div class="row" style="margin-top: 70px">
    <div class="col s12">
        <div class="card z-depth-1 rounded-3">
            <div class="card-content">
                <form method="POST" action="{{ url('/presensi/storeizin') }}" id="frmIzin">
                    @csrf
                    {{-- Tanggal Izin --}}
                    <div class="input-field">
                        <input type="date" id="tgl_izin" name="tgl_izin" class="validate" required>
                        <label for="tgl_izin">Tanggal Izin</label>
                    </div>

                    {{-- Status --}}
                    <div class="input-field">
                        <select name="status" id="status" required>
                            <option value="" disabled selected>Pilih Status</option>
                            <option value="1">Izin</option>
                            <option value="2">Sakit</option>
                            <option value="3">Cuti</option>
                        </select>
                        <label for="status">Status</label>
                    </div>

                    {{-- Keterangan --}}
                    <div class="input-field">
                        <textarea name="keterangan" id="keterangan" class="materialize-textarea" required></textarea>
                        <label for="keterangan">Keterangan</label>
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="input-field center-align">
                        <button type="submit" class="btn waves-effect waves-light blue w-100" style="width:100%;">
                            <i class="material-icons left"></i> KIRIM
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- jQuery + Materialize + SweetAlert -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi komponen Materialize
    var elems = document.querySelectorAll('select');
    M.FormSelect.init(elems);

    // Validasi form sebelum submit
    $('#frmIzin').on('submit', function(e) {
        const tgl_izin = $('#tgl_izin').val().trim();
        const status = $('#status').val();
        const keterangan = $('#keterangan').val().trim();

        if (!tgl_izin) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Oops!',
                text: 'Tanggal tidak boleh kosong!',
                confirmButtonColor: '#3085d6'
            });
            return false;
        }

        if (!status) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Oops!',
                text: 'Status tidak boleh kosong!',
                confirmButtonColor: '#3085d6'
            });
            return false;
        }

        if (!keterangan) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Oops!',
                text: 'Keterangan tidak boleh kosong!',
                confirmButtonColor: '#3085d6'
            });
            return false;
        }

        // jika semua valid, biarkan form terkirim
    });
});
</script>
@endpush
