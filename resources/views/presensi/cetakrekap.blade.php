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
  }

  .foto{
    width : 40px;
    height : 30px;
  }
  </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body class="A3 landscape">



  <!-- Each sheet element should have the class "sheet" -->
  <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
  <section class="sheet padding-10mm">

    <table style="width:100%">
        <tr>
            <td style="width: 30px">
                <img src="{{ asset('assets/img/icon/puprlogo.png') }}" alt="avatar" class="imaged w64 rounded" style="height:75px">
            </td>
            <td>
                <span id="title">
                    REKAP PRESENSI KARYAWAN <br>
                    PERIODE {{ strtoupper ($namabulan [$bulan]) }} {{ $tahun }} <br>
                    PUPR Cipta Karya</br>
                </span>
                <span style=""><i>Jl. Gajah Mungkur Selatan No.14 - 16, Gajahmungkur, Kec. Gajahmungkur, Kota Semarang, Jawa Tengah 50232</i></span>
            </td>
        </tr>
    </table>

    <table class="tabelpresensi">
        <tr>
            <th rowspan="2">Nik</th>
            <th rowspan="2">Nama Karyawan</th>
            <th colspan="31">tanggal</th>
         <th rowspan="2">Total Hadir</th>
                  <th rowspan="2">Total Keterlambatan</th>
        </tr>
        <tr>
            <?php
            
            for($i=1; $i <= 31; $i++){
            ?>
            <th>{{ $i }} </th>
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
        <span style="color:<?= ($jam_in > '07:30:00') ? 'red' : '' ?>;">
            <?= $jam_in ?>
        </span>
        <br>
        <!-- Jam Pulang -->
        <span style="color:<?= ($jam_out < '16:00:00' && !empty($jam_out)) ? 'red' : '' ?>;">
            <?= $jam_out ?>
        </span>
    </td>
<?php
}
?>
            <td>{{ $totalhadir }}</td>
            <td>{{ $totalterlambat }}</td>
        </tr>

        @endforeach
    </table>
    
<table width="100%" style="margin-top:100px">
<tr>
    <td></td>
  <td  style="text-align: center">Semarang, {{ date('d-m-Y') }} </td>
</tr> 
<tr>
    <td style="text-align: right; vertical-align:bottom " height ="100px">
     <u> Deka Abdullah </u><br>
     <i><b>Kepala Keuangan</b></i>
    </td>
  </tr>
</table>
  </section>

</body>

</html>