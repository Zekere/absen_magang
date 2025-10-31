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
      margin: 0;
    }

    body {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 12px;
      color: #000;
    }

    body.A4 {
      background: #f5f5f5;
    }

    /* ✅ Sheet = 1 halaman A4 */
    .sheet {
      margin: 0;
      overflow: hidden;
      position: relative;
      box-sizing: border-box;
      page-break-after: always;
    }

    /* ✅ Padding dalam sheet */
    .sheet.padding-10mm {
      padding: 10mm;
    }

    #title {
      font-size: 18px;
      font-weight: bold;
      line-height: 1.4;
    }

    .tabeldatakaryawan {
      margin-top: 15px;
      margin-bottom: 15px;
      width: 100%;
    }

    .tabeldatakaryawan tr td {
      padding: 4px;
      font-size: 11px;
      vertical-align: top;
    }

    .tabeldatakaryawan tr td:nth-child(2) {
      width: 130px;
    }

    .tabeldatakaryawan tr td:nth-child(3) {
      width: 10px;
    }

    /* ✅ Tabel presensi dengan page break */
    .tabelpresensi {
      width: 100%;
      border-collapse: collapse;
      page-break-inside: auto;
    }

    .tabelpresensi thead {
      display: table-header-group; /* ✅ Header muncul di setiap halaman */
    }

    .tabelpresensi tr {
      page-break-inside: avoid;
      page-break-after: auto;
    }

    .tabelpresensi th,
    .tabelpresensi td {
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

    /* ✅ Foto proporsional */
    img.foto,
    img.foto-karyawan {
      object-fit: cover;
      border-radius: 4px;
    }

    img.foto {
      width: 50px;
      height: 50px;
      border: 1px solid #ddd;
    }

    img.foto-karyawan {
      width: 110px;
      height: 140px;
      border: 2px solid #ccc;
    }

    /* ✅ Badge untuk status */
    .badge {
      padding: 3px 8px;
      border-radius: 4px;
      font-size: 9px;
      font-weight: bold;
      display: inline-block;
    }

    .badge-success {
      background-color: #28a745;
      color: white;
    }

    .badge-warning {
      background-color: #ffc107;
      color: #000;
    }

    .badge-danger {
      background-color: #dc3545;
      color: white;
    }

    /* ✅ Footer tanda tangan */
    .footer {
      width: 100%;
      text-align: right;
      margin-top: 30px;
      page-break-inside: avoid;
    }

    .footer td {
      vertical-align: bottom;
      font-size: 11px;
    }

    /* ✅ Untuk print */
    @media print {
      body {
        background: white;
        margin: 0;
        padding: 0;
      }

      .sheet {
        margin: 0;
        box-shadow: none;
        page-break-after: always;
      }

      .no-print {
        display: none !important;
      }

      /* ✅ Pastikan warna print keluar */
      body {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }

      .tabelpresensi thead {
        display: table-header-group;
      }

      /* ✅ Footer hanya muncul di halaman terakhir */
      .footer {
        page-break-inside: avoid;
      }
    }

    /* ✅ Tombol print (hilang saat di-print) */
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
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
      z-index: 9999;
      font-size: 14px;
    }

    .print-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
    }

    .print-button i {
      margin-right: 5px;
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

  <!-- ✅ Tombol Print (Hidden saat print) -->
  <button class="print-button no-print" onclick="window.print()">
    🖨️ Cetak PDF
  </button>

  <!-- ✅ Section = 1 Halaman A4 -->
  <section class="sheet padding-10mm">

    <!-- Header dengan Logo -->
    <table style="width:100%; margin-bottom: 10px;">
      <tr>
        <td style="width: 80px; vertical-align: top;">
          <img src="{{ asset('assets/img/icon/puprlogo.png') }}" alt="logo" style="height:70px">
        </td>
        <td style="vertical-align: top;">
          <span id="title">
            LAPORAN PRESENSI KARYAWAN <br>
            PERIODE {{ strtoupper($namabulan[$bulan]) }} {{ $tahun }} <br>
          </span>
          <span style="font-size: 14px; font-weight: bold;">PUPR Cipta Karya</span><br>
          <small style="font-size: 9px;"><i>Jl. Gajah Mungkur Selatan No.14 - 16, Gajahmungkur, Kota Semarang, Jawa Tengah 50232</i></small>
        </td>
      </tr>
    </table>

    <hr style="border: 1px solid #333; margin: 10px 0;">

    <!-- Data Karyawan -->
    <table class="tabeldatakaryawan">
      <tr>
        <td rowspan="6" width="130" style="vertical-align: top;">
          @php 
            $path = Storage::url('uploads/karyawan/'.$karyawan->foto); 
          @endphp
          <img src="{{ url($path) }}" alt="foto karyawan" class="foto-karyawan">
        </td>
      </tr>
      <tr>
        <td><strong>NIK</strong></td>
        <td>:</td>
        <td>{{ $karyawan->nik }}</td>
      </tr>
      <tr>
        <td><strong>Nama Karyawan</strong></td>
        <td>:</td>
        <td>{{ $karyawan->nama_lengkap }}</td>
      </tr>
      <tr>
        <td><strong>Jabatan</strong></td>
        <td>:</td>
        <td>{{ $karyawan->jabatan }}</td>
      </tr>
      <tr>
        <td><strong>Departemen</strong></td>
        <td>:</td>
        <td>{{ $karyawan->nama_dept }}</td>
      </tr>
      <tr>
        <td><strong>No HP</strong></td>
        <td>:</td>
        <td>{{ $karyawan->no_hp }}</td>
      </tr>
    </table>

    <!-- Tabel Presensi -->
    <table class="tabelpresensi">
      <thead>
        <tr>
          <th style="width: 4%;">No.</th>
          <th style="width: 10%;">Tanggal</th>
          <th style="width: 8%;">Jam Masuk</th>
          <th style="width: 12%;">Foto Masuk</th>
          <th style="width: 8%;">Jam Pulang</th>
          <th style="width: 12%;">Foto Pulang</th>
          <th style="width: 15%;">Keterangan</th>
          <th style="width: 10%;">Jam Kerja</th>
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
            <td>
              <img src="{{ url($path_in) }}" alt="foto masuk" class="foto">
            </td>
            <td>
              @if($d->jam_out)
                {{ $d->jam_out }}
              @else
                <span class="badge badge-danger">Belum Absen</span>
              @endif
            </td>
            <td>
              @if ($d->jam_out)
                <img src="{{ url($path_out) }}" alt="foto pulang" class="foto">
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

    <!-- Footer Tanda Tangan -->
    <table class="footer">
      <tr>
        <td style="padding-right: 50px;">
          <p>Semarang, {{ date('d F Y') }}</p>
          <p><strong>Mengetahui,</strong></p>
          <br><br><br>
          <p style="margin-top: 60px;">
            <u><strong>Deka Abdullah</strong></u><br>
            <i>Kepala Keuangan</i>
          </p>
        </td>
      </tr>
    </table>

  </section>

  <script>
    // Optional: Auto print saat halaman dimuat
    // window.onload = function() { window.print(); }
  </script>

</body>
</html>