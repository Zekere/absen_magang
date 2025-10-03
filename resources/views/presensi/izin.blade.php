@extends('layout.presensi')
@section('header')
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Data Izin</div>
    <div class="right">
       
    </div>
</div>

@endsection
@section('content')
<div class="row" style="margin-top:70px">
    <div class="col">

         @php
        $messagesuccess = Session::get('success');
                $messageerror = Session::get('error');

        @endphp
        @if(Session::get('success'))
<div class="alert alert-success">
    {{ $messagesuccess }}
</div>
        @endif

         @if(Session::get('error'))
<div class="alert alert-danger">
    {{ $messageerror }}
</div>
        @endif
    </div>
</div>
{{-- resources/views/izin/index.blade.php (contoh) --}}
<div class="row">
    <div class="col">
        @forelse ($dataizin as $d)

          

            <ul class="listview image-listview">
                <li>
                    <div class="item d-flex align-items-center p-3 mb-3 shadow-sm rounded" style="gap: 12px; background: #fff;">
                        
                        <!-- Icon -->
                        <div style="width: 40px; height: 40px; background: #f1f1f1; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <ion-icon name="calendar-outline"></ion-icon>
                        </div>

                        <!-- Info -->
                       <div class="flex-grow-1">
    <b>
        {{ date('d-m-Y', strtotime($d->tgl_izin)) }}
        ( 
            @if($d->status == 1)
                Izin
            @elseif($d->status == 2)
                Sakit
            @elseif($d->status == 3)
                Cuti
            @else
                Tidak Diketahui
            @endif
        )
    </b><br>
    <small class="text-muted">{{ $d->keterangan }}</small>
</div>

                          
@if ($d->status_approved == 0)
<span class ="badge bg-warning text-white">Pending</span>
@elseif ($d->status_approved == 1)
<span class ="badge bg-success text-white">Approved</span>
@elseif ($d->status_approved == 2)
<span class ="badge bg-danger text-white">Decline</span>
@endif
                     

                    </div>
                </li>
            </ul>
        @empty
          <div class="empty-state text-center p-5">
    <div class="empty-state-icon mb-3">
        <ion-icon name="document-text-outline" style="font-size: 60px; color:#3b82f6;"></ion-icon>
    </div>
    <h4 class="fw-bold mb-2" style="color:#374151;">Belum ada data izin</h4>
    <p class="text-muted" style="font-size:14px;">
        Anda belum pernah mengajukan izin.<br>
        Tekan tombol <ion-icon name="add-circle-outline"></ion-icon> di bawah untuk membuat izin baru.
    </p>
</div>

<style>
.empty-state {
    background: #f9fafb;
    border-radius: 16px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    margin: 2rem auto;
    max-width: 320px;
}
.empty-state-icon {
    display:flex;
    align-items:center;
    justify-content:center;
    width:80px;
    height:80px;
    border-radius:50%;
    background:#e0f2fe;
    margin:0 auto;
}
</style>

        @endforelse
    </div>
</div>


<div class="fab-button bottom-right" style="margin-bottom: 70px">
    <a href="/presensi/buatizin" class="fab">
        <ion-icon name="add-outline"></ion-icon>
    </a>
</div>
@endsection