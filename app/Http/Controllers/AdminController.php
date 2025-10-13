<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('users');

        // Filter pencarian nama
        if (!empty($request->nama_admin)) {
            $query->where('name', 'like', '%' . $request->nama_admin . '%');
        }

        // Pagination
        $admin = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.index', compact('admin'));
    }

    public function store(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email|max:255',
                'password' => 'required|string|min:6'
            ], [
                'name.required' => 'Nama wajib diisi',
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah terdaftar',
                'password.required' => 'Password wajib diisi',
                'password.min' => 'Password minimal 6 karakter'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Insert data admin
            DB::table('users')->insert([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data admin berhasil ditambahkan'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $admin = DB::table('users')->where('id', $id)->first();

        if (!$admin) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        return view('admin.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $id,
                'password' => 'nullable|string|min:6'
            ], [
                'name.required' => 'Nama wajib diisi',
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah terdaftar',
                'password.min' => 'Password minimal 6 karakter'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('warning', $validator->errors()->first());
            }

            // Data yang akan diupdate
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'updated_at' => now()
            ];

            // Jika password diisi, update password
            if (!empty($request->password)) {
                $data['password'] = Hash::make($request->password);
            }

            // Update data
            DB::table('users')
                ->where('id', $id)
                ->update($data);

            return redirect('/admin')->with('success', 'Data admin berhasil diupdate');

        } catch (\Exception $e) {
            return redirect()->back()->with('warning', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $delete = DB::table('users')->where('id', $id)->delete();

            if ($delete) {
                return redirect('/admin')->with('success', 'Data admin berhasil dihapus');
            } else {
                return redirect('/admin')->with('warning', 'Data admin gagal dihapus');
            }
        } catch (\Exception $e) {
            return redirect('/admin')->with('warning', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}