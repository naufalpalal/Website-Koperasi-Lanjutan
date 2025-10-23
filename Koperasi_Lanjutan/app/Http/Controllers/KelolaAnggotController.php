<?php

// app/Http/Controllers/AnggotaController.php
namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Pengurus\MasterSimpananWajib;
use App\Models\Pengurus\SimpananSukarela;
use App\Models\Simpanan;
use App\Models\User\MasterSimpananSukarela;
use App\Models\SimpananPokok;
use App\Models\User;
use App\Models\user\SimpananWajib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KelolaAnggotController extends Controller
{
    // Tampilkan semua anggota
    public function index(Request $request)
    {
        $search = $request->input('q');

        // Query dasar untuk user dengan role anggota
        $query = User::where('role', 'anggota');

        // Jika ada pencarian, tambahkan filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('no_telepon', 'like', "%{$search}%")
                    ->orWhere('unit_kerja', 'like', "%{$search}%")
                    ->orWhere('alamat_rumah', 'like', "%{$search}%");
            });

            // Jika sedang search, tampilkan SEMUA hasil tanpa pagination
            $anggota = $query->orderBy('nama', 'asc')->get();
        } else {
            // Normal: pakai pagination
            $anggota = $query->orderBy('nama', 'asc')->paginate(5)->withQueryString();
        }

        return view('pengurus.KelolaAnggota.index', compact('anggota', 'search'));
    }

    // Tampilkan form tambah anggota
    public function create()
    {
        return view('Pengurus.KelolaAnggota.create');
    }

    public function verifikasi()
    {
        // Ambil semua anggota yang perlu diverifikasi
        // misalnya yang status-nya 'pending' atau 'diajukan'
        $anggota = User::where('role', 'anggota')
            ->where('status', 'pending')
            ->get();

        // Kirim data anggota ke view
        return view('pengurus.KelolaAnggota.verifikasi', compact('anggota'));
    }

    // Setujui anggota
    public function approve($id)
    {
        $anggota = User::findOrFail($id);

        // Ubah status menjadi 'aktif'
        $anggota->update([
            'status' => 'aktif',
        ]);

        // (Opsional) Kirim notifikasi atau buat log
        // Notification::send($anggota, new AnggotaApprovedNotification());

        return redirect()->route('pengurus.KelolaAnggota.verifikasi')
            ->with('success', "Anggota {$anggota->nama} berhasil disetujui dan diaktifkan.");
    }

    // Tolak anggota
    public function reject($id)
    {
        $anggota = User::findOrFail($id);

        // Ubah status menjadi 'ditolak'
        $anggota->update([
            'status' => 'ditolak',
        ]);

        // (Opsional) Hapus dokumen pendaftaran jika perlu
        // Storage::delete($anggota->dokumen_path);

        return redirect()->route('pengurus.KelolaAnggota.verifikasi')
            ->with('error', "Pendaftaran anggota {$anggota->nama} telah ditolak.");
    }


    // Simpan anggota baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:20',
            'password' => 'nullable',
            'nip' => 'nullable|string|max:20',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'alamat_rumah' => 'nullable|string|max:255',
            'unit_kerja' => 'nullable|string|max:255',

            // simpanan
            'simpanan_pokok' => 'required|numeric|min:10000',
            'simpanan_wajib' => 'required|numeric|min:10000',
            'simpanan_sukarela_awal' => 'required|numeric|min:10000',
        ]);

        // buat user baru
        $user = User::create([
            'nama' => $validated['nama'],
            'no_telepon' => $validated['no_telepon'],
            'password' => isset($validated['password']) ? bcrypt($validated['password']) : bcrypt('default123'),
            'nip' => $validated['nip'] ?? null,
            'tempat_lahir' => $validated['tempat_lahir'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'alamat_rumah' => $validated['alamat_rumah'] ?? null,
            'unit_kerja' => $validated['unit_kerja'] ?? null,
            'role' => 'anggota',
            'status' => 'aktif',
        ]);

        // Simpanan Pokok
//   SimpananPokok::create([
//     'users_id'  => $user->id,
//     'nilai'     => $validated['simpanan_pokok'],
//     'tahun'     => now()->format('Y'),
//     'bulan'     => now()->format('m'),
//     'status'    => 'Belum Dibayar',
// ]);


        // Simpanan Wajib
        MasterSimpananWajib::create([
            'users_id' => $user->id,
            'nilai' => $validated['simpanan_wajib'],
            'tahun' => now()->format('Y'),
            'bulan' => now()->format('m'),
            'status' => 'Diajukan',
        ]);


        // Simpanan Sukarela
        MasterSimpananSukarela::create([
            'users_id' => $user->id,
            'nilai' => $validated['simpanan_sukarela_awal'],
            'tahun' => now()->format('Y'),
            'bulan' => now()->format('m'),
            'status' => 'Disetujui',
        ]);


        return redirect()->route('pengurus.KelolaAnggota.index')
            ->with('success', 'Anggota berhasil ditambahkan beserta simpanannya');
    }

    // Tampilkan form edit anggota
    public function edit($id)
    {
        $anggota = User::findOrFail($id);
        return view('pengurus.KelolaAnggota.edit', compact('anggota'));
    }

    // Update data anggota
    public function update(Request $request, $id)
    {
        $anggota = User::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:20',
            'nip' => 'nullable|string|max:20',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'alamat_rumah' => 'nullable|string|max:255',
            'unit_kerja' => 'nullable|string|max:255',

            'simpanan_pokok' => 'nullable|numeric|min:0',
            'simpanan_wajib' => 'nullable|numeric|min:0',
            'simpanan_sukarela' => 'nullable|numeric|min:0',
        ]);

        $anggota->update($validated);

        // Update atau buat simpanan Pokok
        // if ($request->filled('simpanan_pokok')) {
        //     $simpanan = $anggota->simpananPokok()->latest('id')->first();
        //     if ($simpanan) {
        //         $simpanan->update([
        //             'amount' => $request->simpanan_pokok,
        //             'status' => 'Dibayar',
        //             'month'  => now()->format('Y-m-01'),
        //         ]);
        //     } else {
        //         $anggota->simpananPokok()->create([
        //             'amount' => $request->simpanan_pokok,
        //             'status' => 'Dibayar',
        //             'month'  => now()->format('Y-m-01'),
        //         ]);
        //     }
        // }

        // Update atau buat simpanan Wajib
        // if ($request->filled('simpanan_wajib')) {
        //     $simpanan = $anggota->simpananWajib()->latest('id')->first();
        //     if ($simpanan) {
        //         $simpanan->update([
        //         'nilai'  => $request->simpanan_wajib,
        //         'status' => 'Dibayar',
        //         'tahun'  => now()->year,
        //         'bulan'  => now()->month,
        //         ]);
        //     } else {
        //         $anggota->simpananWajib()->create([
        //             'nilai' => $request->simpanan_wajib,
        //             'status' => 'Dibayar',
        //             'tahun'  => now()->year,
        //         'bulan'  => now()->month,
        //         ]);
        //     }
        // }

        // Update atau buat simpanan Sukarela
        if ($request->filled('simpanan_sukarela')) {

            // Cek apakah data untuk user, bulan, dan tahun ini sudah ada di tabel master
            $existing = MasterSimpananSukarela::where('users_id', $anggota->id)
                ->where('tahun', now()->year)
                ->where('bulan', now()->month)
                ->first();

            if ($existing) {
                // Jika sudah ada, maka update nilai dan statusnya
                $existing->update([
                    'nilai' => $request->simpanan_sukarela,
                    'status' => 'Disetujui', // atau 'Dibayar' tergantung proses verifikasi kamu
                ]);
            } else {
                // Jika belum ada, buat data baru
                MasterSimpananSukarela::create([
                    'users_id' => $anggota->id,
                    'nilai' => $request->simpanan_sukarela,
                    'tahun' => now()->year,
                    'bulan' => now()->month,
                    'status' => 'Disetujui', // bisa juga 'Dibayar' kalau langsung bayar
                ]);
            }
        }

        return redirect()->route('pengurus.KelolaAnggota.index')
            ->with('success', 'Data anggota beserta simpanan berhasil diperbarui');
    }

    // Hapus anggota
    public function destroy($id)
    {
        $anggota = User::findOrFail($id);

        // Hapus user, Media Library tidak akan diakses
        $anggota->delete();

        return redirect()
            ->route('pengurus.KelolaAnggota.index')
            ->with('success', 'Anggota berhasil dihapus');
    }
}