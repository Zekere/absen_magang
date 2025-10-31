<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\KonfigurasiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KaryawanController; 
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['guest:karyawan'])->group(function(){
Route::get('/',function () {
    return view('auth.login');
})->name('login');
Route::post('/proseslogin', [AuthController::class, 'proseslogin']);
});


Route::middleware(['guest:user'])->group(function(){
Route::get('/panel',function () {
    return view('auth.loginadmin');
})->name('loginadmin');

Route::post('/prosesloginadmin', [AuthController::class, 'prosesloginadmin']);

});

Route::middleware(['auth:karyawan'])->group(function(){
    Route::get( '/dashboard', [DashboardController::class, 'index']);
Route::get('/logout',[AuthController::class,'logout']);

Route::get('/presensi/create',[PresensiController::class,'create']);
Route::post('/presensi/store',[PresensiController::class,'store']);

Route::get('/editprofile',[PresensiController::class,'editprofile']);
Route::post('/presensi/{nik}/updateprofile', [PresensiController::class,'updateprofile']);

//histori presensi

Route::get('/presensi/histori',[PresensiController::class,'histori']);
Route::post('/gethistori',[PresensiController::class,'gethistori'] );

//izin
Route::get('/presensi/izin',[PresensiController::class,'izin']);
Route::get('/presensi/buatizin',[PresensiController::class,'buatizin']);
Route::post('/presensi/storeizin',[PresensiController::class,'storeizin']);
});

Route::middleware(['auth:user'])->group(function (){
Route::get('/logoutadmin',[AuthController::class,'logoutadmin']);
Route::get('/panel/dashboardadmin', [DashboardController::class, 'dashboardadmin']);

//data karyawan
Route::get('/karyawan', [KaryawanController::class, 'index']);
Route::post('/karyawan/store',[KaryawanController::class, 'store']);

Route::post('/karyawan/edit',[KaryawanController::class, 'edit']);
Route::post('/karyawan/{nik}/update',[KaryawanController::class, 'update']);
Route::put('/karyawan/{nik}/update', [KaryawanController::class, 'update']);
Route::post('/karyawan/{nik}/delete',[KaryawanController::class, 'delete']);

//departemen
Route::get('/departemen',[DepartemenController::class,'index']);
Route::post('/departemen/store',[DepartemenController::class,'store']);
Route::post('/departemen/edit',[DepartemenController::class,'edit']);
Route::post('/departemen/{kode_dept}/update', [DepartemenController::class, 'update']);
Route::post('/departemen/{kode_dept}/delete',[DepartemenController::class,'delete']);

//monitoring
//Presensi
Route::get('presensi/monitoring',[PresensiController::class,'monitoring']);
Route::post('/getpresensi',[PresensiController::class,'getpresensi']);
Route::post('/map', [PresensiController::class,'map']);

//laporan
Route::get('/presensi/laporan',[PresensiController::class,'laporan']);
Route::post('/presensi/cetaklaporan',[PresensiController::class,'cetaklaporan']);
// Route untuk generate PDF. Sesuaikan method dan Controller.
//rekap
Route::get('/presensi/rekap',[PresensiController::class,'rekap']);
Route::post('/presensi/cetakrekap',[PresensiController::class,'cetakrekap']);

//konfigurasi
Route::get('/konfigurasi', [KonfigurasiController::class, 'index']);
Route::post('/konfigurasi/updatelokasikantor', [KonfigurasiController::class, 'updatelokasikantor']);

// Izinsakit routes
Route::get('/presensi/izinsakit', [PresensiController::class, 'izinsakit']);
Route::get('/presensi/izinsakit/filter', [PresensiController::class, 'filterIzinSakit']); // ⬅️ letakkan di atas
Route::post('/presensi/approved', [PresensiController::class, 'approved']);
Route::get('/presensi/{id}/batalkanizinsakit', [PresensiController::class, 'batalkanizinsakit']);


// Route untuk Data Admin
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
Route::post('/admin/store', [AdminController::class, 'store'])->name('admin.store');
Route::post('/admin/edit', [AdminController::class, 'edit'])->name('admin.edit');
Route::post('/admin/{id}/update', [AdminController::class, 'update'])->name('admin.update');
Route::post('/admin/{id}/delete', [AdminController::class, 'delete'])->name('admin.delete');

// Route untuk dashboard admin
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

// Route untuk AJAX get data dashboard berdasarkan tanggal
Route::get('/admin/dashboard/data', [DashboardController::class, 'getDashboardData'])->name('admin.dashboard.data');
});

Route::middleware(['auth:karyawan'])->group(function() {
    Route::post('/presensi/storeizin', [PresensiController::class, 'storeizin']);
});

Route::get('/presensi/{id}/lihatbukti', [PresensiController::class, 'lihatbukti']);
Route::get('/presensi/{id}/downloadbukti', [PresensiController::class, 'downloadbukti']);
