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
        $credentials = $request->only('username', 'password');

        // Autentikasi manual agar cocok dengan skema m_user (username + password hash bcrypt)
        $user = \App\Models\UserModel::where('username', $credentials['username'] ?? null)->first();

        $loginSucceeded = $user && \Illuminate\Support\Facades\Hash::check($credentials['password'] ?? '', $user->password);

        if ($loginSucceeded) {
            Auth::login($user);
            $request->session()->regenerate();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'redirect' => url('/')
                ]);
            }

            return redirect('/');
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => false,
                'message' => 'Login Gagal'
            ]);
        }

        return redirect('login')->with('error', 'Login Gagal');
    }

    public function register()
    {
        if (Auth::check()) {
            return redirect('/');
        }

        return view('auth.register');
    }

    public function postRegister(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:3|unique:m_user,username',
            'nama' => 'required|string|max:100',
            'password' => 'required|string|min:6|confirmed'
        ]);

        // set level to STF (staff)
        $level = \App\Models\LevelModel::where('level_kode', 'STF')->first();
        $level_id = $level ? $level->level_id : 3; // fallback to 3

        \App\Models\UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password),
            'level_id' => $level_id
        ]);

        return redirect('login')->with('success', 'Registrasi berhasil. Silakan login.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('login');
    }
}
