<?php

namespace App\Http\Controllers;

use App\Models\Pengurus;
use Illuminate\Http\Request;
use App\Models\IdentitasKoperasi;

class IdentitasKoperasiController extends Controller
{
    public function edit()
{
    // Mengambil data identitas koperasi
    $identitas = IdentitasKoperasi::first();

    // Mengambil data pengurus berdasarkan role atau jabatan tertentu
    $ketua = Pengurus::where('role', 'ketua')->first();
    $bendahara = Pengurus::where('role', 'bendahara')->first();
    $sekretaris = Pengurus::where('role', 'sekretaris')->first();

    // Mengirim data ke view
    return view('pengurus.setting.setting', compact('identitas', 'ketua', 'bendahara', 'sekretaris'));
}


    public function update(Request $request)
    {
        $request->validate([
            'nama_koperasi' => 'required|string|max:255',
            'nama_ketua_koperasi' => 'required|string|max:255',
            'nama_sekretaris_koperasi' => 'required|string|max:255',
            'nama_bendahara_koperasi' => 'required|string|max:255',
            'nama_bendahara_pengeluaran' => 'required|string|max:255',
            'nama_wadir' => 'required|string|max:255',
        ]);
        $identitas = IdentitasKoperasi::first();

        if (!$identitas) {
            $identitas = new IdentitasKoperasi();
            $identitas->fill($request->only([
                'nama_koperasi',
                'nama_ketua_koperasi',
                'nama_sekretaris_koperasi',
                'nama_bendahara_koperasi',
                'nama_bendahara_pengeluaran',
                'nama_wadir',
            ]));
            $identitas->save();
        } else {
            $identitas->update($request->only([
                'nama_koperasi',
                'nama_ketua_koperasi',
                'nama_sekretaris_koperasi',
                'nama_bendahara_koperasi',
                'nama_bendahara_pengeluaran',
                'nama_wadir',
            ]));
        }


        return redirect()->route('settings.edit')->with('success', 'Data identitas koperasi berhasil diperbarui.');
    }

   
}
