@php
// Fungsi hitung selisih waktu (jam:menit)
function selisih($jam_masuk, $jam_keluar)
{
    if (!$jam_masuk || !$jam_keluar) return "0:00";

    $awal  = strtotime($jam_masuk);
    $akhir = strtotime($jam_keluar);

    if ($akhir < $awal) return "0:00";

    $selisihDetik = $akhir - $awal;
    $jam  = floor($selisihDetik / 3600);
    $menit = floor(($selisihDetik % 3600) / 60);

    return sprintf("%02d:%02d", $jam, $menit);
}
@endphp

@foreach ($presensi as $d)
    @php
        $foto_in  = $d->foto_in  ? Storage::url('uploads/absensi/' . $d->foto_in)  : null;
        $foto_out = $d->foto_out ? Storage::url('uploads/absensi/' . $d->foto_out) : null;
    @endphp

    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $d->nik }}</td>
        <td>{{ $d->nama_lengkap }}</td>
        <td>{{ $d->nama_dept }}</td>
        <td>{{ $d->jam_in }}</td>

        {{-- Foto Masuk --}}
        <td>
            @if ($foto_in)
                <img src="{{ $foto_in }}" class="avatar" alt="Foto Masuk">
            @else
                <span class="text-muted">Tidak ada foto</span>
            @endif
        </td>

        {{-- Jam Pulang --}}
        <td>
            @if ($d->jam_out)
                {{ $d->jam_out }}
            @else
                <span class="badge bg-danger">Belum absen</span>
            @endif
        </td>

        {{-- Foto Pulang --}}
        <td>
            @if ($foto_out)
                <img src="{{ $foto_out }}" class="avatar" alt="Foto Pulang">
            @else
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="icon icon-tabler icons-tabler-outline icon-tabler-clock-x">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M20.926 13.15a9 9 0 1 0 -7.835 7.784" />
                    <path d="M12 7v5l2 2" />
                    <path d="M22 22l-5 -5" />
                    <path d="M17 22l5 -5" />
                </svg>
            @endif
        </td>

        {{-- Status Keterlambatan --}}
        <td>
            @if ($d->jam_in > '07:30:00')
                @php $jamterlambat = selisih('07:30:00', $d->jam_in); @endphp
                <span class="badge bg-danger">Terlambat {{ $jamterlambat }}</span>
            @else
                <span class="badge bg-success">Tepat Waktu</span>
            @endif
        </td>

        {{-- Tombol Peta --}}
        <td>
            <a href="#" class="btn btn-primary map" data-id="{{ $d->id }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="icon icon-tabler icons-tabler-outline icon-tabler-map-2">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 18.5l-3 -1.5l-6 3v-13l6 -3l6 3l6 -3v7.5" />
                    <path d="M9 4v13" />
                    <path d="M15 7v5.5" />
                    <path d="M21.121 20.121a3 3 0 1 0 -4.242 0c.418 .419 1.125 1.045 2.121 1.879c1.051 -.89 1.759 -1.516 2.121 -1.879z" />
                    <path d="M19 18v.01" />
                </svg>
            </a>
        </td>
    </tr>
@endforeach

{{-- AJAX Peta --}}
<script>
$(function() {
    $(".map").on("click", function(e) {
        e.preventDefault();
        const id = $(this).data("id");

        $.ajax({
            type: "POST",
            url: "{{ url('/map') }}",
            data: {
                _token: "{{ csrf_token() }}",
                id: id
            },
            cache: false,
            success: function(respond) {
                $("#loadmap").html(respond);
                $("#modal-map").modal("show");
            },
            error: function(xhr, status, error) {
                console.error("Error AJAX:", error);
                alert("Gagal memuat peta. Coba lagi.");
            }
        });
    });
});
</script>
