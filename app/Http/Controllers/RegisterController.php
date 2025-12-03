<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Tampilkan halaman register
     */
    public function index()
    {
        // Load departemen untuk dropdown
        $departemen = DB::table('departemen')->get();
        return view('auth.register', compact('departemen'));
    }

    /**
     * Proses registrasi
     */
    public function prosesregister(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nik' => 'required|unique:karyawan,nik',
            'nama_lengkap' => 'required|string|max:100',
            'jabatan' => 'required|string|max:20',
            'no_hp' => 'required|string|max:13',
            'password' => 'required|string|min:6',
            'kode_dept' => 'required|exists:departemen,kode_dept',
            'face_data' => 'required', // Base64 image data untuk face recognition
            'foto_profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Foto profil optional
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'jabatan.required' => 'Jabatan wajib diisi.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'kode_dept.required' => 'Departemen wajib dipilih.',
            'kode_dept.exists' => 'Departemen tidak valid.',
            'face_data.required' => 'Verifikasi wajah wajib dilakukan.',
            'foto_profile.image' => 'File harus berupa gambar.',
            'foto_profile.mimes' => 'Format foto harus JPG, JPEG, atau PNG.',
            'foto_profile.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $nik = $request->nik;
            $nama_lengkap = $request->nama_lengkap;
            $jabatan = $request->jabatan;
            $no_hp = $request->no_hp;
            $kode_dept = $request->kode_dept;
            $password = Hash::make($request->password);
            $face_data = $request->face_data;

            // ===== 1. Simpan Foto Wajah untuk Face Recognition =====
            $faceFileName = null;
            if ($face_data) {
                // Decode base64 image
                $image = str_replace('data:image/jpeg;base64,', '', $face_data);
                $image = str_replace(' ', '+', $image);
                $imageData = base64_decode($image);

                // Generate nama file
                $faceFileName = $nik . '_face.jpg';
                $folderPath = storage_path('app/public/uploads/faces');

                // Buat folder jika belum ada
                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0755, true);
                }

                // Simpan file
                file_put_contents($folderPath . '/' . $faceFileName, $imageData);
            }

            // ===== 2. Simpan Foto Profil (jika ada) =====
            $fotoProfileName = null;
            if ($request->hasFile('foto_profile')) {
                $extension = $request->file('foto_profile')->getClientOriginalExtension();
                $fotoProfileName = $nik . '.' . $extension;
                $folderPath = 'public/uploads/karyawan';
                $request->file('foto_profile')->storeAs($folderPath, $fotoProfileName);
            } else {
                // Jika tidak upload foto profil, gunakan foto wajah sebagai foto profil
                $fotoProfileName = $faceFileName;
            }

            // Insert ke database
            DB::table('karyawan')->insert([
                'nik' => $nik,
                'nama_lengkap' => $nama_lengkap,
                'jabatan' => $jabatan,
                'no_hp' => $no_hp,
                'foto' => $fotoProfileName, // Foto profil untuk ditampilkan
                'kode_dept' => $kode_dept,
                'password' => $password,
                'remember_token' => Str::random(60),
            ]);

            // Simpan path foto wajah untuk face recognition di tabel terpisah (opsional)
            // Atau bisa ditambahkan kolom 'face_image' di tabel karyawan
            // DB::table('face_recognition')->insert([
            //     'nik' => $nik,
            //     'face_image' => $faceFileName,
            // ]);

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil! Silakan login.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendaftar: ' . $e->getMessage()
            ], 500);
        }
    }
}