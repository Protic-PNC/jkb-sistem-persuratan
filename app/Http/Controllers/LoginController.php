<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login.index', [
            'title' => 'Login',
        ]);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'npm' => ['required', 'alpha_dash', 'max:255'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
    
            if ($user->role_id == 1) {
                return redirect()->intended('/dashboard/admin');
            } elseif ($user->role_id == 2) {
                return redirect()->intended('/dashboard/mahasiswa');
            }
        }

        return back()->with('loginError', 'Username atau password salah.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
