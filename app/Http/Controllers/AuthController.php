<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function proseslogin (Request $request)
    {
if (Auth::guard('karyawan')->attempt(['nik'=>$request->nik,'password'=>$request->password])){
    return redirect('/dashboard');
}else {
    return redirect('/')->with(['error'=>'NIK atau Password salah!']);
}
    }
    public function logout(){
        if(Auth::guard('karyawan')->check()){
Auth::guard('karyawan')->logout();
return redirect('/');
        }
    }
}
