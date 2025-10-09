<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Laporan Presensi - A4</title>

  <!-- Normalize or reset CSS with your favorite library -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

  <!-- Load paper.css for happy printing -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

  <!-- Set page size here: A5, A4 or A3 -->
  <!-- Set also "landscape" if you need -->
  <style>
  @page { size: A4 }
  
  #title {
    font-size: 20px;
    font-weight: bold; 
    font-family: Arial, Helvetica, sans-serif;
  }

  .tabeldatakaryawan {
    margin-top: 40px
  }

  .tabeldatakaryawan tr td {
    padding: 5px;
    font-size: 12px;
    font-family: Arial, Helvetica, sans-serif;
  }

  .tabelpresensi {
    margin-top: 20px;
    width: 100%;
    border-collapse: collapse;
  }

  .tabelpresensi tr th { 
    border: 1px solid black;
    padding: 8px;
    background-color: rgb(204, 204, 204);
    font-size: 12px;
    font-family: Arial, Helvetica, sans-serif;
  }
  
  .tabelpresensi tr td { 
    border: 1px solid black;
    padding: 8px;
    font-size: 13px;
    font-family: Arial, Helvetica, sans-serif;
    vertical-align: middle;
    text-align: center;
  }

  /* Perbaikan untuk foto karyawan agar tidak gepeng */
  .foto-karyawan {
    width: 120px;
    height: 150px;
    object-fit: cover;
    object-position: center;
    border: 2px solid #ddd;
    border-radius: 5px;
  }

  /* Perbaikan untuk foto absensi agar tidak gepeng */
  .foto {
    width: 60px;
    height: 60px;
    object-fit: cover;
    object-position: center;
    border: 1px solid #ddd;
    border-radius: 3px;
    display: block;
    margin: 0 auto;
  }

  /* Logo PUPR */
  .logo-pupr {
    height: 75px;
    width: auto;
    object-fit: contain;
  }

  /* Styling untuk badge */
  .badge {
    display: inline-block;
    padding: 4px 8px;
    font-size: 11px;
    font-weight: bold;
    border-radius: 3px;
  }

  .bg-danger {
    background-color: #dc3545;
    color: white;
  }

  .badge-success {
    background-color: #28a745;
    color: white;
  }

  .badge-warning {
    background-color: #ffc107;
    color: #000;
  }

  /* Icon styling */
  .icon {
    display: inline-block;
    width: 30px;
    height: 30px;
  }
  </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body class="A4">

  <!-- Each sheet element should have the class "sheet" -->
  <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
  <section class="sheet padding-10mm">

    <table style="width:100%">
        <tr>
            <td style="width: 100px; vertical-align: middle;">
                <img src="{{ asset('assets/img/icon/puprlogo.png') }}" alt="Logo PUPR" class="logo-pupr">
            </td>
            <td style="vertical-align: middle;">
                <span id="title">
                    LAPORAN PRESENSI KARYAWAN <br>
                    PERIODE {{ strtoupper($namabulan[$bulan]) }} {{ $tahun }} <br>
                    PUPR Cipta Karya
                </span>
                <br>
                <span style="font-size: 11px;"><i>Jl. Gajah Mungkur Selatan No.14 - 16, Gajahmungkur, Kec. Gajahmungkur, Kota Semarang, Jawa Tengah 50232</i></span>
            </td>
        </tr>
    </table>

    <table class="tabeldatakaryawan">
        <tr>
            <td rowspan="6" style="padding-right: 20px; vertical-align: top;">
                @php
                    $path = Storage::url('uploads/karyawan/'.$karyawan->foto);
                @endphp
                <img src="{{ url($path) }}" alt="Foto Karyawan" class="foto-karyawan">
            </td>
        </tr>
        <tr>
            <td style="width: 150px;">NIK</td>
            <td style="width: 10px;">:</td>
            <td>{{ $karyawan->nik }}</td>
        </tr>
        <tr>
            <td>Nama Karyawan</td>
            <td>:</td>
            <td>{{ $karyawan->nama_lengkap }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>{{ $karyawan->jabatan }}</td>
        </tr>
        <tr>
            <td>Departemen</td>
            <td>:</td>
            <td>{{ $karyawan->nama_dept }}</td>
        </tr>
        <tr>
            <td>No HP</td>
            <td>:</td>
            <td>{{ $karyawan->no_hp }}</td>
        </tr>
    </table>

    <table class="tabelpresensi">
        <tr>
            <th style="width: 5%;">No.</th>
            <th style="width: 12%;">Tanggal</th>
            <th style="width: 10%;">Jam Masuk</th>
            <th style="width: 10%;">Foto Masuk</th>
            <th style="width: 10%;">Jam Pulang</th>
            <th style="width: 10%;">Foto Pulang</th>
            <th style="width: 13%;">Keterangan</th>
        </tr>
        @foreach ($presensi as $d)
        @php 
            $path_in = Storage::url('uploads/absensi/'.$d->foto_in);
            $path_out = Storage::url('uploads/absensi/'.$d->foto_out);
        @endphp
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ date("d-m-Y", strtotime($d->tgl_presensi)) }}</td>
            <td>{{ $d->jam_in }}</td>
            <td>
                <img src="{{ url($path_in) }}" alt="Foto Masuk" class="foto">
            </td>
            <td>
                {!! $d->jam_out != null ? $d->jam_out : '<span class="badge bg-danger">Belum Absen</span>' !!}
            </td>
            <td>
                @if ($d->jam_out != null)
                    <img src="{{ url($path_out) }}" alt="Foto Pulang" class="foto">
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M20.926 13.15a9 9 0 1 0 -7.835 7.784" />
                        <path d="M12 7v5l2 2" />
                        <path d="M22 22l-5 -5" />
                        <path d="M17 22l5 -5" />
                    </svg>
                @endif
            </td>
            <td>
                @if ($d->jam_in > '07:30')
                    <span class="badge badge-warning">Terlambat</span>
                @else
                    <span class="badge badge-success">Tepat Waktu</span>
                @endif
            </td>
        </tr>
        @endforeach
    </table>

  </section>

</body>

</html>