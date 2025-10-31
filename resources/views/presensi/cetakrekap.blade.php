<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Rekap Presensi Karyawan</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

  <style>
    @page {
      size: A4 landscape;
      margin: 15mm;
    }

    body {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 11px;
      color: #000;
      background: #fff;
    }

    .wrapper {
      width: 100%;
      margin: 0 auto;
    }

    #title {
      font-size: 16px;
      font-weight: bold;
      text-align: center;
      line-height: 1.4;
    }

    .address-text {
      font-size: 10px;
      font-style: italic;
      text-align: center;
      display: block;
    }

    /* Header */
    .header-table {
      width: 100%;
      margin-bottom: 10px;
    }

    .logo-cell {
      width: 80px;
      vertical-align: middle;
    }

    .logo-img {
      height: 60px;
      width: auto;
    }

    /* Tabel Presensi */
    .tabelpresensi {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      table-layout: fixed;
      page-break-inside: auto;
    }

    .tabelpresensi thead {
      display: table-header-group;
    }

    .tabelpresensi tbody {
      display: table-row-group;
    }

    .tabelpresensi th,
    .tabelpresensi td {
      border: 1px solid #000;
      padding: 3px 2px;
      font-size: 9px;
      text-align: center;
      vertical-align: middle;
      word-wrap: break-word;
    }

    .tabelpresensi th {
      background-color: #e0e0e0;
      font-weight: bold;
    }

    .tabelpresensi tr {
      page-break-inside: avoid;
    }

    .tabelpresensi tbody tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .time-in,
    .time-out {
      display: block;
      line-height: 1.2;
    }

    /* Signature (footer) */
    .signature-table {
      width: 100%;
      margin-top: 30px;
      page-break-inside: avoid;
    }

    .signature-cell {
      text-align: center;
      vertical-align: bottom;
    }

    .signature-name {
      text-decoration: underline;
      font-weight: bold;
      font-size: 11px;
    }

    .signature-title {
      font-style: italic;
      font-weight: bold;
      font-size: 10px;
    }

    /* Print Mode */
    @media print {
      body {
        background: #fff;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }

      .no-print {
        display: none !important;
      }

      .footer {
        page-break-inside: avoid;
      }
    }

    /* Tombol Print */
    .print-button {
      position: fixed;
      top: 20px;
      right: 20px;
      padding: 10px 20px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.3);
      z-index: 9999;
    }
  </style>
</head>

<body class="A4 landscape">
  <button class="print-button no-print" onclick="window.print()">🖨 Cetak PDF</button>

  <div class="wrapper">

    <!-- Header -->
    <table class="header-table">
      <tr>
        <td class="logo-cell">
          <img src="{{ asset('assets/img/icon/puprlogo.png') }}" alt="logo" class="logo-img">
        </td>
        <td style="text-align:center;">
          <div id="title">
            REKAP PRESENSI KARYAWAN<br>
            PERIODE {{ strtoupper($namabulan[$bulan]) }} {{ $tahun }}<br>
            PUPR Cipta Karya
          </div>
          <span class="address-text">Jl. Gajah Mungkur Selatan No.14 - 16, Gajahmungkur, Kota Semarang, Jawa Tengah 50232</span>
        </td>
      </tr>
    </table>

    <!-- Rekap Presensi -->
    <table class="tabelpresensi">
      <thead>
        <tr>
          <th rowspan="2" style="width: 60px;">NIK</th>
          <th rowspan="2" style="width: 120px;">Nama Karyawan</th>
          <th colspan="31">Tanggal</th>
          <th rowspan="2" style="width: 50px;">Total<br>Hadir</th>
          <th rowspan="2" style="width: 60px;">Total<br>Terlambat</th>
        </tr>
        <tr>
          @for ($i = 1; $i <= 31; $i++)
          <th style="width: 25px;">{{ $i }}</th>
          @endfor
        </tr>
      </thead>

      <tbody>
        @foreach ($rekap as $d)
        <tr>
          <td>{{ $d->nik }}</td>
          <td style="text-align:left;">{{ $d->nama_lengkap }}</td>

          @php
            $totalhadir = 0;
            $totalterlambat = 0;
          @endphp

          @for ($i = 1; $i <= 31; $i++)
            @php
              $tgl = "tgl_" . $i;
              if (empty($d->$tgl)) {
                  $hadir = ['', ''];
              } else {
                  $hadir = explode("-", $d->$tgl);
                  $totalhadir++;
                  if($hadir[0] > "07:30:00"){
                      $totalterlambat++;
                  }
              }
              $jam_in = $hadir[0] ?? '';
              $jam_out = $hadir[1] ?? '';
            @endphp
            <td>
              <span class="time-in" style="color: {{ ($jam_in > '07:30:00') ? 'red' : 'black' }};">{{ $jam_in }}</span>
              <span class="time-out" style="color: {{ ($jam_out < '16:00:00' && !empty($jam_out)) ? 'red' : 'black' }};">{{ $jam_out }}</span>
            </td>
          @endfor

          <td><strong>{{ $totalhadir }}</strong></td>
          <td><strong>{{ $totalterlambat }}</strong></td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <!-- Signature -->
    <table class="signature-table">
      <tr>
        <td style="width: 70%;"></td>
        <td class="signature-cell">
          <p>Semarang, {{ date('d F Y') }}</p>
          <br><br><br><br>
          <p class="signature-name">Deka Abdullah</p>
          <p class="signature-title">Kepala Keuangan</p>
        </td>
      </tr>
    </table>

  </div>
</body>
</html>
