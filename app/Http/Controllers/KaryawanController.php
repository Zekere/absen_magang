<?php

namespace App\Http\Controllers;
use App\Models\Karyawan;
use DB;
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
        $karyawan = $query->paginate(10);


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
}