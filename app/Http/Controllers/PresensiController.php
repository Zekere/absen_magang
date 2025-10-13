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
        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id',1)->first();
        return view('presensi.create', compact('cek','lok_kantor'));
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
            $lok_kantor = DB::table('konfigurasi_lokasi')->where('id',1)->first();
            $lok = explode(",",$lok_kantor->lokasi_kantor);
            $latitudekantor  = $lok[0];
            $longitudekantor = $lok[1];

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
                if ($radius > $lok_kantor -> radius) {
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

    // 🔒 Validasi input
    $request->validate([
        'nama_lengkap' => 'required|string|max:100',
        'no_hp'        => 'nullable|string|max:15',
        'password'     => 'nullable|min:6',
        'foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    // 🧩 Siapkan data untuk update
    $data = [
        'nama_lengkap' => $request->nama_lengkap,
        'no_hp'        => $request->no_hp,
    ];

    // 🔐 Update password hanya jika diisi
    if (!empty($request->password)) {
        $data['password'] = Hash::make($request->password);
    }

    // 🖼️ Upload foto baru jika ada
    if ($request->hasFile('foto')) {
        $foto = $nik . "." . $request->file('foto')->getClientOriginalExtension();
        $folderPath = "public/uploads/karyawan";

        // Hapus foto lama jika ada
        if (!empty($karyawan->foto) && Storage::exists($folderPath . '/' . $karyawan->foto)) {
            Storage::delete($folderPath . '/' . $karyawan->foto);
        }

        // Simpan foto baru
        $request->file('foto')->storeAs($folderPath, $foto);
        $data['foto'] = $foto;
    } else {
        $data['foto'] = $karyawan->foto; // tetap gunakan foto lama
    }

    // 💾 Lakukan update data
    $update = DB::table('karyawan')->where('nik', $nik)->update($data);

    // ✅ Jika ada perubahan atau foto diupload → sukses
    if ($update || $request->hasFile('foto')) {
        return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
    }

    // ⚠️ Tidak ada data yang berubah
    return Redirect::back()->with(['warning' => 'Tidak ada perubahan data']);
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

    public function map(Request $request){
        $id = $request -> id;
        $presensi = DB::table('presensi')->where('id', $id)
        ->join('karyawan','presensi.nik','=','karyawan.nik')
        ->first();
        return view('presensi.showmap',compact('presensi'));
    }

    public function laporan()
    {
         $namabulan =["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
         $karyawan = DB::table('karyawan')->orderBy('nama_lengkap')->get();
        return view('presensi.laporan',compact('namabulan','karyawan'));
    }

    public function cetaklaporan(Request $request){

        $nik = $request->nik;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $namabulan =["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        $karyawan = DB::table('karyawan')->where('nik',$nik)
            ->join('departemen','karyawan.kode_dept','=','departemen.kode_dept')
            ->first();

            $presensi = DB::table('presensi')
            ->where('nik',$nik)
            ->whereRAw('MONTH(tgl_presensi)="'.$bulan.'"')
            ->whereRAw('YEAR(tgl_presensi)="'.$tahun.'"')    
            ->orderBy('tgl_presensi')
            ->get();

        return view('presensi.cetaklaporan',compact('bulan','tahun','namabulan','karyawan','presensi'));
    }

    public function Rekap(){

        $namabulan =["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
     $karyawan = DB::table('karyawan')->orderBy('nama_lengkap')->get();
        return view ('presensi.rekaplaporan',compact('namabulan'));
    }

    public function cetakrekap(Request $request){
        $bulan = $request -> bulan;
        $tahun = $request -> tahun;
        $namabulan =["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        $rekap = DB::table('presensi')
        ->selectRaw(' presensi.nik,nama_lengkap,

    MAX(IF(DAY(tgl_presensi) = 1, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_1,
    MAX(IF(DAY(tgl_presensi) = 2, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_2,
    MAX(IF(DAY(tgl_presensi) = 3, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_3,
    MAX(IF(DAY(tgl_presensi) = 4, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_4,
    MAX(IF(DAY(tgl_presensi) = 5, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_5,
    MAX(IF(DAY(tgl_presensi) = 6, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_6,
    MAX(IF(DAY(tgl_presensi) = 7, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_7,
    MAX(IF(DAY(tgl_presensi) = 8, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_8,
    MAX(IF(DAY(tgl_presensi) = 9, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_9,
    MAX(IF(DAY(tgl_presensi) = 10, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_10,
    MAX(IF(DAY(tgl_presensi) = 11, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_11,
    MAX(IF(DAY(tgl_presensi) = 12, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_12,
    MAX(IF(DAY(tgl_presensi) = 13, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_13,
    MAX(IF(DAY(tgl_presensi) = 14, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_14,
    MAX(IF(DAY(tgl_presensi) = 15, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_15,
    MAX(IF(DAY(tgl_presensi) = 16, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_16,
    MAX(IF(DAY(tgl_presensi) = 17, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_17,
    MAX(IF(DAY(tgl_presensi) = 18, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_18,
    MAX(IF(DAY(tgl_presensi) = 19, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_19,
    MAX(IF(DAY(tgl_presensi) = 20, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_20,
    MAX(IF(DAY(tgl_presensi) = 21, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_21,
    MAX(IF(DAY(tgl_presensi) = 22, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_22,
    MAX(IF(DAY(tgl_presensi) = 23, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_23,
    MAX(IF(DAY(tgl_presensi) = 24, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_24,
    MAX(IF(DAY(tgl_presensi) = 25, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_25,
    MAX(IF(DAY(tgl_presensi) = 26, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_26,
    MAX(IF(DAY(tgl_presensi) = 27, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_27,
    MAX(IF(DAY(tgl_presensi) = 28, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_28,
    MAX(IF(DAY(tgl_presensi) = 29, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_29,
    MAX(IF(DAY(tgl_presensi) = 30, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_30,
    MAX(IF(DAY(tgl_presensi) = 31, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_31
')
->join('karyawan','presensi.nik','=','karyawan.nik')
->whereRaw('MONTH(tgl_presensi)="'.$bulan.'"')
->whereRaw('YEAR(tgl_presensi)="'.$tahun.'"')
->groupByRaw('presensi.nik,nama_lengkap')
->get();

return view('presensi.cetakrekap',compact('bulan','tahun','namabulan','rekap'));
    }

    public function izinsakit(){

        $izinsakit= DB::table('pengajuan_izin')
        ->join('karyawan','pengajuan_izin.nik','=','karyawan.nik')
        ->orderBy('tgl_izin','desc')
        ->get();
        return view('presensi.izinsakit',compact('izinsakit'));
    }

    public function approved(Request $request){

        $status_approved = $request->status_approved;
        $id_izinsakit_form=$request ->id_izinsakit_form;
        $update = DB::table('pengajuan_izin')->where('id',$id_izinsakit_form)->update([
            'status_approved' => $status_approved
        ]);
        if($update){
            return Redirect::back()->with(['success'=> 'Data Berhasil di update']);
        }else {
            return Redirect::back()->with(['warning'=>'Data gagal di update']);
        }
        }

        public function batalkanizinsakit($id){

             $update = DB::table('pengajuan_izin')->where('id',$id)->update([
            'status_approved' => 0
        ]);
        if($update){
            return Redirect::back()->with(['success'=> 'Data Berhasil di update']);
        }else {
            return Redirect::back()->with(['warning'=>'Data gagal di update']);
        }
        }

    }