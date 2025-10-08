<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $hariini   = date("Y-m-d");
        $bulanini  = date("m");
        $tahunini  = date("Y");
        $nik       = Auth::guard('karyawan')->user()->nik;

        // Presensi hari ini
        $presensihariini = DB::table('presensi')
            ->where('nik', $nik)
            ->where('tgl_presensi', $hariini)
            ->first();

        // Histori bulan ini
        $histroribulanini = DB::table('presensi')
            ->where('nik', $nik)
            ->whereMonth('tgl_presensi', $bulanini)
            ->whereYear('tgl_presensi', $tahunini)
            ->orderBy('tgl_presensi', 'desc')
            ->get();

        // Rekap presensi (hadir dan terlambat)
        $rekappresensi = DB::table('presensi')
            ->selectRaw('COUNT(nik) as jmlhadir, SUM(IF(jam_in > "07:30:00", 1, 0)) as jmlterlambat')
            ->where('nik', $nik)
            ->whereMonth('tgl_presensi', $bulanini)
            ->whereYear('tgl_presensi', $tahunini)
            ->first();

        // Leaderboard absensi hari ini
        $leaderboard = DB::table('presensi')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->where('tgl_presensi', $hariini)
            ->orderBy('jam_in')
            ->get();

        // Nama bulan
        $namabulan = [
            "", "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];

        // ✅ Rekap izin termasuk cuti
        $rekapizin = DB::table('pengajuan_izin')
            ->selectRaw('
                SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as jmlizin,
                SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as jmlsakit,
                SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) as jmlcuti
            ')
            ->where('nik', $nik)
            ->whereMonth('tgl_izin', $bulanini)
            ->whereYear('tgl_izin', $tahunini)
            ->where('status_approved', 1)
            ->first();

        return view('dashboard.dashboard', compact(
            'presensihariini',
            'histroribulanini',
            'namabulan',
            'bulanini',
            'tahunini',
            'rekappresensi',
            'leaderboard',
            'rekapizin'
        ));
    }

    public function dashboardadmin()
    {
        $hariini = date("Y-m-d");

        // Rekap presensi
        $rekappresensi = DB::table('presensi')
            ->selectRaw('COUNT(nik) as jmlhadir, SUM(IF(jam_in > "07:30:00",1,0)) as jmlterlambat')
            ->where('tgl_presensi', $hariini)
            ->first();

        // ✅ Tambahkan hitungan cuti juga di admin
        $rekapizin = DB::table('pengajuan_izin')
            ->selectRaw('
                SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as jmlizin,
                SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as jmlsakit,
                SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) as jmlcuti
            ')
            ->where('tgl_izin', $hariini)
            ->where('status_approved', 1)
            ->first();

        return view('dashboard.dashboardadmin', compact('rekappresensi', 'rekapizin'));
    }
}
