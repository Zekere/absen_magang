<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Models\JamKerja;

class KonfigurasiController extends Controller
{
    // ==================== INDEX ====================
    public function index()
    {
        // Ambil semua lokasi kantor
        $lok_kantor  = DB::table('konfigurasi_lokasi')->where('id', 1)->first();
        $lokasi_list = DB::table('konfigurasi_lokasi')->orderBy('id')->get();
        $jamKerja    = JamKerja::getConfig();

        return view('konfigurasi.index', compact('lok_kantor', 'lokasi_list', 'jamKerja'));
    }

    // ==================== UPDATE LOKASI LAMA (id=1) ====================
    public function updatelokasikantor(Request $request)
    {
        $request->validate([
            'lokasi_kantor' => 'required|string',
            'radius'        => 'required|integer|min:1',
        ]);

        $update = DB::table('konfigurasi_lokasi')
            ->where('id', 1)
            ->update([
                'lokasi_kantor' => $request->lokasi_kantor,
                'radius'        => $request->radius,
            ]);

        if ($update) {
            return Redirect::back()->with('success', 'Data Berhasil Diupdate');
        }
        return Redirect::back()->with('warning', 'Tidak ada perubahan data');
    }

    // ==================== STORE (Tambah Lokasi Baru) ====================
    public function storelokasi(Request $request)
    {
        $request->validate([
            'nama_kantor'   => 'required|string|max:100',
            'lokasi_kantor' => 'required|string',
            'radius'        => 'required|integer|min:1',
        ]);

        // Cek duplikat nama
        $exists = DB::table('konfigurasi_lokasi')
            ->where('nama_kantor', $request->nama_kantor)
            ->exists();

        if ($exists) {
            return Redirect::back()->with('error', 'Nama kantor sudah ada, gunakan nama lain.');
        }

        DB::table('konfigurasi_lokasi')->insert([
            'nama_kantor'   => $request->nama_kantor,
            'lokasi_kantor' => $request->lokasi_kantor,
            'radius'        => $request->radius,
        ]);

        return Redirect::back()->with('success', 'Lokasi kantor berhasil ditambahkan!');
    }

    // ==================== UPDATE (Edit Lokasi) ====================
    public function updatelokasi(Request $request, $id)
    {
        $request->validate([
            'nama_kantor'   => 'required|string|max:100',
            'lokasi_kantor' => 'required|string',
            'radius'        => 'required|integer|min:1',
        ]);

        // Cek duplikat nama (kecuali dirinya sendiri)
        $exists = DB::table('konfigurasi_lokasi')
            ->where('nama_kantor', $request->nama_kantor)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return Redirect::back()->with('error', 'Nama kantor sudah digunakan, gunakan nama lain.');
        }

        DB::table('konfigurasi_lokasi')->where('id', $id)->update([
            'nama_kantor'   => $request->nama_kantor,
            'lokasi_kantor' => $request->lokasi_kantor,
            'radius'        => $request->radius,
        ]);

        return Redirect::back()->with('success', 'Lokasi kantor berhasil diupdate!');
    }

    // ==================== DELETE (Hapus Lokasi) ====================
    public function deletelokasi($id)
    {
        // Cegah hapus lokasi utama (id=1)
        if ($id == 1) {
            return Redirect::back()->with('error', 'Lokasi utama tidak dapat dihapus!');
        }

        DB::table('konfigurasi_lokasi')->where('id', $id)->delete();

        return Redirect::back()->with('success', 'Lokasi kantor berhasil dihapus!');
    }

    // ==================== UPDATE JAM KERJA ====================
    public function updatejamkerja(Request $request)
    {
        $request->validate([
            'jam_masuk'                  => 'required',
            'jam_pulang'                 => 'required',
            'toleransi_keterlambatan'    => 'required|integer|min:0',
            'batas_absen_masuk_awal'     => 'required|integer|min:0',
            'batas_absen_masuk_akhir'    => 'required|integer|min:0',
            'batas_absen_pulang_sebelum' => 'required|integer|min:0',
        ]);

        try {
            $jamKerja = JamKerja::first();

            if ($jamKerja) {
                $jamKerja->update([
                    'jam_masuk'                  => $request->jam_masuk,
                    'jam_pulang'                 => $request->jam_pulang,
                    'toleransi_keterlambatan'    => $request->toleransi_keterlambatan,
                    'batas_absen_masuk_awal'     => $request->batas_absen_masuk_awal,
                    'batas_absen_masuk_akhir'    => $request->batas_absen_masuk_akhir,
                    'batas_absen_pulang_sebelum' => $request->batas_absen_pulang_sebelum,
                ]);
            } else {
                JamKerja::create([
                    'jam_masuk'                  => $request->jam_masuk,
                    'jam_pulang'                 => $request->jam_pulang,
                    'toleransi_keterlambatan'    => $request->toleransi_keterlambatan,
                    'batas_absen_masuk_awal'     => $request->batas_absen_masuk_awal,
                    'batas_absen_masuk_akhir'    => $request->batas_absen_masuk_akhir,
                    'batas_absen_pulang_sebelum' => $request->batas_absen_pulang_sebelum,
                ]);
            }

            return Redirect::back()->with('success', 'Konfigurasi jam kerja berhasil diupdate');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', 'Konfigurasi jam kerja gagal diupdate: ' . $e->getMessage());
        }
    }
}