<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class PresensiController extends Controller
{
    /**
     * TAMPILKAN HALAMAN IZIN/SAKIT
     */
    public function izin()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        
        $dataizin = DB::table('pengajuan_izin')
            ->where('nik', $nik)
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('presensi.izin', compact('dataizin'));
    }

    /**
     * TAMPILKAN FORM BUAT IZIN
     */
    public function buatizin()
    {
        return view('presensi.buatizin');
    }

    /**
     * SIMPAN DATA IZIN - AUTO APPROVE JIKA SAKIT
     */
    public function storeizin(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_izin = $request->tgl_izin;
        $status = $request->status; // 1=Izin, 2=Sakit, 3=Cuti
        $keterangan = $request->keterangan;
        
        // Validasi
        $request->validate([
            'tgl_izin' => 'required|date',
            'status' => 'required|in:1,2,3',
            'keterangan' => 'required|max:500',
            'bukti_surat' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048'
        ], [
            'tgl_izin.required' => 'Tanggal izin harus diisi',
            'status.required' => 'Jenis izin harus dipilih',
            'keterangan.required' => 'Keterangan harus diisi',
            'bukti_surat.mimes' => 'Format file harus jpg, jpeg, png, pdf, doc, atau docx',
            'bukti_surat.max' => 'Ukuran file maksimal 2MB'
        ]);
        
        // Cek apakah sudah ada izin di tanggal yang sama
        $cek = DB::table('pengajuan_izin')
            ->where('nik', $nik)
            ->where('tgl_izin', $tgl_izin)
            ->count();
            
        if ($cek > 0) {
            return redirect()->back()
                ->with(['error' => '❌ Anda sudah mengajukan izin pada tanggal tersebut'])
                ->withInput();
        }
        
        // Upload bukti surat jika ada
        $bukti_surat = null;
        if ($request->hasFile('bukti_surat')) {
            $file = $request->file('bukti_surat');
            $bukti_surat = $nik . "_" . time() . "." . $file->getClientOriginalExtension();
            $file->storeAs('public/uploads/izin', $bukti_surat);
        }
        
        // ✅ AUTO APPROVE JIKA STATUS = SAKIT (2)
        $status_approved = ($status == 2) ? 1 : 0; // 1 = Approved, 0 = Pending
        
        // Simpan data
        $data = [
            'nik' => $nik,
            'tgl_izin' => $tgl_izin,
            'status' => $status,
            'keterangan' => $keterangan,
            'bukti_surat' => $bukti_surat,
            'status_approved' => $status_approved,
            'approved_by' => ($status == 2) ? 'SYSTEM_AUTO' : null,
            'approved_at' => ($status == 2) ? now() : null,
            'created_at' => now(),
            'updated_at' => now()
        ];
        
        try {
            $simpan = DB::table('pengajuan_izin')->insert($data);
            
            if ($simpan) {
                $tanggal = date('d/m/Y', strtotime($tgl_izin));
                
                // Jika sakit, langsung approved
                if ($status == 2) {
                    return redirect('/presensi/izin')->with([
                        'success' => '✅ Pengajuan Sakit berhasil dan langsung disetujui secara otomatis!',
                        'trigger_notification' => [
                            'status' => 'approved',
                            'message' => "Pengajuan sakit tanggal {$tanggal} telah disetujui secara otomatis",
                            'user_id' => Auth::id()
                        ]
                    ]);
                } else {
                    $jenis = ($status == 1) ? 'Izin' : 'Cuti';
                    return redirect('/presensi/izin')->with([
                        'success' => "✅ Pengajuan {$jenis} berhasil! Menunggu persetujuan admin."
                    ]);
                }
            } else {
                return redirect()->back()
                    ->with(['error' => '❌ Gagal menyimpan data'])
                    ->withInput();
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => '❌ Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * TAMPILKAN FORM EDIT IZIN
     */
    public function editizin($id)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        
        $dataizin = DB::table('pengajuan_izin')
            ->where('id', $id)
            ->where('nik', $nik)
            ->first();

        if (!$dataizin) {
            return redirect('/presensi/izin')->with('error', 'Data tidak ditemukan');
        }

        // Tidak bisa edit jika sudah approved atau rejected
        if ($dataizin->status_approved != 0) {
            return redirect('/presensi/izin')->with('error', 'Tidak dapat mengedit pengajuan yang sudah diproses');
        }

        return view('presensi.editizin', compact('dataizin'));
    }

    /**
     * UPDATE DATA IZIN
     */
    public function updateizin(Request $request, $id)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        
        // Validasi
        $request->validate([
            'tgl_izin' => 'required|date',
            'status' => 'required|in:1,2,3',
            'keterangan' => 'required|max:500',
            'bukti_surat' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048'
        ]);

        // Cek data exist dan milik user
        $dataizin = DB::table('pengajuan_izin')
            ->where('id', $id)
            ->where('nik', $nik)
            ->first();

        if (!$dataizin) {
            return redirect('/presensi/izin')->with('error', 'Data tidak ditemukan');
        }

        // Tidak bisa edit jika sudah approved/rejected
        if ($dataizin->status_approved != 0) {
            return redirect('/presensi/izin')->with('error', 'Tidak dapat mengedit pengajuan yang sudah diproses');
        }

        // Handle upload file baru
        $bukti_surat = $dataizin->bukti_surat;
        if ($request->hasFile('bukti_surat')) {
            // Hapus file lama jika ada
            if ($bukti_surat && Storage::exists('public/uploads/izin/' . $bukti_surat)) {
                Storage::delete('public/uploads/izin/' . $bukti_surat);
            }
            
            $file = $request->file('bukti_surat');
            $bukti_surat = $nik . "_" . time() . "." . $file->getClientOriginalExtension();
            $file->storeAs('public/uploads/izin', $bukti_surat);
        }

        // ✅ AUTO APPROVE JIKA DIUBAH MENJADI SAKIT
        $status = $request->status;
        $status_approved = ($status == 2) ? 1 : 0;
        $approved_by = ($status == 2) ? 'SYSTEM_AUTO' : null;
        $approved_at = ($status == 2) ? now() : null;

        // Update data
        $update = DB::table('pengajuan_izin')
            ->where('id', $id)
            ->update([
                'tgl_izin' => $request->tgl_izin,
                'status' => $status,
                'keterangan' => $request->keterangan,
                'bukti_surat' => $bukti_surat,
                'status_approved' => $status_approved,
                'approved_by' => $approved_by,
                'approved_at' => $approved_at,
                'updated_at' => now()
            ]);

        if ($update) {
            if ($status == 2) {
                return redirect('/presensi/izin')->with([
                    'success' => '✅ Data berhasil diupdate dan langsung disetujui (Sakit)!',
                    'trigger_notification' => [
                        'status' => 'approved',
                        'message' => 'Pengajuan sakit Anda telah disetujui secara otomatis',
                        'user_id' => Auth::id()
                    ]
                ]);
            } else {
                return redirect('/presensi/izin')->with('success', '✅ Data berhasil diupdate!');
            }
        }

        return redirect()->back()->with('error', '❌ Gagal mengupdate data');
    }

    /**
     * HAPUS DATA IZIN (hanya bisa jika status pending)
     */
    public function deleteizin($id)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        
        $dataizin = DB::table('pengajuan_izin')
            ->where('id', $id)
            ->where('nik', $nik)
            ->first();

        if (!$dataizin) {
            return redirect('/presensi/izin')->with('error', 'Data tidak ditemukan');
        }

        // Hanya bisa hapus jika status pending
        if ($dataizin->status_approved != 0) {
            return redirect('/presensi/izin')->with('error', 'Tidak dapat menghapus pengajuan yang sudah diproses');
        }

        // Hapus file bukti jika ada
        if ($dataizin->bukti_surat && Storage::exists('public/uploads/izin/' . $dataizin->bukti_surat)) {
            Storage::delete('public/uploads/izin/' . $dataizin->bukti_surat);
        }

        $delete = DB::table('pengajuan_izin')->where('id', $id)->delete();

        if ($delete) {
            return redirect('/presensi/izin')->with('success', '✅ Data berhasil dihapus');
        }

        return redirect()->back()->with('error', '❌ Gagal menghapus data');
    }

    /**
     * APPROVAL IZIN/SAKIT - dengan Push Notification (UNTUK ADMIN)
     */
    public function approveizinsakit(Request $request)
    {
        try {
            $id_izinsakit = $request->id_izinsakit_form;
            $status_approved = $request->status_approved;
            $alasan_tolak = $request->alasan_tolak;
            $nik_karyawan = $request->nik_karyawan;

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
                    'alasan_tolak' => $alasan_tolak,
                    'updated_at' => now()
                ]);

            if ($update) {
                $tanggal = date('d/m/Y', strtotime($izin->tgl_izin));
                
                if ($status_approved == 1) {
                    // DISETUJUI
                    session()->flash('trigger_notification', [
                        'status' => 'approved',
                        'message' => "Pengajuan izin tanggal {$tanggal} telah disetujui!",
                        'user_id' => $nik_karyawan
                    ]);
                    
                    return redirect('/panel/dataizinsakit')->with('success', '✅ Izin berhasil disetujui! Karyawan akan menerima notifikasi.');
                    
                } else {
                    // DITOLAK
                    $alasan_text = $alasan_tolak ?: 'Tidak memenuhi syarat';
                    session()->flash('trigger_notification', [
                        'status' => 'rejected',
                        'message' => "Pengajuan izin ditolak. Alasan: {$alasan_text}",
                        'user_id' => $nik_karyawan
                    ]);
                    
                    return redirect('/panel/dataizinsakit')->with('success', '✅ Izin berhasil ditolak! Karyawan akan menerima notifikasi.');
                }
            }

            return redirect()->back()->with('error', '❌ Gagal memproses approval');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Terjadi kesalahan: ' . $e->getMessage());
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

            // Tidak bisa batalkan jika auto-approved (sakit)
            if ($izin->approved_by == 'SYSTEM_AUTO') {
                return redirect()->back()->with('error', 'Tidak dapat membatalkan approval otomatis sistem');
            }

            $update = DB::table('pengajuan_izin')
                ->where('id', $id)
                ->update([
                    'status_approved' => 0,
                    'approved_by' => null,
                    'approved_at' => null,
                    'alasan_tolak' => null,
                    'updated_at' => now()
                ]);

            if ($update) {
                return redirect('/panel/dataizinsakit')->with('success', '✅ Approval berhasil dibatalkan');
            }

            return redirect()->back()->with('error', '❌ Gagal membatalkan approval');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * API - CEK UPDATE IZIN (untuk notification)
     */
    public function checkIzinUpdates()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        
        // Cek apakah ada izin yang baru di-approve/reject dalam 5 menit terakhir
        $recentUpdate = DB::table('pengajuan_izin')
            ->where('nik', $nik)
            ->where('approved_at', '>=', now()->subMinutes(5))
            ->whereNotNull('approved_at')
            ->orderBy('approved_at', 'DESC')
            ->first();

        if ($recentUpdate) {
            $tanggal = date('d/m/Y', strtotime($recentUpdate->tgl_izin));
            $status = $recentUpdate->status_approved == 1 ? 'approved' : 'rejected';
            $message = $status == 'approved' 
                ? "Pengajuan izin tanggal {$tanggal} telah disetujui!"
                : "Pengajuan izin tanggal {$tanggal} ditolak. Alasan: " . ($recentUpdate->alasan_tolak ?: 'Tidak memenuhi syarat');

            return response()->json([
                'has_update' => true,
                'notification' => [
                    'status' => $status,
                    'message' => $message
                ]
            ]);
        }

        return response()->json(['has_update' => false]);
    }
}