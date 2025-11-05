<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PengurusController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login-pengurus');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nip' => 'required',
            'password' => 'required',
        ]);

        if (Auth::guard('pengurus')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/pengurus/dashboard');
        }

        return back()->withErrors([
            'nip' => 'NIP atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('pengurus')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/pengurus/login');
    }
      public function dashboard()
    {
        $totalAnggota = User::count();
        return view('pengurus.dashboard.index', compact('totalAnggota'));
    }

}