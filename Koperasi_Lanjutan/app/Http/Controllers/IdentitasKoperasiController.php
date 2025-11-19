<?php

namespace App\Http\Controllers;

use App\Models\Pengurus;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\IdentitasKoperasi;
use Illuminate\Support\Facades\DB;

class IdentitasKoperasiController extends Controller
{

    public function index()
    {
        $identitas = IdentitasKoperasi::first();

        // Ambil kolom nama_bendahara_koperasi
        // Asumsi disimpan di DB bentuk JSON: ["Agus", "Budi"]
        // Jika kosong, set sebagai array kosong []
        $list_bendahara = $identitas && $identitas->nama_bendahara_koperasi
            ? json_decode($identitas->nama_bendahara_koperasi)
            : [];

        // Kirim variabel $list_bendahara ke view
        return view('pengurus.setting.setting', compact('identitas', 'list_bendahara'));
    }
    public function edit()
    {
        $identitas = IdentitasKoperasi::first();

        // Jabatan tunggal (ambil satu saja)
        $ketua = Pengurus::where('role', 'ketua')->first();
        $sekretaris = Pengurus::where('role', 'sekretaris')->first();

        // Jabatan khusus tunggal lainnya (sesuaikan jika ada di DB)
        $wadir = Pengurus::where('role', 'wadir')->first();
        $bendahara_gaji = Pengurus::where('role', 'bendahara_gaji')->first();

        // Bendahara (Bisa lebih dari 1, jadi ambil get())
        $list_bendahara = Pengurus::where('role', 'bendahara')->get();

        return view('pengurus.setting.setting', compact(
            'identitas',
            'ketua',
            'list_bendahara', // Mengirim list (collection)
            'sekretaris',
            'wadir',
            'bendahara_gaji'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_koperasi' => 'required|string|max:255',
            'nama_ketua_koperasi' => 'required|string|max:255',
            'nama_sekretaris_koperasi' => 'required|string|max:255',
            'nama_wadir' => 'required|string|max:255',
            'bendahara_gaji' => 'required|string|max:255',

            // Validasi Bendahara sekarang harus berupa ARRAY
            'nama_bendahara_koperasi' => 'required|array',
            'nama_bendahara_koperasi.*' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // ====================================================
            // 1. UPDATE PENGURUS (JABATAN TUNGGAL: Ketua, Sekretaris, dll)
            // ====================================================

            $jabatanTunggal = [
                'nama_ketua_koperasi' => 'ketua',
                'nama_sekretaris_koperasi' => 'sekretaris',
                'bendahara_gaji' => 'bendahara_gaji',
                'nama_wadir' => 'wadir',
            ];

            foreach ($jabatanTunggal as $inputField => $roleName) {
                $namaInput = $request->input($inputField);

                // Cari User
                $user = User::where('nama', $namaInput)->first();

                if ($user) {
                    // Hapus pengurus lama dengan role ini
                    Pengurus::where('role', $roleName)->delete();

                    // Tambah baru dari data User
                    Pengurus::create([
                        'nama' => $user->nama,
                        'no_telepon' => $user->no_telepon,
                        'password' => $user->password,
                        'nip' => $user->nip,
                        'tempat_lahir' => $user->tempat_lahir,
                        'tanggal_lahir' => $user->tanggal_lahir,
                        'alamat_rumah' => $user->alamat_rumah,
                        'unit_kerja' => $user->unit_kerja,
                        'photo_path' => null,
                        'role' => $roleName,
                    ]);
                }
            }

            // ====================================================
            // 2. UPDATE PENGURUS (BENDAHARA - BISA BANYAK)
            // ====================================================

            // Ambil array input nama bendahara
            $inputBendaharaArray = $request->input('nama_bendahara_koperasi');

            // Hapus SEMUA bendahara yang ada di tabel pengurus saat ini
            Pengurus::where('role', 'bendahara')->delete();

            // Loop input array untuk input ulang satu per satu
            if (!empty($inputBendaharaArray)) {
                foreach ($inputBendaharaArray as $namaBendahara) {
                    $userBendahara = User::where('nama', $namaBendahara)->first();

                    if ($userBendahara) {
                        Pengurus::create([
                            'nama' => $userBendahara->nama,
                            'no_telepon' => $userBendahara->no_telepon,
                            'password' => $userBendahara->password,
                            'nip' => $userBendahara->nip,
                            'tempat_lahir' => $userBendahara->tempat_lahir,
                            'tanggal_lahir' => $userBendahara->tanggal_lahir,
                            'alamat_rumah' => $userBendahara->alamat_rumah,
                            'unit_kerja' => $userBendahara->unit_kerja,
                            'photo_path' => null,
                            'role' => 'bendahara', // Role tetap 'bendahara' untuk semua orang ini
                        ]);
                    }
                }
            }

            // ====================================================
            // 3. UPDATE TABEL SETTINGS (IdentitasKoperasi)
            // ====================================================

            $identitas = IdentitasKoperasi::first();

            // Kita gabungkan array nama bendahara menjadi string koma (contoh: "Budi, Ani") 
            // untuk disimpan di tabel settings agar tidak error tipe data
            $stringBendahara = implode(', ', $inputBendaharaArray);

            $dataSettings = [
                'nama_koperasi' => $request->nama_koperasi,
                'nama_ketua_koperasi' => $request->nama_ketua_koperasi,
                'nama_sekretaris_koperasi' => $request->nama_sekretaris_koperasi,
                'nama_bendahara_koperasi' => $stringBendahara, // Simpan sebagai string
                'bendahara_gaji' => $request->bendahara_gaji,
                'nama_wadir' => $request->nama_wadir,
            ];

            if (!$identitas) {
                IdentitasKoperasi::create($dataSettings);
            } else {
                $identitas->update($dataSettings);
            }

            DB::commit();
            return redirect()->route('settings.edit')->with('success', 'Data identitas dan struktur pengurus berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}