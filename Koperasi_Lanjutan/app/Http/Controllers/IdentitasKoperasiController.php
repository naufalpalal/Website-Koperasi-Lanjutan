<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IdentitasKoperasi;

class IdentitasKoperasiController extends Controller
{
    public function edit()
    {
        $identitas = IdentitasKoperasi::first(); // Ambil data pertama
        return view('pengurus.setting.setting', compact('identitas'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_koperasi' => 'required|string|max:255',
            'nama_ketua_koperasi' => 'required|string|max:255',
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
                'nama_bendahara_koperasi',
                'nama_bendahara_pengeluaran',
                'nama_wadir',
            ]));
            $identitas->save();
        } else {
            $identitas->update($request->only([
                'nama_koperasi',
                'nama_ketua_koperasi',
                'nama_bendahara_koperasi',
                'nama_bendahara_pengeluaran',
                'nama_wadir',
            ]));
        }


        return redirect()->route('settings.edit')->with('success', 'Data identitas koperasi berhasil diperbarui.');
    }
}
