<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    public function create()
    {
        $hariini = date('Y-m-d');
        $nik = Auth::guard('karyawan')->user()->nik;

        $cek = DB::table('presensi')
            ->where('tgl_presensi', $hariini)
            ->where('nik', $nik)
            ->count();

        return view('presensi.create', compact('cek'));
    }

    public function store(Request $request)
    {
        try {
            $nik          = Auth::guard('karyawan')->user()->nik;
            $tgl_presensi = date('Y-m-d');
            $jam          = date('H:i:s');
            $lokasi       = $request->lokasi;
            $image        = $request->image;

            // folder tujuan penyimpanan
            $folderPath  = 'public/uploads/absensi/';
            $formatName  = $nik . "-" . $tgl_presensi . "-" . time();

            // pecah base64 string
            $image_parts = explode(";base64,", $image);
            if (count($image_parts) < 2) {
                echo "error|Format foto tidak valid|x";
                return;
            }

            $image_base64 = base64_decode($image_parts[1]);
            $fileName     = $formatName . '.png';
            $file         = $folderPath . $fileName;

            // cek apakah sudah absen hari ini
            $cek = DB::table('presensi')
                ->where('tgl_presensi', $tgl_presensi)
                ->where('nik', $nik)
                ->first();

            if (!$cek) {
                // ============== Absen Masuk ==============
                $data_masuk = [
                    'nik'          => $nik,
                    'tgl_presensi' => $tgl_presensi,
                    'jam_in'       => $jam,
                    'foto_in'      => $fileName,
                    'lokasi_in'    => $lokasi,
                ];

                $simpan = DB::table('presensi')->insert($data_masuk);

                if ($simpan) {
                    Storage::put($file, $image_base64);
                    echo "success|Terima Kasih, Selamat Bekerja!|in";
                    return;
                }
            } else {
                // ============== Absen Pulang ==============
                $data_pulang = [
                    'jam_out'    => $jam,
                    'foto_out'   => $fileName,
                    'lokasi_out' => $lokasi,
                ];

                $update = DB::table('presensi')
                    ->where('tgl_presensi', $tgl_presensi)
                    ->where('nik', $nik)
                    ->update($data_pulang);

                if ($update) {
                    Storage::put($file, $image_base64);
                    echo "success|Terima Kasih, Hati-hati di jalan!|out";
                    return;
                }
            }

            echo "error|Gagal menyimpan presensi|x";

        } catch (\Exception $e) {
            echo "error|Terjadi kesalahan: " . $e->getMessage() . "|x";
        }
    }
}
