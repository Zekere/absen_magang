<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\JamKerja; // ⭐ TAMBAHAN: Import Model JamKerja

class DashboardController extends Controller
{
    public function index()
    {
        $hariini   = date("Y-m-d");
        $bulanini  = date("m");
        $tahunini  = date("Y");
        $nik       = Auth::guard('karyawan')->user()->nik;

        // ⭐ TAMBAHAN: Ambil konfigurasi jam kerja
        $jamKerja = JamKerja::getConfig();
        $jam_masuk = $jamKerja ? $jamKerja->jam_masuk : '07:30:00';

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

        // ⭐ UPDATED: Rekap presensi dengan jam masuk dari database
        $rekappresensi = DB::table('presensi')
            ->selectRaw('COUNT(nik) as jmlhadir, SUM(IF(jam_in > "' . $jam_masuk . '", 1, 0)) as jmlterlambat')
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

        // Rekap izin termasuk cuti
        $rekapizin = DB::table('pengajuan_izin')
            ->selectRaw('
                SUM(CASE WHEN status = "1" THEN 1 ELSE 0 END) as jmlizin,
                SUM(CASE WHEN status = "2" THEN 1 ELSE 0 END) as jmlsakit,
                SUM(CASE WHEN status = "3" THEN 1 ELSE 0 END) as jmlcuti
            ')
            ->where('nik', $nik)
            ->whereMonth('tgl_izin', $bulanini)
            ->whereYear('tgl_izin', $tahunini)
            ->where('status_approved', '1')
            ->first();

        // ⭐ TAMBAHAN: Pass jamKerja ke view
        return view('dashboard.dashboard', compact(
            'presensihariini',
            'histroribulanini',
            'namabulan',
            'bulanini',
            'tahunini',
            'rekappresensi',
            'leaderboard',
            'rekapizin',
            'jamKerja'  // ⭐ TAMBAH INI
        ));
    }

    public function dashboardadmin()
    {
        $hariini = date("Y-m-d");

        // ⭐ TAMBAHAN: Ambil konfigurasi jam kerja
        $jamKerja = JamKerja::getConfig();
        $jam_masuk = $jamKerja ? $jamKerja->jam_masuk : '07:30:00';

        // Total karyawan
        $jmlkaryawan = DB::table('karyawan')->count();

        // ⭐ UPDATED: Rekap presensi hari ini dengan jam masuk dari database
        $rekappresensi = DB::table('presensi')
            ->selectRaw('COUNT(nik) as jmlhadir, SUM(IF(jam_in > "' . $jam_masuk . '",1,0)) as jmlterlambat')
            ->where('tgl_presensi', $hariini)
            ->first();

        // Rekap izin/sakit/cuti hari ini
        $rekapizin = DB::table('pengajuan_izin')
            ->selectRaw('
                SUM(CASE WHEN status = "1" THEN 1 ELSE 0 END) as jmlizin,
                SUM(CASE WHEN status = "2" THEN 1 ELSE 0 END) as jmlsakit,
                SUM(CASE WHEN status = "3" THEN 1 ELSE 0 END) as jmlcuti
            ')
            ->where('tgl_izin', $hariini)
            ->where('status_approved', '1')
            ->first();

        return view('dashboard.dashboardadmin', compact('jmlkaryawan', 'rekappresensi', 'rekapizin'));
    }

    /**
     * Get dashboard data berdasarkan tanggal yang dipilih (untuk AJAX)
     */
    public function getDashboardData(Request $request)
    {
        $date = $request->input('date'); // Format: Y-m-d
        
        // Validasi tanggal
        if (!$date || !strtotime($date)) {
            return response()->json([
                'error' => 'Invalid date format'
            ], 400);
        }

        // ⭐ TAMBAHAN: Ambil konfigurasi jam kerja
        $jamKerja = JamKerja::getConfig();
        $jam_masuk = $jamKerja ? $jamKerja->jam_masuk : '07:30:00';
        
        // Total karyawan (tidak berubah berdasarkan tanggal)
        $jmlkaryawan = DB::table('karyawan')->count();
        
        // ⭐ UPDATED: Rekap presensi dengan jam masuk dari database
        $rekappresensi = DB::table('presensi')
            ->selectRaw('COUNT(nik) as jmlhadir, SUM(IF(jam_in > "' . $jam_masuk . '", 1, 0)) as jmlterlambat')
            ->where('tgl_presensi', $date)
            ->first();
        
        // Rekap izin berdasarkan tanggal yang dipilih
        $rekapizin = DB::table('pengajuan_izin')
            ->selectRaw('
                SUM(CASE WHEN status = "1" THEN 1 ELSE 0 END) as jmlizin,
                SUM(CASE WHEN status = "2" THEN 1 ELSE 0 END) as jmlsakit,
                SUM(CASE WHEN status = "3" THEN 1 ELSE 0 END) as jmlcuti
            ')
            ->where('tgl_izin', $date)
            ->where('status_approved', '1')
            ->first();
        
        return response()->json([
            'success' => true,
            'date' => $date,
            'jmlkaryawan' => $jmlkaryawan ?? 0,
            'jmlhadir' => $rekappresensi->jmlhadir ?? 0,
            'jmlterlambat' => $rekappresensi->jmlterlambat ?? 0,
            'jmlizin' => $rekapizin->jmlizin ?? 0,
            'jmlsakit' => $rekapizin->jmlsakit ?? 0,
            'jmlcuti' => $rekapizin->jmlcuti ?? 0,
        ]);
    }
}