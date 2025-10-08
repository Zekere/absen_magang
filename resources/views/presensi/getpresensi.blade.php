@foreach ($presensi as $d)
   
@php
$foto_in = Storage::url('uploads/absensi/'.$d->foto_in);
$foto_out = Storage::url('uploads/absensi/'.$d->foto_out);

@endphp
<tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $d->nik }}</td>
         <td>{{ $d->nama_lengkap }}</td>
                  <td>{{ $d->nama_dept }}</td>
                           <td>{{ $d->jam_in }}</td>
    <td>
        <img src="{{ url($foto_in) }}" class="avatar" alt="">
    </td>
    <td>{!! $d->jam_out != null ? $d->jam_out : '<span class="badge bg-danger">Belum absen</span>' !!}
</td>
     <td>
        @if ($d->jam_out != null)
        <img src="{{ url( $foto_out) }}" class="avatar" alt="">
@else
      <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-clock-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20.926 13.15a9 9 0 1 0 -7.835 7.784" /><path d="M12 7v5l2 2" /><path d="M22 22l-5 -5" /><path d="M17 22l5 -5" /></svg>
        @endif
    </td>
    <td>
    @if ($d->jam_in > '07:30')
    <span class="badge bg-danger"> Terlambat</span>
    @else
        <span class="badge bg-success">Tepat Waktu</span>
@endif
</td>
         
    </tr>

@endforeach