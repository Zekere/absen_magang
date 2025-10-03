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
    <div class="right">
       
    </div>
</div>

@endsection
@section('content')
<div class="row" style="margin-top: 70px">
    <div class="col">
        <div class="col">
    <form method="POST" action="{{ url('/presensi/storeizin') }}" id="frmIzin">
        @csrf
        <div class="row mb-3">
            <div class="col">
                <div class="form-group">
                    <input type="date" id="tgl_izin" name="tgl_izin" class="form-control" placeholder="Tanggal" required>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <div class="form-group">
                    <select name="status" id="status" class="form-control" required>
                        <option value="">Pilih Status</option>
                        <option value="1">Izin</option>
                        <option value="2">Sakit</option>
                        <option value="3">Cuti</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb-3">
            <textarea name="keterangan" id="keterangan" cols="30" rows="5" 
                      class="form-control" placeholder="Keterangan" required></textarea>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary w-100">Kirim</button>
        </div>
    </form>
</div>

</div>
@endsection

@push('scripts')
<script>
    var currYear = (new Date()).getFullYear();

        $(document).ready(function() {
        $(".datepicker").datepicker({
           
            format: "dd/mm/yyyy"    
        });

        $("#frmIzin").submit(function() {
            var tgl_izin = $("#tgl_izin").val();
            var status = $("#status").val();
            var keterangan = $("#keterangan").val();
                if(tgl_izin == "") {
                    Swal.fire({
                            title: 'Oops!',
                            text: 'Tanggal tidak boleh kosong!',
                            icon:'Warning!',
                        });
                    return false;
                }else if(status == ""){
                    Swal.fire({
                            title: 'Oops!',
                            text: 'Status tidak boleh kosong!',
                            icon: 'Warning!',
                        });
                    return false;  
                }else if(keterangan == ""){
                    Swal.fire({
                            title: 'Oops!',
                            text: 'Keterangan tidak boleh kosong!',
                            icon: 'Warning!',
                        });
                    return false;  
                }
            
        });
    });


</script>
@endpush