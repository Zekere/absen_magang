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
        $query = Karyawan::query();
        $query->select('karyawan.*', 'nama_dept');
        $query->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept');
        $query->orderBy('nama_lengkap');

        if (!empty($request->nama_karyawan)) {
            $query->where('nama_lengkap', 'like', '%' . $request->nama_karyawan . '%');
        }

        if (!empty($request->kode_dept)) {
            $query->where('karyawan.kode_dept', $request->kode_dept);
        }

        $perPage = $request->get('per_page', 10);

        if ($perPage === 'all') {
            $totalCount = $query->count();
            $karyawan = $query->paginate($totalCount > 0 ? $totalCount : 1);
        } else {
            $karyawan = $query->paginate((int)$perPage);
        }

        $departemen = DB::table('departemen')->get();

        return view('karyawan.index', compact('karyawan', 'departemen'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik'          => 'required|unique:karyawan,nik',
            'nama_lengkap' => 'required',
            'jabatan'      => 'required',
            'no_hp'        => 'required',
            'kode_dept'    => 'required',
            'password'     => 'required|min:6',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'face_data'    => 'required', // wajib scan wajah
        ], [
            'nik.required'          => 'NIK wajib diisi.',
            'nik.unique'            => 'NIK sudah terdaftar.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'jabatan.required'      => 'Jabatan wajib diisi.',
            'no_hp.required'        => 'Nomor HP wajib diisi.',
            'kode_dept.required'    => 'Departemen wajib dipilih.',
            'password.required'     => 'Password wajib diisi.',
            'password.min'          => 'Password minimal 6 karakter.',
            'face_data.required'    => 'Scan wajah karyawan wajib dilakukan.',
        ]);

        try {
            $nik          = $request->nik;
            $nama_lengkap = $request->nama_lengkap;
            $jabatan      = $request->jabatan;
            $no_hp        = $request->no_hp;
            $kode_dept    = $request->kode_dept;
            $password     = Hash::make($request->password);

            // ===== 1. Simpan Foto Wajah (Face Recognition) =====
            $faceFileName = null;
            if ($request->face_data) {
                $image     = str_replace('data:image/jpeg;base64,', '', $request->face_data);
                $image     = str_replace(' ', '+', $image);
                $imageData = base64_decode($image);

                $faceFileName = $nik . '_face.jpg';
                $folderPath   = storage_path('app/public/uploads/faces');

                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0755, true);
                }

                file_put_contents($folderPath . '/' . $faceFileName, $imageData);
            }

            // ===== 2. Simpan Foto Profil =====
            $foto = null;
            if ($request->hasFile('foto')) {
                $foto = $nik . '.' . $request->file('foto')->getClientOriginalExtension();
                $request->file('foto')->storeAs('public/uploads/karyawan', $foto);
            } else {
                // Gunakan foto wajah sebagai foto profil jika tidak upload foto profil
                $foto = $faceFileName;
                // Copy dari faces ke karyawan folder
                if ($faceFileName) {
                    $src = storage_path('app/public/uploads/faces/' . $faceFileName);
                    $dst = storage_path('app/public/uploads/karyawan/' . $faceFileName);
                    if (file_exists($src) && !file_exists($dst)) {
                        copy($src, $dst);
                    }
                }
            }

            // ===== 3. Simpan ke Database =====
            DB::table('karyawan')->insert([
                'nik'            => $nik,
                'nama_lengkap'   => $nama_lengkap,
                'jabatan'        => $jabatan,
                'no_hp'          => $no_hp,
                'foto'           => $foto,
                'kode_dept'      => $kode_dept,
                'password'       => $password,
                'remember_token' => Str::random(60),
            ]);

            return Redirect::back()->with(['success' => 'Data karyawan berhasil disimpan!']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }

    public function edit(Request $request)
    {
        $nik        = $request->nik;
        $departemen = DB::table('departemen')->get();
        $karyawan   = DB::table('karyawan')->where('nik', $nik)->first();
        return view('karyawan.edit', compact('departemen', 'karyawan'));
    }

    public function update(Request $request, $nik)
    {
        $nikLama = $nik;

        $request->validate([
            'nik'          => 'required|unique:karyawan,nik,' . $nikLama . ',nik',
            'nama_lengkap' => 'required',
            'jabatan'      => 'required',
            'no_hp'        => 'required',
            'kode_dept'    => 'required',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password'     => 'nullable|min:6',
        ]);

        try {
            $karyawan = DB::table('karyawan')->where('nik', $nikLama)->first();
            if (!$karyawan) {
                return Redirect::back()->with(['warning' => 'Data karyawan tidak ditemukan.']);
            }

            $newNik   = $request->nik;
            $fotoName = $karyawan->foto;

            if ($request->hasFile('foto')) {
                if (!empty($karyawan->foto)) {
                    $oldPath = 'public/uploads/karyawan/' . $karyawan->foto;
                    if (Storage::exists($oldPath)) Storage::delete($oldPath);
                }
                $extension = $request->file('foto')->getClientOriginalExtension();
                $fotoName  = $newNik . '.' . $extension;
                $request->file('foto')->storeAs('public/uploads/karyawan', $fotoName);
            } elseif (!empty($karyawan->foto) && $newNik != $karyawan->nik) {
                $oldPath = 'public/uploads/karyawan/' . $karyawan->foto;
                if (Storage::exists($oldPath)) {
                    $oldExt      = pathinfo($karyawan->foto, PATHINFO_EXTENSION);
                    $newFotoName = $newNik . '.' . $oldExt;
                    Storage::move($oldPath, 'public/uploads/karyawan/' . $newFotoName);
                    $fotoName = $newFotoName;
                }
            }

            // Jika NIK berubah, rename juga foto wajah
            if ($newNik != $nikLama) {
                $oldFacePath = storage_path('app/public/uploads/faces/' . $nikLama . '_face.jpg');
                $newFacePath = storage_path('app/public/uploads/faces/' . $newNik . '_face.jpg');
                if (file_exists($oldFacePath)) {
                    rename($oldFacePath, $newFacePath);
                }
            }

            $updateData = [
                'nik'          => $newNik,
                'nama_lengkap' => $request->nama_lengkap,
                'jabatan'      => $request->jabatan,
                'no_hp'        => $request->no_hp,
                'kode_dept'    => $request->kode_dept,
                'foto'         => $fotoName,
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            DB::table('karyawan')->where('nik', $nikLama)->update($updateData);

            return Redirect::to('/karyawan')->with(['success' => 'Data karyawan berhasil diperbarui!']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Gagal memperbarui data: ' . $e->getMessage()]);
        }
    }

    public function delete($nik)
    {
        try {
            $karyawan = DB::table('karyawan')->where('nik', $nik)->first();

            if ($karyawan) {
                // Hapus foto profil
                if (!empty($karyawan->foto)) {
                    $fotoPath = 'public/uploads/karyawan/' . $karyawan->foto;
                    if (Storage::exists($fotoPath)) Storage::delete($fotoPath);
                }

                // Hapus foto wajah
                $facePath = storage_path('app/public/uploads/faces/' . $nik . '_face.jpg');
                if (file_exists($facePath)) unlink($facePath);

                $delete = DB::table('karyawan')->where('nik', $nik)->delete();

                if ($delete) {
                    return Redirect::back()->with(['success' => 'Data karyawan berhasil dihapus!']);
                } else {
                    return Redirect::back()->with(['warning' => 'Data gagal dihapus!']);
                }
            } else {
                return Redirect::back()->with(['warning' => 'Data karyawan tidak ditemukan!']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Gagal menghapus data: ' . $e->getMessage()]);
        }
    }
}