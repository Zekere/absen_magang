<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>A4</title>

  <!-- Normalize or reset CSS with your favorite library -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

  <!-- Load paper.css for happy printing -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

  <!-- Set page size here: A5, A4 or A3 -->
  <!-- Set also "landscape" if you need -->
  <style>
  @page { size: A4 landscape }
  
  body {
    font-family: Arial, Helvetica, sans-serif;
  }

  #title {
    font-size: 16px;
    font-weight: bold; 
    font-family: Arial, Helvetica, sans-serif;
    line-height: 1.4;
  }

  .header-table {
    width: 100%;
    margin-bottom: 15px;
  }

  .logo-cell {
    width: 80px;
    vertical-align: middle;
  }

  .logo-img {
    height: 60px;
    width: auto;
  }

  .tabeldatakaryawan {
    margin-top: 20px
  }

  .tabeldatakaryawan tr td {
    padding: 5px;
    font-size: 11px;
    font-family: Arial, Helvetica, sans-serif;
  }

  .tabelpresensi {
    margin-top: 10px;
    width: 100%;
    border-collapse: collapse;
  }

  .tabelpresensi tr th { 
    border: 1px solid black;
    padding: 4px 2px;
    background-color: rgb(204, 204, 204);
    font-size: 9px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: center;
  }
  
  .tabelpresensi tr td { 
    border: 1px solid black;
    padding: 4px 2px;
    font-size: 8px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: center;
  }

  .tabelpresensi tr td:first-child,
  .tabelpresensi tr td:nth-child(2) {
    text-align: left;
    font-size: 9px;
  }

  .time-in {
    display: block;
    line-height: 1.2;
  }

  .time-out {
    display: block;
    line-height: 1.2;
  }

  .signature-table {
    width: 100%;
    margin-top: 30px;
  }

  .signature-cell {
    text-align: center;
    vertical-align: bottom;
  }

  .signature-name {
    text-decoration: underline;
    font-weight: normal;
    font-size: 11px;
  }

  .signature-title {
    font-style: italic;
    font-weight: bold;
    font-size: 10px;
  }

  .address-text {
    font-size: 11px;
    font-style: italic;
  }

  </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body class="A4 landscape">

  <!-- Each sheet element should have the class "sheet" -->
  <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
  <section class="sheet padding-10mm">

    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <img src="{{ asset('assets/img/icon/puprlogo.png') }}" alt="logo" class="logo-img">
            </td>
            <td>
                <span id="title">
                    REKAP PRESENSI KARYAWAN<br>
                    PERIODE {{ strtoupper ($namabulan [$bulan]) }} {{ $tahun }}<br>
                    PUPR Cipta Karya
                </span>
                <br>
                <span class="address-text">Jl. Gajah Mungkur Selatan No.14 - 16, Gajahmungkur, Kec. Gajahmungkur, Kota Semarang, Jawa Tengah 50232</span>
            </td>
        </tr>
    </table>

    <table class="tabelpresensi">
        <tr>
            <th rowspan="2">NIK</th>
            <th rowspan="2">Nama Karyawan</th>
            <th colspan="31">Tanggal</th>
            <th rowspan="2">Total<br>Hadir</th>
            <th rowspan="2">Total<br>Terlambat</th>
        </tr>
        <tr>
            <?php
            for($i=1; $i <= 31; $i++){
            ?>
            <th>{{ $i }}</th>
            <?php
            }
            ?>
        </tr>

        @foreach ( $rekap as $d )
        <tr>
            <td>{{ $d->nik }}</td>
            <td>{{ $d->nama_lengkap }}</td>
            <?php
            $totalhadir = 0;
            $totalterlambat = 0;
            for ($i = 1; $i <= 31; $i++) {
                $tgl = "tgl_" . $i;

                if (empty($d->$tgl)) {
                    $hadir = ['', ''];
                    $totalhadir += 0;
                } else {
                    $hadir = explode("-", $d->$tgl);
                    $totalhadir +=1;
                    if($hadir[0] > "07:30:00"){
                        $totalterlambat +=1;
                    }
                }

                // Ambil jam masuk dan jam keluar
                $jam_in  = $hadir[0] ?? '';
                $jam_out = $hadir[1] ?? '';
            ?>
                <td>
                    <!-- Jam Masuk -->
                    <span class="time-in" style="color:<?= ($jam_in > '07:30:00') ? 'red' : '' ?>;">
                        <?= $jam_in ?>
                    </span>
                    <!-- Jam Pulang -->
                    <span class="time-out" style="color:<?= ($jam_out < '16:00:00' && !empty($jam_out)) ? 'red' : '' ?>;">
                        <?= $jam_out ?>
                    </span>
                </td>
            <?php
            }
            ?>
            <td><strong>{{ $totalhadir }}</strong></td>
            <td><strong>{{ $totalterlambat }}</strong></td>
        </tr>
        @endforeach
    </table>
    
    <table class="signature-table">
        <tr>
            <td style="width: 60%"></td>
            <td class="signature-cell">
                <div>Semarang, {{ date('d-m-Y') }}</div>
                <div style="height: 50px;"></div>
                <div class="signature-name">Deka Abdullah</div>
                <div class="signature-title">Kepala Keuangan</div>
            </td>
        </tr>
    </table>

  </section>

</body>

</html>