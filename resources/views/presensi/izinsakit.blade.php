@extends ('layout.admin.template')
@section('content')


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid mt-4">
  <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-2">
    <div class="col">
      <h3 class="fw-bold mb-2">Data Izin dan Sakit </h3>
      <h5 class="text-muted mb-3"> Izin sakit</h5>
    </div>
  </div>

 <div class="page-body">
  <div class="container-xl">
    <div class="row">
      <div class="col-12">
        <div class="table-responsive"> {{-- Tambahan agar tabel bisa digeser --}}
          <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark text-center">
              <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>NIK</th>
                <th>Nama Karyawan</th>
                <th>Jabatan</th>
                <th>Status</th>
                <th>Keterangan</th>
                <th>Persetujuan</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($izinsakit as $d)
                <tr>
                  <td class="text-center">{{ $loop->iteration }}</td>
                  <td>{{ date('d-m-Y', strtotime($d->tgl_izin)) }}</td>
                  <td>{{ $d->nik }}</td>
                  <td>{{ $d->nama_lengkap }}</td>
                  <td>{{ $d->jabatan }}</td>
<td>
  @if ($d->status == "1")
    Izin
  @elseif ($d->status == "2")
    Sakit
  @elseif ($d->status == "3")
    Cuti
  @else
    Tidak Diketahui
  @endif
</td>
                  <td>{{ $d->keterangan }}</td>
                  <td class="text-center">
                    @if ($d->status_approved == 1)
                      <span class="badge bg-success">Disetujui</span>
                    @elseif ($d->status_approved == 2)
                      <span class="badge bg-danger">Ditolak</span>
                    @else
                      <span class="badge bg-warning text-dark">Pending</span>
                    @endif
                  </td>
                  <td class="text-center">

                  @if($d->status_approved==0)

                <a href="#" class="btn btn-sm btn-primary" id="approved" id_izinsakit="{{ $d->id }}">
  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-external-link">
    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
    <path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6"></path>
    <path d="M11 13l9 -9"></path>
    <path d="M15 4h5v5"></path>
  </svg>
</a>
                    @else
                    <a href="/presensi/{{ $d->id }}/batalkanizinsakit" class="btn btn-sm btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-circle-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M10 10l4 4m0 -4l-4 4" />Batalkan</svg>
                    </a>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div> {{-- Tutup div table-responsive --}}
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-izinsakit" tabindex="-1" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow-lg rounded-3">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold"><i class="bi bi-check-circle-fill me-2"></i> Persetujuan </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="/presensi/approved" method = "POST">
            @csrf 
            <input type="hidden" id="id_izinsakit_form" name="id_izinsakit_form">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <select name="status_approved" id="status_approved" class="form-select">
                            <option value="1">Disetujui</option>
                            <option value="2">Ditolak</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form">
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-send"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14l11 -11" /><path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" /></svg>
                        Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

      </div>

    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
    $(function(){
        $("#approved").click(function(e){
e.preventDefault();
var id_izinsakit = $(this).attr("id_izinsakit");
$("#id_izinsakit_form").val(id_izinsakit);
$("#modal-izinsakit").modal("show")
        });
    });
</script>
@endpush