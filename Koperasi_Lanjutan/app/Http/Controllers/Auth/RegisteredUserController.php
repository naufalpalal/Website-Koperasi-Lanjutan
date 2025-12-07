<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'jenis_anggota' => 'required|in:pegawai,non_pegawai',
            'name' => 'required|string|max:255',
            'nip_username' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:20',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'alamat_rumah' => 'nullable|string|max:500',
            'unit_kerja' => 'nullable|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'simpanan_sukarela' => 'nullable|numeric|min:0',
        ]);


        $data = [
            'nama' => $request->name,
            'no_telepon' => $request->no_telepon,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat_rumah' => $request->alamat_rumah,
            'unit_kerja' => $request->unit_kerja,
            'password' => Hash::make($request->password),
        ];


        if ($request->jenis_anggota === 'pegawai') {
            $data['nip'] = $request->nip_username; // simpan di nip
            $data['username'] = null;
        } else {
            $data['username'] = $request->nip_username; // simpan di username
            $data['nip'] = null;
        }

        // Buat user
        $user = User::create($data);
        // Login otomatis
        Auth::login($user);

        // ====== Simpanan Sukarela Pertama ======
        if ($request->simpanan_sukarela && $request->simpanan_sukarela > 0) {
            \App\Models\User\MasterSimpananSukarela::create([
                'users_id' => $user->id,
                'nilai' => $request->simpanan_sukarela,
                'bulan' => now()->month,
                'tahun' => now()->year,
                'status' => 'Pending',
            ]);
        }

        return view('guest.dashboard')->with('success', 'Pendaftaran berhasil! Selamat datang di koperasi.');
    }
}
