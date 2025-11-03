<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PresensiController extends Controller
{
    /**
     * APPROVAL IZIN/SAKIT - dengan Push Notification
     */
    public function approveizinsakit(Request $request)
    {
        try {
            $id_izinsakit = $request->id_izinsakit_form;
            $status_approved = $request->status_approved;
            $alasan_tolak = $request->alasan_tolak;
            $nik_karyawan = $request->nik_karyawan; // NIK dari form

            // Get data izin untuk info lengkap
            $izin = DB::table('pengajuan_izin')
                ->where('id', $id_izinsakit)
                ->first();

            if (!$izin) {
                return redirect()->back()->with('error', 'Data izin tidak ditemukan');
            }

            // Update status
            $update = DB::table('pengajuan_izin')
                ->where('id', $id_izinsakit)
                ->update([
                    'status_approved' => $status_approved,
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                    'alasan_tolak' => $alasan_tolak
                ]);

            if ($update) {
                // 🔔 TRIGGER NOTIFICATION
                $tanggal = date('d/m/Y', strtotime($izin->tgl_izin));
                
                if ($status_approved == 1) {
                    // DISETUJUI
                    session()->flash('trigger_notification', [
                        'status' => 'approved',
                        'message' => "Pengajuan izin tanggal {$tanggal} telah disetujui!",
                        'user_id' => $nik_karyawan
                    ]);
                    
                    return redirect('/panel/dataizinsakit')->with('success', 'Izin berhasil disetujui! Karyawan akan menerima notifikasi.');
                    
                } else {
                    // DITOLAK
                    $alasan_text = $alasan_tolak ?: 'Tidak memenuhi syarat';
                    session()->flash('trigger_notification', [
                        'status' => 'rejected',
                        'message' => "Pengajuan izin ditolak. Alasan: {$alasan_text}",
                        'user_id' => $nik_karyawan
                    ]);
                    
                    return redirect('/panel/dataizinsakit')->with('success', 'Izin berhasil ditolak! Karyawan akan menerima notifikasi.');
                }
            }

            return redirect()->back()->with('error', 'Gagal memproses approval');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * BATALKAN APPROVAL
     */
    public function batalkanizinsakit($id)
    {
        try {
            $izin = DB::table('pengajuan_izin')
                ->where('id', $id)
                ->first();

            if (!$izin) {
                return redirect()->back()->with('error', 'Data tidak ditemukan');
            }

            $update = DB::table('pengajuan_izin')
                ->where('id', $id)
                ->update([
                    'status_approved' => 0,
                    'approved_by' => null,
                    'approved_at' => null,
                    'alasan_tolak' => null
                ]);

            if ($update) {
                return redirect('/panel/dataizinsakit')->with('success', 'Approval berhasil dibatalkan');
            }

            return redirect()->back()->with('error', 'Gagal membatalkan approval');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}