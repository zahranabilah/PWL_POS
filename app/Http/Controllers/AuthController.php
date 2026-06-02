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

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('login');
    }
}
