<?php

namespace App\Http\Controllers;
use App\Models\Karyawan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class KaryawanController extends Controller
{
    public function index(Request $request)
    {

        $query = Karyawan :: query();
        $query->select('karyawan.*','nama_dept');
        $query->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept');
        $query->orderBy('nama_lengkap');
        if(!empty($request->nama_karyawan)){
            $query->where('nama_lengkap','like','%'.$request->nama_karyawan.'%');
        }
          if(!empty($request->kode_dept)){
            $query->where('karyawan.kode_dept',$request->kode_dept);
        }
        $karyawan = $query->paginate(5);


        $departemen = DB::table('departemen') ->get();
        return view('karyawan.index', compact('karyawan','departemen'));
    }

   public function store(Request $request)
    {
        // ✅ Validasi input
        $request->validate([
            'nik' => 'required|unique:karyawan,nik',
            'nama_lengkap' => 'required',
            'jabatan' => 'required',
            'no_hp' => 'required',
            'kode_dept' => 'required',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            // Pesan error yang lebih ramah
            'nik.required' => 'NIK wajib diisi.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'jabatan.required' => 'Jabatan wajib diisi.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'kode_dept.required' => 'Departemen wajib dipilih.',
        ]);

        try {
            $nik = $request->nik;
            $nama_lengkap = $request->nama_lengkap;
            $jabatan = $request->jabatan;
            $no_hp = $request->no_hp;
            $kode_dept = $request->kode_dept;
            $password = Hash::make('12345'); // ✅ Password default di-hash
            $foto = null;

            // ✅ Proses upload foto jika ada
            if ($request->hasFile('foto')) {
                $foto = $nik . '.' . $request->file('foto')->getClientOriginalExtension();
                $folderPath = 'public/uploads/karyawan';
                $request->file('foto')->storeAs($folderPath, $foto);
            }

            // ✅ Simpan data ke database
            DB::table('karyawan')->insert([
                'nik' => $nik,
                'nama_lengkap' => $nama_lengkap,
                'jabatan' => $jabatan,
                'no_hp' => $no_hp,
                'foto' => $foto,
                'kode_dept' => $kode_dept,
                'password' => $password,
                'remember_token' => Str::random(60), // ✅ Token acak biar tidak NULL
            ]);

            return Redirect::back()->with(['success' => 'Data karyawan berhasil disimpan!']);
        } catch (\Exception $e) {


            return Redirect::back()->with(['warning' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }

    

      public function edit(Request $request)
    {
        $nik = $request->nik;
        $departemen = DB::table('departemen') ->get();
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        return view('karyawan.edit', compact('departemen', 'karyawan'));
    }


    public function update(Request $request, $nik)
{
    // Validasi input — unique nik di-skip untuk record saat ini
    $request->validate([
        'nik' => 'required|unique:karyawan,nik,' . $nik . ',nik', // ignore current nik
        'nama_lengkap' => 'required',
        'jabatan' => 'required',
        'no_hp' => 'required',
        'kode_dept' => 'required',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'password' => 'nullable|min:6',
    ], [
        'nik.required' => 'NIK wajib diisi.',
        'nik.unique' => 'NIK sudah terdaftar.',
        'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
        'jabatan.required' => 'Jabatan wajib diisi.',
        'no_hp.required' => 'Nomor HP wajib diisi.',
        'kode_dept.required' => 'Departemen wajib dipilih.',
    ]);

    try {
        // Ambil data lama
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        if (!$karyawan) {
            return Redirect::back()->with(['warning' => 'Data karyawan tidak ditemukan.']);
        }

        $newNik = $request->nik;
        $fotoName = $karyawan->foto; // default tetap foto lama

        // Jika ada file foto baru -> upload dan gunakan nama baru berdasarkan newNik
        if ($request->hasFile('foto')) {
            $extension = $request->file('foto')->getClientOriginalExtension();
            $fotoName = $newNik . '.' . $extension;
            $request->file('foto')->storeAs('public/uploads/karyawan', $fotoName);
        } else {
            // Jika nik diubah tetapi tidak meng-upload foto baru -> coba rename file lama agar sesuai nama baru
            if (!empty($karyawan->foto) && $newNik != $karyawan->nik) {
                $oldPath = 'public/uploads/karyawan/' . $karyawan->foto;
                if (Storage::exists($oldPath)) {
                    $oldExt = pathinfo($karyawan->foto, PATHINFO_EXTENSION);
                    $newFotoName = $newNik . '.' . $oldExt;
                    $newPath = 'public/uploads/karyawan/' . $newFotoName;
                    // rename/move
                    Storage::move($oldPath, $newPath);
                    $fotoName = $newFotoName;
                }
            }
        }

        // Siapkan data update
        $updateData = [
            'nik' => $newNik,
            'nama_lengkap' => $request->nama_lengkap,
            'jabatan' => $request->jabatan,
            'no_hp' => $request->no_hp,
            'kode_dept' => $request->kode_dept,
            'foto' => $fotoName,
        ];

        // Jika password diisi -> hash dan update
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        // Lakukan update berdasarkan nik lama
        DB::table('karyawan')->where('nik', $nik)->update($updateData);

        return Redirect::to('/karyawan')->with(['success' => 'Data karyawan berhasil diperbarui!']);
    } catch (\Exception $e) {
        return Redirect::back()->with(['warning' => 'Gagal memperbarui data: ' . $e->getMessage()]);
    }
}

}
