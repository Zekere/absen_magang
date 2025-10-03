@if($histori->isEmpty())
<div class="text-center my-5">
    <!-- Ikon dengan background bulat -->
    <div class="d-flex justify-content-center mb-3">
        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm"
             style="width: 80px; height: 80px; background: #f8fafc; color: #3b82f6; font-size: 38px;">
            <ion-icon name="time-outline"></ion-icon>
        </div>
    </div>

    <!-- Judul -->
    <h4 class="fw-bold text-dark mb-2">Tidak Ada Histori Presensi</h4>

    <!-- Deskripsi -->
    <p class="text-muted" style="font-size: 14px;">
        Data histori absensi Anda untuk periode ini belum tersedia.
    </p>
</div>
@endif


@foreach ( $histori as $d )
                        <ul class="listview image-listview">

                        <li>
                            <div class="item d-flex align-items-center p-2 mb-3 shadow-sm rounded" style="gap: 12px; background: #fff;">
    @php
        $path = Storage::url('uploads/absensi/'.$d->foto_in);
    @endphp
    
    <!-- Foto -->
    <img src="{{ url($path) }}" alt="image" class="rounded" 
         style="width: 50px; height: 50px; object-fit: cover;">
    
    <!-- Keterangan -->
    <div class="flex-grow-1">
        <b>{{ date("d-m-Y", strtotime($d->tgl_presensi)) }}</b><br>
        <span class="badge {{ $d->jam_in < '07:30' ? 'bg-success' : 'bg-danger' }}">
            {{ $d->jam_in }}
        </span>
    </div>

    <!-- Jam out -->
    @if($d->jam_out)
        <span class="badge bg-primary">{{ $d->jam_out }}</span>
    @endif
</div>


                                </div>
                        </li>
                        </ul>
@endforeach