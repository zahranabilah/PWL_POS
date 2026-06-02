<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect('/');
        }

        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {

            $credentials = $request->only('username', 'password');

            // Autentikasi manual agar cocok dengan skema m_user (username + password hash bcrypt)
            $user = \App\Models\UserModel::where('username', $credentials['username'] ?? null)->first();

            // Debug cepat bila login gagal (bisa dibuka dari response JSON)
            // $ok = $user ? \Illuminate\Support\Facades\Hash::check($credentials['password'] ?? '', $user->password) : false;
            // return response()->json(['status'=>false,'message'=>'DEBUG','ok'=>$ok]);

            if ($user && \Illuminate\Support\Facades\Hash::check($credentials['password'] ?? '', $user->password)) {
                Auth::login($user);




                return response()->json([
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'redirect' => url('/')
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Login Gagal'
            ]);
        }

        return redirect('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('login');
    }
}