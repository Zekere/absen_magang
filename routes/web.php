<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\KonfigurasiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KaryawanController; 
use App\Http\Controllers\AdminController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\JamKerjaController;
use App\Http\Controllers\LemburController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==================== ROUTE UNTUK GUEST (Belum Login) ====================
Route::middleware(['guest:karyawan'])->group(function(){
    // Halaman Login Karyawan
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');
    
    // Halaman Register
    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    
    // Proses Login & Register
    Route::post('/proseslogin', [AuthController::class, 'proseslogin']);
    Route::post('/prosesregister', [RegisterController::class, 'prosesregister']);
});

// ==================== ROUTE UNTUK ADMIN (Guest) ====================
Route::middleware(['guest:user'])->group(function(){
    Route::get('/panel', function () {
        return view('auth.login');
    })->name('loginadmin');
    
    Route::post('/prosesloginadmin', [AuthController::class, 'prosesloginadmin']);
});

// ==================== ROUTE UNTUK KARYAWAN (Sudah Login) ====================
Route::middleware(['auth:karyawan'])->group(function(){
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/logout', [AuthController::class, 'logout']);
    
    // Presensi
    Route::get('/presensi/create', [PresensiController::class, 'create']);
    Route::post('/presensi/store', [PresensiController::class, 'store']);
    
    // Profile
    Route::get('/editprofile', [PresensiController::class, 'editprofile']);
    Route::post('/presensi/{nik}/updateprofile', [PresensiController::class, 'updateprofile']);
    
    // Histori Presensi
    Route::get('/presensi/histori', [PresensiController::class, 'histori']);
    Route::post('/gethistori', [PresensiController::class, 'gethistori']);
    
    // Izin
    Route::get('/presensi/izin', [PresensiController::class, 'izin']);
    Route::get('/presensi/buatizin', [PresensiController::class, 'buatizin']);
    Route::post('/presensi/storeizin', [PresensiController::class, 'storeizin']);
    
    // Notifikasi Check (API untuk cek update izin)
    Route::get('/check-izin-updates', function () {
        try {
            $userId = Auth::user()->nik;
            $lastCheck = session('last_izin_check', now()->subMinutes(3));
            
            $recentUpdate = DB::table('pengajuan_izin')
                ->where('nik', $userId)
                ->where('updated_at', '>', $lastCheck)
                ->whereIn('status_approved', [1, 2])
                ->orderBy('updated_at', 'desc')
                ->first();
            
            session(['last_izin_check' => now()]);
            
            if ($recentUpdate) {
                $status = $recentUpdate->status_approved == 1 ? 'approved' : 'rejected';
                $message = $status == 'approved' 
                    ? 'Pengajuan izin Anda tanggal ' . date('d/m/Y', strtotime($recentUpdate->tgl_izin)) . ' disetujui!'
                    : 'Pengajuan izin ditolak. Alasan: ' . ($recentUpdate->alasan_tolak ?? 'Tidak memenuhi syarat');
                
                return response()->json([
                    'success' => true,
                    'has_update' => true,
                    'notification' => [
                        'status' => $status,
                        'message' => $message
                    ]
                ]);
            }
            
            return response()->json([
                'success' => true,
                'has_update' => false
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    });

    // ===== Lembur Karyawan =====
    // PENTING: Route yang lebih spesifik harus di ATAS yang umum!
    Route::get('/lembur/create', [LemburController::class, 'create'])->name('lembur.create');
    Route::get('/lembur/{id}/edit', [LemburController::class, 'edit'])->name('lembur.edit');
    Route::get('/lembur/{id}/bukti', [LemburController::class, 'lihatBukti'])->name('lembur.bukti');
    Route::get('/lembur', [LemburController::class, 'index'])->name('lembur.index');
    Route::post('/lembur/store', [LemburController::class, 'store'])->name('lembur.store');
    Route::put('/lembur/{id}/update', [LemburController::class, 'update'])->name('lembur.update');
    Route::delete('/lembur/{id}/delete', [LemburController::class, 'destroy'])->name('lembur.destroy');
    Route::get('/lembur/{id}', [LemburController::class, 'show'])->name('lembur.show');
});

// ==================== ROUTE UNTUK ADMIN (Sudah Login) ====================
Route::middleware(['auth:user'])->group(function () {
    // Dashboard Admin
    Route::get('/panel/dashboardadmin', [DashboardController::class, 'dashboardadmin']);
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboard/data', [DashboardController::class, 'getDashboardData'])->name('admin.dashboard.data');
    Route::get('/logoutadmin', [AuthController::class, 'logoutadmin']);
    
    // ===== Data Karyawan =====
    Route::get('/karyawan', [KaryawanController::class, 'index']);
    Route::post('/karyawan/store', [KaryawanController::class, 'store']);
    Route::post('/karyawan/edit', [KaryawanController::class, 'edit']);
    Route::post('/karyawan/{nik}/update', [KaryawanController::class, 'update']);
    Route::put('/karyawan/{nik}/update', [KaryawanController::class, 'update']);
    Route::post('/karyawan/{nik}/delete', [KaryawanController::class, 'delete']);
    
    // ===== Departemen =====
    Route::get('/departemen', [DepartemenController::class, 'index']);
    Route::post('/departemen/store', [DepartemenController::class, 'store']);
    Route::post('/departemen/edit', [DepartemenController::class, 'edit']);
    Route::post('/departemen/{kode_dept}/update', [DepartemenController::class, 'update']);
    Route::post('/departemen/{kode_dept}/delete', [DepartemenController::class, 'delete']);
    
    // ===== Monitoring Presensi =====
    Route::get('/presensi/monitoring', [PresensiController::class, 'monitoring']);
    Route::post('/getpresensi', [PresensiController::class, 'getpresensi']);
    Route::post('/presensi/showmap', [PresensiController::class, 'showmap']);
    Route::post('/map', [PresensiController::class, 'showMap'])->name('map.show');
    Route::delete('/presensi/delete/{id}', [PresensiController::class, 'deletePresensi']);
    
    // ===== Laporan =====
    Route::get('/presensi/laporan', [PresensiController::class, 'laporan']);
    Route::post('/presensi/cetaklaporan', [PresensiController::class, 'cetaklaporan']);
    
    // ===== Rekap =====
    Route::get('/presensi/rekap', [PresensiController::class, 'rekap']);
    Route::post('/presensi/cetakrekap', [PresensiController::class, 'cetakrekap']);
    
    // ===== Izin/Sakit =====
    Route::get('/presensi/izinsakit', [PresensiController::class, 'izinsakit']);
    Route::get('/presensi/izinsakit/filter', [PresensiController::class, 'filterIzinSakit']);
    Route::post('/presensi/approved', [PresensiController::class, 'approved']);
    Route::get('/presensi/{id}/batalkanizinsakit', [PresensiController::class, 'batalkanizinsakit']);
    Route::get('/presensi/{id}/lihatbukti', [PresensiController::class, 'lihatbukti']);
    Route::get('/presensi/{id}/downloadbukti', [PresensiController::class, 'downloadbukti']);
    
    // ===== Konfigurasi =====
    Route::get('/konfigurasi', [KonfigurasiController::class, 'index']);
    Route::post('/konfigurasi/updatelokasikantor', [KonfigurasiController::class, 'updatelokasikantor']);
    
    // ===== Data Admin =====
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/admin/store', [AdminController::class, 'store'])->name('admin.store');
    Route::post('/admin/edit', [AdminController::class, 'edit'])->name('admin.edit');
    Route::post('/admin/{id}/update', [AdminController::class, 'update'])->name('admin.update');
    Route::post('/admin/{id}/delete', [AdminController::class, 'delete'])->name('admin.delete');

    // ===== Jam Kerja =====
    Route::get('/jamkerja', [JamKerjaController::class, 'index']);
    Route::post('/jamkerja/update', [JamKerjaController::class, 'update']);

    // ===== Lembur Admin =====
    Route::get('/admin/lembur/data', [LemburController::class, 'dataLembur'])->name('admin.lembur.data');
    Route::get('/admin/lembur/laporan', [LemburController::class, 'laporan'])->name('admin.lembur.laporan');
    Route::post('/admin/lembur/cetaklaporan', [LemburController::class, 'cetakLaporan'])->name('admin.lembur.cetak');
    Route::post('/admin/lembur/exportexcel', [LemburController::class, 'exportExcel'])->name('admin.lembur.export');
});

// ==================== ROUTE PREFIX PANEL (Admin) ====================
Route::prefix('panel')->middleware(['auth:user'])->group(function () {

    // ===== Jam Kerja =====
    Route::get('/jamkerja', [JamKerjaController::class, 'index']);
    Route::post('/jamkerja/update', [JamKerjaController::class, 'update']);

    // ===== Konfigurasi =====
    Route::get('/konfigurasi', [KonfigurasiController::class, 'index']);
    Route::post('/konfigurasi/updatelokasikantor', [KonfigurasiController::class, 'updatelokasikantor']);

    // ===== Lembur Admin (via /panel) =====
    // PERBAIKAN: GET method agar bisa dipanggil dari window.open() dan window.location.href
    Route::get('/lembur/laporan', [LemburController::class, 'laporan'])->name('panel.lembur.laporan');
    Route::get('/lembur/cetaklaporan', [LemburController::class, 'cetakLaporan'])->name('panel.lembur.cetak');
    Route::get('/lembur/exportexcel', [LemburController::class, 'exportExcel'])->name('panel.lembur.export');
    Route::get('/lembur/data', [LemburController::class, 'dataLembur'])->name('panel.lembur.data');
});

// ==================== ROUTE EVENT (Admin) ====================
Route::prefix('admin')->middleware(['auth:user'])->group(function () {
    Route::get('/events', [DashboardController::class, 'getEvents'])->name('admin.events.index');
    Route::post('/events', [DashboardController::class, 'storeEvent'])->name('admin.events.store');
    Route::get('/events/{id}', [DashboardController::class, 'getEventDetail'])->name('admin.events.show');
    Route::put('/events/{id}', [DashboardController::class, 'updateEvent'])->name('admin.events.update');
    Route::delete('/events/{id}', [DashboardController::class, 'deleteEvent'])->name('admin.events.delete');
});

// ===== Konfigurasi Lokasi Kantor (Multi-Lokasi) =====
// Route::get('/konfigurasi', [KonfigurasiController::class, 'index']);
// Route::post('/konfigurasi/updatelokasikantor', [KonfigurasiController::class, 'updatelokasikantor']);
// Route::post('/konfigurasi/storelokasi', [KonfigurasiController::class, 'storelokasi']);
// Route::post('/konfigurasi/updatelokasi/{id}', [KonfigurasiController::class, 'updatelokasi']);
// Route::get('/konfigurasi/deletelokasi/{id}', [KonfigurasiController::class, 'deletelokasi']);

// {{ 

// }}
// ===== Konfigurasi Lokasi Kantor (Multi-Lokasi) =====
Route::get('/konfigurasi', [KonfigurasiController::class, 'index']);
Route::post('/konfigurasi/updatelokasikantor', [KonfigurasiController::class, 'updatelokasikantor']);
Route::post('/konfigurasi/storelokasi', [KonfigurasiController::class, 'storelokasi']);
Route::post('/konfigurasi/updatelokasi/{id}', [KonfigurasiController::class, 'updatelokasi']);
Route::post('/konfigurasi/deletelokasi/{id}', [KonfigurasiController::class, 'deletelokasi']); // ← ubah GET ke POST

Route::post('/presensi/setupwajah', [PresensiController::class, 'setupwajah']);