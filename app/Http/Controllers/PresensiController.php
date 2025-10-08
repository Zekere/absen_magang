<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;       // ✅ import Hash
use Illuminate\Support\Facades\Redirect;  
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    public function create()
    {
        $hariini = date('Y-m-d');
        $nik = Auth::guard('karyawan')->user()->nik;

        // gunakan count supaya integer, bisa dipakai di view
        $cek = DB::table('presensi')
            ->where('tgl_presensi', $hariini)
            ->where('nik', $nik)
            ->count();

        return view('presensi.create', compact('cek'));
    }

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

            // Koordinat kantor
            $latitudekantor  = -7.004715376154676;
            $longitudekantor = 110.40668618650808;

            // Validasi lokasi user
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

            // Hitung jarak
            $jarak  = $this->distance($latitudekantor, $longitudekantor, $latitudeuser, $longitudeuser);
            $radius = round($jarak['meters']);

            // cek apakah sudah presensi hari ini
            $cek = DB::table('presensi')
                ->where('tgl_presensi', $tgl_presensi)
                ->where('nik', $nik)
                ->first();

            $ket = $cek ? "out" : "in";

            $image = $request->image;
            if (!$image) {
                echo "error|Foto tidak dikirim|x";
                return;
            }

            // pecah base64 string
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

            // folder tujuan penyimpanan
            $folderPath  = 'public/uploads/absensi/';
            $fileName    = $nik . "-" . $tgl_presensi . "-" . $ket . '.png';
            $file        = $folderPath . $fileName;

            if (!$cek) {
                // ============== Absen Masuk ==============
                if ($radius > 1000) {
                    echo "error|Maaf Anda Berada Diluar Jangkauan|x";
                    return;
                }

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

    /**
     * Hitung jarak antara dua koordinat (Haversine Formula)
     */
    private function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = sin(deg2rad($lat1)) * sin(deg2rad($lat2))
               + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));

        // amankan range supaya tidak error domain acos
        $miles = acos(min(1, max(-1, $miles)));
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;

        return compact('meters');
    }

    public function editprofile()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();

        return view('presensi.editprofile', compact('karyawan'));
    }

    public function updateprofile(Request $request)
{
    $nik = Auth::guard('karyawan')->user()->nik;
    $karyawan = DB::table('karyawan')->where('nik', $nik)->first();

    // Validasi input
    $request->validate([
        'nama_lengkap' => 'required|string|max:100',
        'no_hp'        => 'nullable|string|max:15',
        'password'     => 'nullable|min:6',
        'foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    // Siapkan data update
    $data = [
        'nama_lengkap' => $request->nama_lengkap,
        'no_hp'        => $request->no_hp,
    ];

    // Update password hanya jika diisi
    if (!empty($request->password)) {
        $data['password'] = Hash::make($request->password);
    }

    // Update foto jika ada upload baru
    if ($request->hasFile('foto')) {
        $foto = $nik . "." . $request->file('foto')->getClientOriginalExtension();
        $folderPath = "public/uploads/karyawan";

        // Simpan foto baru
        $request->file('foto')->storeAs($folderPath, $foto);
        $data['foto'] = $foto;
    } else {
        $data['foto'] = $karyawan->foto; // tetap pakai foto lama
    }

    // Update ke database
    $update = DB::table('karyawan')->where('nik', $nik)->update($data);

    if ($update) {
        return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
    } else {
        return Redirect::back()->with(['error' => 'Data Gagal Di Update']);
    }
}


   public function histori(){

    $namabulan =["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
    return view('presensi.histori',compact('namabulan'));
   } 

   public function gethistori(Request $request){
    $bulan = $request->bulan;
    $tahun = $request->tahun;
    $nik = Auth::guard('karyawan')->user()->nik;
    $histori = DB::table('presensi')
    ->whereRaw('MONTH(tgl_presensi) = ?',[$bulan])
    ->whereRaw('YEAR(tgl_presensi) = ?',[$tahun])
    ->where('nik',$nik)
    ->orderBy('tgl_presensi')
    ->get();

    return view('presensi.gethistori',compact('histori'));
   }

   public function izin()

   {
     $nik = Auth::guard('karyawan')->user()->nik;
    $dataizin = DB::table('pengajuan_izin')->where('nik',$nik)->get();
    return view('presensi.izin', compact('dataizin'));
   }

   public function buatizin()
   {
   
    return view('presensi.buatizin');
   }

   public function storeizin(Request $request)
{
    $nik = Auth::guard('karyawan')->user()->nik;
    $tgl_izin = $request->tgl_izin;
    $status = $request->status;
    $keterangan = $request->keterangan;
    
    $data = [
        'nik'            => $nik,
        'tgl_izin'       => $tgl_izin,
        'status'         => $status,
        'keterangan'     => $keterangan,
        'status_approved'=> 0, // 👈 default pending
       
    ];

    $simpan = DB::table('pengajuan_izin')->insert($data);

    if($simpan){
        return redirect('/presensi/izin')->with('success','Data berhasil disimpan');
    }else{
        return redirect('/presensi/izin')->with('error','Data gagal disimpan');
    }
}
public function monitoring(){
        return view('presensi.monitoring');
    }

    public function getpresensi(Request $request){
        $tanggal = $request -> tanggal;
        $presensi = DB::table('presensi')
        ->select('presensi.*','nama_lengkap','nama_dept')  
        ->join('karyawan','presensi.nik','=','karyawan.nik')
        ->join('departemen','karyawan.kode_dept','=','departemen.kode_dept')
        ->where('tgl_presensi',$tanggal)
        ->get();

        return view('presensi.getpresensi', compact('presensi'));
    }
}
