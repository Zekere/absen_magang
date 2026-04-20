<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use App\Models\JamKerja;
use App\Models\Karyawan;
use App\Models\Departemen;

class PresensiController extends Controller
{
    // ─────────────────────────────────────────────
    //  HELPER: hitung status kehadiran
    // ─────────────────────────────────────────────

    /**
     * Tentukan status kehadiran berdasarkan jam_masuk vs konfigurasi jam kerja.
     *
     * @param  string   $jamMasuk  Format "HH:MM:SS"
     * @param  JamKerja $jk
     * @return string   hadir | terlambat | alpha
     */
    private function hitungStatusKehadiran(string $jamMasuk, JamKerja $jk): string
    {
        $masukMenit     = (int)date('H', strtotime($jamMasuk)) * 60 + (int)date('i', strtotime($jamMasuk));
        $jamMasukMenit  = (int)date('H', strtotime($jk->jam_masuk)) * 60 + (int)date('i', strtotime($jk->jam_masuk));
        $batasTerlambat = $jamMasukMenit + (int)$jk->toleransi_keterlambatan;

        if ($masukMenit <= $batasTerlambat) {
            return 'hadir';
        }
        return 'terlambat';
    }

    // ─────────────────────────────────────────────
    //  CREATE — halaman absen
    // ─────────────────────────────────────────────

    public function create()
    {
        $hariini = date('Y-m-d');
        $nik     = Auth::guard('karyawan')->user()->nik;

        $cek = DB::table('presensi')
            ->where('tgl_presensi', $hariini)
            ->where('nik', $nik)
            ->count();

        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();

        $facePath    = storage_path('app/public/uploads/faces/' . $nik . '_face.jpg');
        $hasFaceData = file_exists($facePath);

        // Ambil jam kerja global (semua karyawan pakai jam kerja yang sama)
        $jamKerja = JamKerja::getConfig();

        return view('presensi.create', compact('cek', 'lok_kantor', 'hasFaceData', 'jamKerja'));
    }

    // ─────────────────────────────────────────────
    //  SETUP WAJAH
    // ─────────────────────────────────────────────

    public function setupwajah(Request $request)
    {
        try {
            $nik      = Auth::guard('karyawan')->user()->nik;
            $faceData = $request->face_data;

            if (!$faceData) {
                return response()->json(['success' => false, 'message' => 'Data foto wajah tidak ditemukan.']);
            }

            $parts = explode(';base64,', $faceData);
            if (count($parts) < 2) {
                return response()->json(['success' => false, 'message' => 'Format foto tidak valid.']);
            }

            $imageData = base64_decode($parts[1]);
            if ($imageData === false) {
                return response()->json(['success' => false, 'message' => 'Gagal mendekode foto.']);
            }

            $folder = storage_path('app/public/uploads/faces/');
            if (!file_exists($folder)) {
                mkdir($folder, 0755, true);
            }

            $filePath = $folder . $nik . '_face.jpg';
            $saved    = file_put_contents($filePath, $imageData);

            if ($saved === false) {
                return response()->json(['success' => false, 'message' => 'Gagal menyimpan foto ke server.']);
            }

            return response()->json(['success' => true, 'message' => 'Data wajah berhasil disimpan.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────
    //  STORE — simpan presensi
    // ─────────────────────────────────────────────

    public function store(Request $request)
    {
        try {
            $user = Auth::guard('karyawan')->user();
            if (!$user) {
                echo "error|User tidak ditemukan|x";
                return;
            }

            $nik          = $user->nik;
            $tgl_presensi = date('Y-m-d');
            $jam          = date('H:i:s');

            // Validasi face verification
            $face_verified = $request->face_verified ?? 0;
            if ($face_verified != 1) {
                echo "error|Wajah Anda belum terverifikasi! Pastikan wajah terlihat jelas di kamera.|x";
                return;
            }

            $cek_count = DB::table('presensi')
                ->where('tgl_presensi', $tgl_presensi)
                ->where('nik', $nik)
                ->count();

            if ($cek_count >= 2) {
                echo "error|Anda sudah melakukan absen masuk dan pulang hari ini|x";
                return;
            }

            // Ambil konfigurasi lokasi — cek semua lokasi, valid jika dalam salah satu radius
            $lokasi_list = DB::table('konfigurasi_lokasi')->orderBy('id')->get();

            $lokasi = $request->lokasi;
            if (!$lokasi) {
                echo "error|Lokasi tidak dikirim|x";
                return;
            }

            $lokasiuser = explode(",", $lokasi);
            if (count($lokasiuser) < 2) {
                echo "error|Format lokasi tidak valid|x";
                return;
            }

            $latitudeuser  = floatval(trim($lokasiuser[0]));
            $longitudeuser = floatval(trim($lokasiuser[1]));

            // Cek terhadap semua lokasi kantor yang terdaftar
            $status_lokasi    = 'luar_kantor';
            $radius_terdekat  = PHP_INT_MAX;
            $lok_kantor_utama = null;

            foreach ($lokasi_list as $lok) {
                $lok_coords = explode(",", $lok->lokasi_kantor);
                $jarak_m    = $this->distance(
                    floatval(trim($lok_coords[0])), floatval(trim($lok_coords[1])),
                    $latitudeuser, $longitudeuser
                )['meters'];

                if ($jarak_m < $radius_terdekat) {
                    $radius_terdekat  = round($jarak_m);
                    $lok_kantor_utama = $lok;
                }

                if ($jarak_m <= $lok->radius) {
                    $status_lokasi = 'dalam_kantor';
                }
            }

            // Fallback jika tidak ada lokasi sama sekali
            if (!$lok_kantor_utama) {
                echo "error|Konfigurasi lokasi kantor tidak ditemukan|x";
                return;
            }

            $cek = DB::table('presensi')
                ->where('tgl_presensi', $tgl_presensi)
                ->where('nik', $nik)
                ->first();

            $ket   = $cek ? "out" : "in";
            $image = $request->image;

            if (!$image) {
                echo "error|Foto tidak dikirim|x";
                return;
            }

            $image_parts = explode(";base64,", $image);
            if (count($image_parts) < 2) {
                echo "error|Format foto tidak valid|x";
                return;
            }

            $image_base64 = base64_decode($image_parts[1]);
            if ($image_base64 === false) {
                echo "error|Gagal decode gambar|x";
                return;
            }

            $folderPath = 'public/uploads/absensi/';
            $fileName   = $nik . "-" . $tgl_presensi . "-" . $ket . '.png';
            $file       = $folderPath . $fileName;

            // ── Ambil jam kerja global ────────────────────────────────────────
            $jamKerja = JamKerja::getConfig();

            if (!$cek) {
                // ── ABSEN MASUK ───────────────────────────────────────────────

                // Validasi batas waktu absen masuk
                if ($jamKerja) {
                    $nowMenit        = (int)date('H') * 60 + (int)date('i');
                    $batasMasukAwal  = (int)$jamKerja->batas_absen_masuk_awal;
                    $batasMasukAkhir = (int)$jamKerja->batas_absen_masuk_akhir;

                    if ($nowMenit < $batasMasukAwal) {
                        $waktuBuka = sprintf("%02d:%02d", floor($batasMasukAwal / 60), $batasMasukAwal % 60);
                        echo "error|Belum waktunya absen masuk. Absen masuk dibuka mulai pukul {$waktuBuka}.|x";
                        return;
                    }

                    if ($nowMenit > $batasMasukAkhir) {
                        $waktuAkhir = sprintf("%02d:%02d", floor($batasMasukAkhir / 60), $batasMasukAkhir % 60);
                        echo "error|Waktu absen masuk telah berakhir pada pukul {$waktuAkhir}.|x";
                        return;
                    }
                }

                // Hitung status kehadiran otomatis
                $statusKehadiran = 'hadir';
                if ($jamKerja) {
                    $statusKehadiran = $this->hitungStatusKehadiran($jam, $jamKerja);
                }

                $data_masuk = [
                    'nik'              => $nik,
                    'tgl_presensi'     => $tgl_presensi,
                    'jam_in'           => $jam,
                    'foto_in'          => $fileName,
                    'lokasi_in'        => $lokasi,
                    'status_lokasi_in' => $status_lokasi,
                    'jarak_in'         => $radius_terdekat,
                    'status'           => $statusKehadiran,
                ];

                $simpan = DB::table('presensi')->insert($data_masuk);

                if ($simpan) {
                    Storage::put($file, $image_base64);
                    $pesanLokasi = $status_lokasi === 'luar_kantor'
                        ? " Anda berada di luar area kantor (Jarak: {$radius_terdekat}m)."
                        : '';
                    $pesanStatus = $statusKehadiran === 'terlambat' ? ' Anda tercatat terlambat.' : '';

                    echo "success|Selamat datang! Absen masuk berhasil.{$pesanStatus}{$pesanLokasi}|in";
                    return;
                }
            } else {
                // ── ABSEN PULANG ──────────────────────────────────────────────

                // Validasi waktu absen pulang
                if ($jamKerja) {
                    $nowMenit    = (int)date('H') * 60 + (int)date('i');
                    $pulangMenit = (int)date('H', strtotime($jamKerja->jam_pulang)) * 60
                                 + (int)date('i', strtotime($jamKerja->jam_pulang));
                    $batasPulang = $pulangMenit - (int)$jamKerja->batas_absen_pulang_sebelum;

                    if ($nowMenit < $batasPulang) {
                        $waktuBuka = sprintf("%02d:%02d", floor($batasPulang / 60), $batasPulang % 60);
                        echo "error|Belum bisa absen pulang. Absen pulang dibuka mulai pukul {$waktuBuka}.|x";
                        return;
                    }
                }

                if (!empty($cek->jam_out)) {
                    echo "error|Anda sudah melakukan absen pulang hari ini|x";
                    return;
                }

                $data_pulang = [
                    'jam_out'           => $jam,
                    'foto_out'          => $fileName,
                    'lokasi_out'        => $lokasi,
                    'status_lokasi_out' => $status_lokasi,
                    'jarak_out'         => $radius_terdekat,
                ];

                $update = DB::table('presensi')
                    ->where('tgl_presensi', $tgl_presensi)
                    ->where('nik', $nik)
                    ->update($data_pulang);

                if ($update) {
                    Storage::put($file, $image_base64);
                    $pesanLokasi = $status_lokasi === 'luar_kantor'
                        ? " Anda berada di luar area kantor (Jarak: {$radius_terdekat}m)."
                        : '';

                    echo "success|Absen pulang berhasil. Hati-hati di jalan!{$pesanLokasi}|out";
                    return;
                }
            }

            echo "error|Gagal menyimpan presensi|x";
        } catch (\Exception $e) {
            echo "error|Terjadi kesalahan: " . $e->getMessage() . "|x";
        }
    }

    // ─────────────────────────────────────────────
    //  DISTANCE helper
    // ─────────────────────────────────────────────

    private function distance($lat1, $lon1, $lat2, $lon2): array
    {
        $theta     = $lon1 - $lon2;
        $miles     = sin(deg2rad($lat1)) * sin(deg2rad($lat2))
                   + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $miles     = acos(min(1, max(-1, $miles)));
        $miles     = rad2deg($miles);
        $miles     = $miles * 60 * 1.1515;
        $kilometers = $miles * 1.609344;
        $meters    = $kilometers * 1000;

        return compact('meters');
    }

    private function hitungJarakCepat($lat1, $lon1, $lat2, $lon2): float
    {
        $R    = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a    = sin($dLat / 2) * sin($dLat / 2)
              + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
              * sin($dLon / 2) * sin($dLon / 2);
        $c    = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $R * $c;
    }

    // ─────────────────────────────────────────────
    //  PROFILE
    // ─────────────────────────────────────────────

    public function editprofile()
    {
        $nik      = Auth::guard('karyawan')->user()->nik;
        $karyawan = Karyawan::with('departemen')->where('nik', $nik)->first();

        return view('presensi.editprofile', compact('karyawan'));
    }

    public function updateprofile(Request $request)
    {
        $nik      = Auth::guard('karyawan')->user()->nik;
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();

        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'no_hp'        => 'nullable|string|max:15',
            'password'     => 'nullable|min:6',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'no_hp'        => $request->no_hp,
        ];

        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            $foto       = $nik . "." . $request->file('foto')->getClientOriginalExtension();
            $folderPath = "public/uploads/karyawan";

            if (!empty($karyawan->foto) && Storage::exists($folderPath . '/' . $karyawan->foto)) {
                Storage::delete($folderPath . '/' . $karyawan->foto);
            }

            $request->file('foto')->storeAs($folderPath, $foto);
            $data['foto'] = $foto;
        } else {
            $data['foto'] = $karyawan->foto;
        }

        $update = DB::table('karyawan')->where('nik', $nik)->update($data);

        if ($update || $request->hasFile('foto')) {
            return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        }

        return Redirect::back()->with(['warning' => 'Tidak ada perubahan data']);
    }

    // ─────────────────────────────────────────────
    //  HISTORI
    // ─────────────────────────────────────────────

    public function histori()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                      "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('presensi.histori', compact('namabulan'));
    }

    public function gethistori(Request $request)
    {
        $bulan  = $request->bulan;
        $tahun  = $request->tahun;
        $nik    = Auth::guard('karyawan')->user()->nik;

        $histori = DB::table('presensi')
            ->whereRaw('MONTH(tgl_presensi) = ?', [$bulan])
            ->whereRaw('YEAR(tgl_presensi) = ?',  [$tahun])
            ->where('nik', $nik)
            ->orderBy('tgl_presensi')
            ->get();

        return view('presensi.gethistori', compact('histori'));
    }

    // ─────────────────────────────────────────────
    //  IZIN
    // ─────────────────────────────────────────────

    public function izin()
    {
        $nik      = Auth::guard('karyawan')->user()->nik;
        $dataizin = DB::table('pengajuan_izin')->where('nik', $nik)->get();
        return view('presensi.izin', compact('dataizin'));
    }

    public function buatizin()
    {
        return view('presensi.buatizin');
    }

    public function storeizin(Request $request)
    {
        $nik         = Auth::guard('karyawan')->user()->nik;
        $tgl_izin    = $request->tgl_izin;
        $status      = $request->status;
        $keterangan  = $request->keterangan;
        $bukti_surat = null;

        $cekIzin = DB::table('pengajuan_izin')
            ->where('nik', $nik)
            ->whereDate('tgl_izin', $tgl_izin)
            ->first();

        if ($cekIzin) {
            return redirect('/presensi/buatizin')
                ->with('error', 'Anda sudah mengajukan izin pada tanggal tersebut!');
        }

        if ($request->hasFile('bukti_surat')) {
            $file     = $request->file('bukti_surat');
            $ext      = $file->getClientOriginalExtension();
            $filename = $nik . '-' . date('YmdHis') . '.' . $ext;
            $path     = public_path('storage/uploads/izin');

            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            $file->move($path, $filename);
            $bukti_surat = $filename;
        }

        // Status 1 = Izin (perlu approval), 2 = Sakit (auto-approved), 3 = Cuti (perlu approval)
        if ($status == '2') {
            $status_approved = 1;
            $message         = 'Pengajuan sakit berhasil! Status sudah otomatis disetujui.';
        } else {
            $status_approved = 0;
            $message         = 'Pengajuan berhasil dikirim. Menunggu persetujuan admin.';
        }

        $data = [
            'nik'             => $nik,
            'tgl_izin'        => $tgl_izin,
            'status'          => $status,
            'keterangan'      => $keterangan,
            'bukti_surat'     => $bukti_surat,
            'status_approved' => $status_approved,
        ];

        $simpan = DB::table('pengajuan_izin')->insert($data);

        if ($simpan) {
            return redirect('/presensi/izin')->with('success', $message);
        }

        return redirect('/presensi/izin')->with('error', 'Gagal mengirim pengajuan izin');
    }

    public function lihatbukti($id)
    {
        $izin = DB::table('pengajuan_izin')->where('id', $id)->first();
        $path = public_path('storage/uploads/izin/' . $izin->bukti_surat);

        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($path);
    }

    public function downloadbukti($id)
    {
        $izin = DB::table('pengajuan_izin')->where('id', $id)->first();
        $path = public_path('storage/uploads/izin/' . $izin->bukti_surat);

        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->download($path);
    }

    // ─────────────────────────────────────────────
    //  MONITORING ADMIN
    // ─────────────────────────────────────────────

    public function monitoring()
    {
        return view('presensi.monitoring');
    }

    public function getpresensi(Request $request)
    {
        $tanggal = $request->tanggal;

        $presensi = DB::table('presensi')
            ->select('presensi.*', 'nama_lengkap', 'nama_dept')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->where('tgl_presensi', $tanggal)
            ->get();

        $lok_kantor     = DB::table('konfigurasi_lokasi')->where('id', 1)->first();
        $lokasi_kantor  = explode(',', $lok_kantor->lokasi_kantor);
        $lat_kantor     = (float) trim($lokasi_kantor[0]);
        $long_kantor    = (float) trim($lokasi_kantor[1]);
        $radius_kantor  = $lok_kantor->radius;

        $jamKerja          = JamKerja::getConfig();
        $jam_masuk_default = $jamKerja ? $jamKerja->jam_masuk : '07:30:00';

        $no   = 0;
        // $html = '';
        $html = '<tr data-status="' . strtolower($d->status ?? '') . '" data-jam-out="' . ($d->jam_out ?? '') . '">';

        if ($presensi->isEmpty()) {
            $html .= '<tr>';
            $html .= '<td colspan="13" class="text-center text-muted py-4">
                        <i class="bi bi-inbox" style="font-size: 2rem;"></i><br>
                        Tidak ada data presensi pada tanggal ini
                      </td>';
            $html .= '</tr>';
        } else {
            foreach ($presensi as $d) {
                $no++;

                // Status lokasi masuk
                $lokasi_masuk_status = '-';
                if (!empty($d->lokasi_in)) {
                    $lok_masuk = explode(',', $d->lokasi_in);
                    if (count($lok_masuk) >= 2) {
                        $jarak_masuk = $this->hitungJarakCepat(
                            $lat_kantor, $long_kantor,
                            (float) trim($lok_masuk[0]),
                            (float) trim($lok_masuk[1])
                        );
                        if ($jarak_masuk <= $radius_kantor) {
                            $lokasi_masuk_status = '<span class="badge-dalam-kantor"><i class="bi bi-check-circle-fill"></i> Dalam Kantor</span>';
                        } else {
                            $lokasi_masuk_status = '<span class="badge-luar-kantor"><i class="bi bi-exclamation-triangle-fill"></i> Luar (' . round($jarak_masuk) . 'm)</span>';
                        }
                    }
                }

                // Status lokasi pulang
                $lokasi_pulang_status = '-';
                if (!empty($d->lokasi_out)) {
                    $lok_pulang = explode(',', $d->lokasi_out);
                    if (count($lok_pulang) >= 2) {
                        $jarak_pulang = $this->hitungJarakCepat(
                            $lat_kantor, $long_kantor,
                            (float) trim($lok_pulang[0]),
                            (float) trim($lok_pulang[1])
                        );
                        if ($jarak_pulang <= $radius_kantor) {
                            $lokasi_pulang_status = '<span class="badge-dalam-kantor"><i class="bi bi-check-circle-fill"></i> Dalam Kantor</span>';
                        } else {
                            $lokasi_pulang_status = '<span class="badge-luar-kantor"><i class="bi bi-exclamation-triangle-fill"></i> Luar (' . round($jarak_pulang) . 'm)</span>';
                        }
                    }
                }

                // Keterangan keterlambatan
                $keterangan = '-';
                if (!empty($d->jam_in)) {
                    $jam_masuk_real       = strtotime($d->jam_in);
                    $jam_masuk_seharusnya = strtotime($jam_masuk_default);
                    $toleransi_detik      = $jamKerja ? ((int)$jamKerja->toleransi_keterlambatan * 60) : 0;

                    if ($jam_masuk_real > ($jam_masuk_seharusnya + $toleransi_detik)) {
                        $selisih_detik   = $jam_masuk_real - $jam_masuk_seharusnya;
                        $jam_terlambat   = floor($selisih_detik / 3600);
                        $menit_terlambat = floor(($selisih_detik % 3600) / 60);

                        if ($jam_terlambat > 0) {
                            $keterangan = '<span class="badge bg-danger">Terlambat ' . $jam_terlambat . 'j ' . $menit_terlambat . 'm</span>';
                        } else {
                            $keterangan = '<span class="badge bg-warning text-dark">Terlambat ' . $menit_terlambat . 'm</span>';
                        }
                    } else {
                        $keterangan = '<span class="badge bg-success"><i class="bi bi-check-lg"></i> Tepat Waktu</span>';
                    }
                }

                // Badge status kehadiran dari kolom status
                $statusBadge = '-';
                if (!empty($d->status)) {
                    $statusMap = [
                        'hadir'     => '<span class="badge bg-success">Hadir</span>',
                        'terlambat' => '<span class="badge bg-warning text-dark">Terlambat</span>',
                        'alpha'     => '<span class="badge bg-danger">Alpha</span>',
                    ];
                    $statusBadge = $statusMap[$d->status] ?? '<span class="badge bg-secondary">' . ucfirst($d->status) . '</span>';
                }

                $foto_in = !empty($d->foto_in)
                    ? '<img src="' . asset('storage/uploads/absensi/' . $d->foto_in) . '" class="img-thumbnail" style="max-width: 60px; cursor: pointer;" onclick="showImage(this.src)">'
                    : '<span class="text-muted">-</span>';

                $foto_out = !empty($d->foto_out)
                    ? '<img src="' . asset('storage/uploads/absensi/' . $d->foto_out) . '" class="img-thumbnail" style="max-width: 60px; cursor: pointer;" onclick="showImage(this.src)">'
                    : '<span class="text-muted">-</span>';

                $html .= '<tr>';
                $html .= '<td>' . $no . '</td>';
                $html .= '<td>' . $d->nik . '</td>';
                $html .= '<td>' . $d->nama_lengkap . '</td>';
                $html .= '<td>' . $d->nama_dept . '</td>';
                $html .= '<td>' . ($d->jam_in ?? '-') . '</td>';
                $html .= '<td>' . $foto_in . '</td>';
                $html .= '<td>' . $lokasi_masuk_status . '</td>';
                $html .= '<td>' . ($d->jam_out ?? '-') . '</td>';
                $html .= '<td>' . $foto_out . '</td>';
                $html .= '<td>' . $lokasi_pulang_status . '</td>';
                $html .= '<td>' . $keterangan . '</td>';
                $html .= '<td>' . $statusBadge . '</td>';
                $html .= '<td>' . ((!empty($d->lokasi_in) || !empty($d->lokasi_out))
                    ? '<button class="btn btn-sm btn-primary btn-action" onclick="tampilkanMap(' . $d->id . ')"><i class="bi bi-geo-alt-fill"></i> Map</button>'
                    : '<span class="text-muted">-</span>') . '</td>';
                $html .= '<td><button class="btn btn-sm btn-delete btn-action" onclick="deletePresensi(' . $d->id . ')"><i class="bi bi-trash-fill"></i></button></td>';
                $html .= '</tr>';
            }
        }

        echo $html;
    }

    // ─────────────────────────────────────────────
    //  SHOWMAP
    // ─────────────────────────────────────────────

    // public function showmap(Request $request)
    // {
    //     $id         = $request->id;
    //     $presensi   = DB::table('presensi')->where('id', $id)->first();
    //     $lok_kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();

    //     return view('presensi.showmap', compact('presensi', 'lok_kantor'));
    // }

    public function showMap(Request $request)
    {
        $id       = $request->id;
        $presensi = DB::table('presensi')->where('id', $id)
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->first();
        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();
        return view('presensi.showmap', compact('presensi', 'lok_kantor'));
    }

    public function deletePresensi($id)
    {
        try {
            $presensi = DB::table('presensi')->where('id', $id)->first();

            if ($presensi) {
                if (!empty($presensi->foto_in)) {
                    Storage::delete('public/uploads/absensi/' . $presensi->foto_in);
                }
                if (!empty($presensi->foto_out)) {
                    Storage::delete('public/uploads/absensi/' . $presensi->foto_out);
                }

                DB::table('presensi')->where('id', $id)->delete();

                return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus']);
            }

            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // ─────────────────────────────────────────────
    //  LAPORAN
    // ─────────────────────────────────────────────

    public function laporan()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                      "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $karyawan  = DB::table('karyawan')->orderBy('nama_lengkap')->get();
        return view('presensi.laporan', compact('namabulan', 'karyawan'));
    }

    public function cetaklaporan(Request $request)
    {
        $nik       = $request->nik;
        $bulan     = $request->bulan;
        $tahun     = $request->tahun;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                      "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        $karyawan = DB::table('karyawan')->where('nik', $nik)
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->first();

        $presensi = DB::table('presensi')
            ->where('nik', $nik)
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->orderBy('tgl_presensi')
            ->get();

        if (isset($_POST['exportexcel'])) {
            return $this->exportLaporanToExcel($karyawan, $presensi, $bulan, $tahun, $namabulan);
        }

        return view('presensi.cetaklaporan', compact('bulan', 'tahun', 'namabulan', 'karyawan', 'presensi'));
    }

    private function exportLaporanToExcel($karyawan, $presensi, $bulan, $tahun, $namabulan)
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(12);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);

        $sheet->setCellValue('A1', 'LAPORAN PRESENSI KARYAWAN');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A3', 'NIK');        $sheet->setCellValue('B3', ': ' . $karyawan->nik);
        $sheet->setCellValue('A4', 'Nama');       $sheet->setCellValue('B4', ': ' . $karyawan->nama_lengkap);
        $sheet->setCellValue('A5', 'Jabatan');    $sheet->setCellValue('B5', ': ' . $karyawan->jabatan);
        $sheet->setCellValue('A6', 'Departemen'); $sheet->setCellValue('B6', ': ' . $karyawan->nama_dept);
        $sheet->setCellValue('A7', 'Periode');    $sheet->setCellValue('B7', ': ' . $namabulan[$bulan] . ' ' . $tahun);

        $row = 9;
        $sheet->setCellValue('A' . $row, 'No');
        $sheet->setCellValue('B' . $row, 'Tanggal');
        $sheet->setCellValue('C' . $row, 'Jam Masuk');
        $sheet->setCellValue('D' . $row, 'Jam Pulang');
        $sheet->setCellValue('E' . $row, 'Status');
        $sheet->setCellValue('F' . $row, 'Keterangan');

        $headerStyle = [
            'font'      => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'CCCCCC']],
        ];
        $sheet->getStyle('A' . $row . ':F' . $row)->applyFromArray($headerStyle);

        $row++;
        $no = 1;
        $jamKerja = JamKerja::getConfig();

        foreach ($presensi as $d) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, date('d-m-Y', strtotime($d->tgl_presensi)));
            $sheet->setCellValue('C' . $row, $d->jam_in  ?? '-');
            $sheet->setCellValue('D' . $row, $d->jam_out ?? '-');
            $sheet->setCellValue('E' . $row, !empty($d->status) ? ucfirst($d->status) : '-');

            // Keterangan keterlambatan
            $ket = '-';
            if (!empty($d->jam_in) && $jamKerja) {
                $masukMenit    = (int)date('H', strtotime($d->jam_in)) * 60 + (int)date('i', strtotime($d->jam_in));
                $seharusnya    = (int)date('H', strtotime($jamKerja->jam_masuk)) * 60 + (int)date('i', strtotime($jamKerja->jam_masuk));
                $batasToleransi = $seharusnya + (int)$jamKerja->toleransi_keterlambatan;
                if ($masukMenit > $batasToleransi) {
                    $selisih = $masukMenit - $seharusnya;
                    $ket     = 'Terlambat ' . floor($selisih / 60) . 'j ' . ($selisih % 60) . 'm';
                } else {
                    $ket = 'Tepat Waktu';
                }
            }
            $sheet->setCellValue('F' . $row, $ket);

            $sheet->getStyle('A' . $row . ':F' . $row)->applyFromArray([
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ]);

            $row++;
        }

        $filename = 'Laporan_Presensi_' . $karyawan->nama_lengkap . '_' . $namabulan[$bulan] . '_' . $tahun . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // ─────────────────────────────────────────────
    //  REKAP
    // ─────────────────────────────────────────────

    public function Rekap()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                      "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('presensi.rekaplaporan', compact('namabulan'));
    }

    public function cetakrekap(Request $request)
    {
        $bulan     = $request->bulan;
        $tahun     = $request->tahun;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                      "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        $jumlahHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        $selectRaw  = 'presensi.nik, nama_lengkap';

        for ($i = 1; $i <= $jumlahHari; $i++) {
            $selectRaw .= ', MAX(IF(DAY(tgl_presensi) = ' . $i . ', CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_' . $i;
        }

        $rekap = DB::table('presensi')
            ->selectRaw($selectRaw)
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->groupByRaw('presensi.nik, nama_lengkap')
            ->get();

        if (isset($_POST['exportexcel'])) {
            return $this->exportRekapToExcel($rekap, $bulan, $tahun, $namabulan, $jumlahHari);
        }

        return view('presensi.cetakrekap', compact('bulan', 'tahun', 'namabulan', 'rekap', 'jumlahHari'));
    }

    private function exportRekapToExcel($rekap, $bulan, $tahun, $namabulan, $jumlahHari)
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(25);

        for ($i = 1; $i <= $jumlahHari; $i++) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(3 + $i);
            $sheet->getColumnDimension($col)->setWidth(15);
        }

        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(3 + $jumlahHari);

        $sheet->setCellValue('A1', 'REKAP PRESENSI KARYAWAN');
        $sheet->mergeCells('A1:' . $lastCol . '1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Periode: ' . $namabulan[$bulan] . ' ' . $tahun);
        $sheet->mergeCells('A2:' . $lastCol . '2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $row = 4;
        $sheet->setCellValue('A' . $row, 'No');
        $sheet->setCellValue('B' . $row, 'NIK');
        $sheet->setCellValue('C' . $row, 'Nama Karyawan');

        for ($i = 1; $i <= $jumlahHari; $i++) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(3 + $i);
            $sheet->setCellValue($col . $row, $i);
        }

        $headerStyle = [
            'font'      => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'CCCCCC']],
        ];
        $sheet->getStyle('A' . $row . ':' . $lastCol . $row)->applyFromArray($headerStyle);

        $row++;
        $no = 1;
        foreach ($rekap as $d) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $d->nik);
            $sheet->setCellValue('C' . $row, $d->nama_lengkap);

            for ($i = 1; $i <= $jumlahHari; $i++) {
                $col   = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(3 + $i);
                $tgl   = 'tgl_' . $i;
                $value = $d->$tgl ?? '';
                $sheet->setCellValue($col . $row, $value);
                $sheet->getStyle($col . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }

            $sheet->getStyle('A' . $row . ':' . $lastCol . $row)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ]);

            $row++;
        }

        $filename = 'Rekap_Presensi_' . $namabulan[$bulan] . '_' . $tahun . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // ─────────────────────────────────────────────
    //  IZIN SAKIT — ADMIN
    // ─────────────────────────────────────────────

    public function izinsakit()
    {
        $izinsakit = DB::table('pengajuan_izin')
            ->join('karyawan', 'pengajuan_izin.nik', '=', 'karyawan.nik')
            ->orderBy('tgl_izin', 'desc')
            ->get();
        return view('presensi.izinsakit', compact('izinsakit'));
    }

    public function filterIzinSakit(Request $request)
    {
        $query = DB::table('pengajuan_izin')
            ->join('karyawan', 'pengajuan_izin.nik', '=', 'karyawan.nik')
            ->orderBy('tgl_izin', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('status_approved')) {
            $query->where('status_approved', $request->status_approved);
        }
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('tgl_izin', $request->bulan)
                  ->whereYear('tgl_izin', $request->tahun);
        }

        $izinsakit = $query->get();
        return view('presensi.izinsakit', compact('izinsakit'));
    }

    public function approved(Request $request)
    {
        $status_approved    = $request->status_approved;
        $id_izinsakit_form  = $request->id_izinsakit_form;
        $alasan_tolak       = $request->alasan_tolak ?? null;

        $updateData = ['status_approved' => $status_approved, 'updated_at' => now()];
        if ($status_approved == 2 && $alasan_tolak) {
            $updateData['alasan_tolak'] = $alasan_tolak;
        }

        $update = DB::table('pengajuan_izin')
            ->where('id', $id_izinsakit_form)
            ->update($updateData);

        if ($update) {
            return Redirect::back()->with(['success' => 'Data Berhasil di update']);
        }

        return Redirect::back()->with(['warning' => 'Data gagal di update']);
    }

    public function batalkanizinsakit($id)
    {
        $update = DB::table('pengajuan_izin')->where('id', $id)->update([
            'status_approved' => 0,
        ]);

        if ($update) {
            return Redirect::back()->with(['success' => 'Data Berhasil di update']);
        }

        return Redirect::back()->with(['warning' => 'Data gagal di update']);
    }
}