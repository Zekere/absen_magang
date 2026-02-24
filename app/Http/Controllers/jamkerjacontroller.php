<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\JamKerja;

class JamKerjaController extends Controller
{
    /**
     * Tampilkan halaman konfigurasi jam kerja
     */
    public function index()
    {
        $jamKerja = JamKerja::getConfig();
        return view('jamkerja.index', compact('jamKerja'));
    }

    /**
     * Update konfigurasi jam kerja
     */
    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'jam_masuk' => 'required',
            'jam_pulang' => 'required',
            'toleransi_keterlambatan' => 'required|integer|min:0',
            'batas_absen_masuk_awal' => 'required|integer|min:0',
            'batas_absen_masuk_akhir' => 'required|integer|min:0',
            'batas_absen_pulang_sebelum' => 'required|integer|min:0',
        ]);

        try {
            $jamKerja = JamKerja::first();
            
            if ($jamKerja) {
                // Update data yang sudah ada
                $jamKerja->update([
                    'jam_masuk' => $request->jam_masuk,
                    'jam_pulang' => $request->jam_pulang,
                    'toleransi_keterlambatan' => $request->toleransi_keterlambatan,
                    'batas_absen_masuk_awal' => $request->batas_absen_masuk_awal,
                    'batas_absen_masuk_akhir' => $request->batas_absen_masuk_akhir,
                    'batas_absen_pulang_sebelum' => $request->batas_absen_pulang_sebelum,
                ]);
            } else {
                // Buat data baru jika belum ada
                JamKerja::create([
                    'jam_masuk' => $request->jam_masuk,
                    'jam_pulang' => $request->jam_pulang,
                    'toleransi_keterlambatan' => $request->toleransi_keterlambatan,
                    'batas_absen_masuk_awal' => $request->batas_absen_masuk_awal,
                    'batas_absen_masuk_akhir' => $request->batas_absen_masuk_akhir,
                    'batas_absen_pulang_sebelum' => $request->batas_absen_pulang_sebelum,
                ]);
            }

            return Redirect::back()->with(['success' => 'Konfigurasi jam kerja berhasil diupdate']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => 'Konfigurasi jam kerja gagal diupdate: ' . $e->getMessage()]);
        }
    }
}