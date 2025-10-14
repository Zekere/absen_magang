<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Laporan Presensi Karyawan</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

  <style>
    @page {
      size: A4;
      margin: 10mm;
    }

    body {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 12px;
      color: #000;
    }

    #title {
      font-size: 20px;
      font-weight: bold;
    }

    .tabeldatakaryawan {
      margin-top: 20px;
      width: 100%;
    }

    .tabeldatakaryawan tr td {
      padding: 5px;
      font-size: 12px;
      vertical-align: top;
    }

    .tabelpresensi {
      margin-top: 20px;
      width: 100%;
      border-collapse: collapse;
      page-break-inside: auto;
    }

    .tabelpresensi th, .tabelpresensi td {
      border: 1px solid #000;
      padding: 6px;
      text-align: center;
      font-size: 11px;
    }

    .tabelpresensi th {
      background-color: #e0e0e0;
    }

    /* ✅ Foto proporsional */
    img.foto, img.foto-karyawan {
      object-fit: cover;
      border-radius: 4px;
    }

    img.foto {
      width: 45px;
      height: 45px;
    }

    img.foto-karyawan {
      width: 120px;
      height: 150px;
      border: 1px solid #ccc;
    }

    /* ✅ Supaya tabel bisa lanjut ke halaman berikut */
    tr {
      page-break-inside: avoid;
      page-break-after: auto;
    }

    /* ✅ Footer tanda tangan tetap di bawah */
    .footer {
      width: 100%;
      text-align: right;
      margin-top: 50px;
      page-break-after: avoid;
    }

    .footer td {
      vertical-align: bottom;
    }
  </style>
</head>

<body class="A4">

@php
 function selisih($jam_masuk, $jam_keluar) {
     list($h, $m, $s) = explode(":", $jam_masuk);
     $dtAwal = mktime($h, $m, $s, "1", "1", "1");
     list($h, $m, $s) = explode(":", $jam_keluar);
     $dtAkhir = mktime($h, $m, $s, "1", "1", "1");
     $dtSelisih = $dtAkhir - $dtAwal;
     $totalmenit = $dtSelisih / 60;
     $jam = explode(".", $totalmenit / 60);
     $sisamenit = ($totalmenit / 60) - $jam[0];
     $sisamenit2 = $sisamenit * 60;
     $jml_jam = $jam[0];
     return $jml_jam . ":" . round($sisamenit2);
 }
@endphp

<section class="sheet padding-10mm">

  <table style="width:100%">
    <tr>
      <td style="width: 70px">
        <img src="{{ asset('assets/img/icon/puprlogo.png') }}" alt="logo" style="height:75px">
      </td>
      <td>
        <span id="title">
          LAPORAN PRESENSI KARYAWAN <br>
          PERIODE {{ strtoupper($namabulan[$bulan]) }} {{ $tahun }} <br>
          PUPR Cipta Karya
        </span><br>
        <small><i>Jl. Gajah Mungkur Selatan No.14 - 16, Gajahmungkur, Kota Semarang, Jawa Tengah 50232</i></small>
      </td>
    </tr>
  </table>

  <table class="tabeldatakaryawan">
    <tr>
      <td rowspan="6" width="140">
        @php $path = Storage::url('uploads/karyawan/'.$karyawan->foto); @endphp
        <img src="{{ url($path) }}" alt="foto karyawan" class="foto-karyawan">
      </td>
      <td>NIK</td><td>:</td><td>{{ $karyawan->nik }}</td>
    </tr>
    <tr><td>Nama Karyawan</td><td>:</td><td>{{ $karyawan->nama_lengkap }}</td></tr>
    <tr><td>Jabatan</td><td>:</td><td>{{ $karyawan->jabatan }}</td></tr>
    <tr><td>Departemen</td><td>:</td><td>{{ $karyawan->nama_dept }}</td></tr>
    <tr><td>No HP</td><td>:</td><td>{{ $karyawan->no_hp }}</td></tr>
  </table>

  <table class="tabelpresensi">
    <thead>
      <tr>
        <th>No.</th>
        <th>Tanggal</th>
        <th>Jam Masuk</th>
        <th>Foto Masuk</th>
        <th>Jam Pulang</th>
        <th>Foto Pulang</th>
        <th>Keterangan</th>
        <th>Jumlah Jam Kerja</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($presensi as $d)
        @php 
          $path_in = Storage::url('uploads/absensi/'.$d->foto_in);
          $path_out = Storage::url('uploads/absensi/'.$d->foto_out);
          $jamterlambat = selisih('07:30:00', $d->jam_in);
        @endphp
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ date("d-m-Y", strtotime($d->tgl_presensi)) }}</td>
          <td>{{ $d->jam_in }}</td>
          <td><img src="{{ url($path_in) }}" alt="foto masuk" class="foto"></td>
          <td>{!! $d->jam_out != null ? $d->jam_out : '<span class="badge bg-danger">Belum absen</span>' !!}</td>
          <td>
            @if ($d->jam_out)
              <img src="{{ url($path_out) }}" alt="foto pulang" class="foto">
            @else
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 22l-5 -5M17 22l5 -5M12 7v5l2 2M20.926 13.15a9 9 0 1 0 -7.835 7.784"/>
              </svg>
            @endif
          </td>
          <td>
            @if ($d->jam_in > '07:30')
              Terlambat ({{ $jamterlambat }})
            @else
              Tepat Waktu
            @endif
          </td>
          <td>
            @if ($d->jam_out)
              @php $jmljamkerja = selisih($d->jam_in, $d->jam_out); @endphp
            @else
              @php $jmljamkerja = 0; @endphp
            @endif
            {{ $jmljamkerja }}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <table class="footer">
    <tr>
      <td>Semarang, {{ date('d-m-Y') }}</td>
    </tr>
    <tr>
      <td height="80" style="vertical-align: bottom;">
        <u><b>Deka Abdullah</b></u><br>
        <i>Kepala Keuangan</i>
      </td>
    </tr>
  </table>

</section>

</body>
</html>
