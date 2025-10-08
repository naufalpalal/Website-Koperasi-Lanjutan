<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\Rules\Password;
// use App\Models\Pinjaman;
use App\Models\Simpanansukarela;
use Illuminate\Support\Facades\Hash;
// use App\Http\Controllers\PinjamanController;

class UserController extends Controller
{
    // 🔹 Menampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login');
    }
 public function create()
    {
        return view('pengurus.register_anggota'); // sesuaikan nama view
    }

    /**
     * Simpan anggota baru.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:50'],
            'no_telepon' => ['required', 'string', 'max:20'],
            'alamat_rumah' => ['nullable', 'string', 'max:255'],
            'unit_kerja' => ['nullable', 'string', 'max:255'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'simpanan_sukarela_awal' => ['nullable', 'numeric', 'min:0'],
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $validated['name'],
            'nip' => $validated['nip'] ?? null,
            'no_telepon' => $validated['no_telepon'],
            'alamat_rumah' => $validated['alamat_rumah'] ?? null,
            'unit_kerja' => $validated['unit_kerja'] ?? null,
            'tempat_lahir' => $validated['tempat_lahir'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Simpan Simpanan Sukarela Awal jika ada
        if(!empty($validated['simpanan_sukarela_awal'])){
            $user->simpananSukarela()->create([
                'nilai' => $validated['simpanan_sukarela_awal'],
                'keterangan' => 'Simpanan awal',
                'tanggal' => now(),
            ]);
        }

        return redirect()->route('pengurus.KelolaAnggota.index')
                         ->with('success', 'Anggota baru berhasil ditambahkan.');
    }
    // 🔹 Proses login
    public function login(Request $request)
    {
        $request->validate([
            'nip' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('nip', $request->nip)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            // Redirect sesuai role
            switch ($user->role) {
                case 'pengurus':
                    return redirect()->route('pengurus.dashboard.index'); // kalau ada dashboard khusus pengurus bisa diarahkan ke sana
                   
                case 'anggota':
                    return redirect()->route('user.dashboard.index');

                default:
                    Auth::logout();
                    return redirect()->route('login')->withErrors([
                        'role' => 'Role tidak dikenali. Hubungi pengurus.',
                    ]);
            }
        }

        // 🔹 Jika gagal login
        return back()->withErrors([
            'nip' => 'NIP atau Password salah.',
        ])->withInput();
    }


    public function dashboard()
    {
        return view('pengurus.index');
    }
   public function dashboardView()
{
    // Hitung total anggota
    $totalAnggota = User::where('role', 'anggota')->count();

    // (opsional) kalau ada tabel Pinjaman / Simpanan bisa ditambah di sini:
    // $totalPinjaman =  Pinjaman::where('status', 'aktif')->count();
    // $totalSimpanan = Simpanan::sum('jumlah');

    return view('pengurus.dashboard.index', compact('totalAnggota'));
}

    public function dashboardUserView()
    {
        return view('user.dashboard.index');
    }
    
}
