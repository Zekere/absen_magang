@extends('layout.presensi')
@section('header')
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Histori Presensi</div>
    <div class="right">
        <a href="javascript:;" class="headerButton">
        </a>
    </div>
</div>
@endsection

@section ('content')
<div class="row" style="margin-top:70px">
    <div class="col">
        <!-- Kalender Realtime -->
        <div class="card mb-3">
            <div class="card-body text-center">
                <h5 id="today-date" class="mb-1"></h5>
                <h6 id="today-time" class="text-muted"></h6>
            </div>
        </div>

        <!-- Filter Bulan -->
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <select name="bulan" id="bulan" class="form-control">
                        <option value="">Bulan</option>
                        @for ($i=1; $i<=12; $i++)
                        <option value="{{ $i }}" {{ (int)date("m") == $i ? 'selected' : '' }}>
                            {{ $namabulan[$i] }}
                        </option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>

        <!-- Filter Tahun -->
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <select name="tahun" id="tahun" class="form-control">
                        <option value="">Tahun</option>
                        @php 
                            $tahunmulai = 2023; 
                            $tahunsekarang = date('Y');
                        @endphp
                        @for ($tahun = $tahunmulai; $tahun <= $tahunsekarang; $tahun++)
                        <option value="{{ $tahun }}" {{ $tahun == date('Y') ? 'selected' : '' }}>
                            {{ $tahun }}
                        </option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>

        <!-- Tombol Search -->
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <button class="btn btn-primary btn-block" id="get">
                        <ion-icon name="search-outline"></ion-icon> Search
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tempat hasil histori -->
<div class="row">
    <div class="col" id="showhistori"></div>
</div>
@endsection

@push('scripts')
<script>
$(function(){
    // AJAX histori
    $("#get").click(function(e){
        var bulan = $("#bulan").val();
        var tahun = $("#tahun").val();
        $.ajax({
            type: 'POST',
            url: '/gethistori',
            data: {
                _token: "{{ csrf_token() }}",
                bulan: bulan,
                tahun : tahun 
            },
            cache : false,
            success: function(respond){
                $("#showhistori").html(respond);
            }
        });
    });

    // Kalender Realtime (tanggal + jam sekarang)
    function updateDateTime(){
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'};
        const dateStr = now.toLocaleDateString('id-ID', options);
        const timeStr = now.toLocaleTimeString('id-ID');
        $("#today-date").text(dateStr);
        $("#today-time").text(timeStr);
    }

    setInterval(updateDateTime, 1000);
    updateDateTime();
});
</script>
@endpush
