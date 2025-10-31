<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Laporan Presensi Karyawan</title>

  <style>
    @page {
      size: A4 portrait;
      margin: 15mm;
    }

    body {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 12px;
      color: #000;
    }

    #title {
      font-size: 18px;
      font-weight: bold;
      line-height: 1.4;
      text-align: center;
      margin-bottom: 5px;
    }

    .wrapper {
      width: 95%;
      margin: 0 auto;
    }

    hr {
      border: 1px solid #333;
      margin: 10px 0;
    }

    .tabeldatakaryawan {
      margin-top: 10px;
      margin-bottom: 10px;
      width: 100%;
      border-collapse: collapse;
    }

    .tabeldatakaryawan td {
      padding: 4px;
      font-size: 11px;
      vertical-align: top;
    }

    /* ✅ Tabel presensi auto-break */
    .tabelpresensi {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      page-break-inside: auto;
      table-layout: fixed;
      word-wrap: break-word;
    }

    .tabelpresensi thead {
      display: table-header-group;
    }

    .tabelpresensi tbody {
      display: table-row-group;
    }

    .tabelpresensi tr {
      page-break-inside: avoid;
    }

    .tabelpresensi th, .tabelpresensi td {
      border: 1px solid #000;
      padding: 6px 4px;
      text-align: center;
      font-size: 10px;
      vertical-align: middle;
    }

    .tabelpresensi th {
      background-color: #e0e0e0;
      font-weight: bold;
    }

    .tabelpresensi tbody tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    img.foto {
      width: 45px;
      height: 45px;
      object-fit: cover;
      border-radius: 4px;
      border: 1px solid #ccc;
    }

    img.foto-karyawan {
      width: 100px;
      height: 130px;
      border: 2px solid #ccc;
      object-fit: cover;
    }

    .badge {
      padding: 3px 6px;
      border-radius: 4px;
      font-size: 9px;
      font-weight: bold;
    }

    .badge-success {
      background-color: #28a745;
      color: #fff;
    }

    .badge-warning {
      background-color: #ffc107;
      color: #000;
    }

    .badge-danger {
      background-color: #dc3545;
      color: #fff;
    }

    .footer {
      width: 100%;
      text-align: right;
      margin-top: 20px;
      page-break-inside: avoid;
    }

    @media print {
      body {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }

      .no-print {
        display: none;
      }

      .footer {
        position: relative;
        page-break-inside: avoid;
      }
    }

    .print-button {
      position: fixed;
      top: 20px;
      right: 20px;
      padding: 12px 24px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      z-index: 9999;
    }
  </style>
</head>

<body>
  <button class="print-button no-print" onclick="window.print()">🖨 Cetak PDF</button>
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
  <div class="wrapper">
    <!-- Header -->
    <table style="width:100%; margin-bottom: 10px;">
      <tr>
        <td style="width: 80px; vertical-align: top;">
          <img src="{{ asset('assets/img/icon/puprlogo.png') }}" alt="logo" style="height:70px">
        </td>
        <td style="text-align:center;">
          <div id="title">
            LAPORAN PRESENSI KARYAWAN<br>
            PERIODE {{ strtoupper($namabulan[$bulan]) }} {{ $tahun }}<br>
          </div>
          <span style="font-size: 14px; font-weight: bold;">PUPR Cipta Karya</span><br>
          <small><i>Jl. Gajah Mungkur Selatan No.14 - 16, Kota Semarang, Jawa Tengah 50232</i></small>
        </td>
      </tr>
    </table>

    <hr>

    <!-- Data Karyawan -->
    <table class="tabeldatakaryawan">
      <tr>
        <td rowspan="6" width="130" style="vertical-align: top;">
          @php $path = Storage::url('uploads/karyawan/'.$karyawan->foto); @endphp
          <img src="{{ url($path) }}" alt="foto karyawan" class="foto-karyawan">
        </td>
      </tr>
      <tr><td><strong>NIK</strong></td><td>:</td><td>{{ $karyawan->nik }}</td></tr>
      <tr><td><strong>Nama Karyawan</strong></td><td>:</td><td>{{ $karyawan->nama_lengkap }}</td></tr>
      <tr><td><strong>Jabatan</strong></td><td>:</td><td>{{ $karyawan->jabatan }}</td></tr>
      <tr><td><strong>Departemen</strong></td><td>:</td><td>{{ $karyawan->nama_dept }}</td></tr>
      <tr><td><strong>No HP</strong></td><td>:</td><td>{{ $karyawan->no_hp }}</td></tr>
    </table>

    <!-- Tabel Presensi -->
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
          <th>Jam Kerja</th>
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
            <td><img src="{{ url($path_in) }}" class="foto"></td>
            <td>
              @if($d->jam_out)
                {{ $d->jam_out }}
              @else
                <span class="badge badge-danger">Belum Absen</span>
              @endif
            </td>
            <td>
              @if ($d->jam_out)
                <img src="{{ url($path_out) }}" class="foto">
              @else
                <span style="font-size: 9px; color: #999;">-</span>
              @endif
            </td>
            <td>
              @if ($d->jam_in > '07:30:00')
                <span class="badge badge-warning">Terlambat {{ $jamterlambat }}</span>
              @else
                <span class="badge badge-success">Tepat Waktu</span>
              @endif
            </td>
            <td>
              @if ($d->jam_out)
                @php $jmljamkerja = selisih($d->jam_in, $d->jam_out); @endphp
                <strong>{{ $jmljamkerja }}</strong>
              @else
                <span style="color: #999;">0</span>
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
      <p>Semarang, {{ date('d F Y') }}</p>
      <p><strong>Mengetahui,</strong></p>
      <br><br><br>
      <p><u><strong>Deka Abdullah</strong></u><br><i>Kepala Keuangan</i></p>
    </div>
  </div>
</body>
</html>