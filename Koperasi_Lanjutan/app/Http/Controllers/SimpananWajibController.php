<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NominalWajib;

class SimpananWajibController extends Controller
{
    public function index()
    {
        $nominal = NominalWajib::latest()->first(); // ambil nominal terbaru
        return view('admin.nominal_wajib.index', compact('nominal'));
    }

    public function editNominalWajib()
    {
        $nominal = NominalWajib::latest()->first();
        return view('admin.nominal_wajib.edit', compact('nominal'));
    }

    public function updateNominalWajib(Request $request)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:0',
            'tahun' => 'nullable|digits:4'
        ]);

        NominalWajib::create([
            'nominal' => $request->nominal,
            'tahun' => $request->tahun,
        ]);

        return redirect()->route('admin.nominal_wajib.index')
                         ->with('success', 'Nominal simpanan wajib berhasil diperbarui.');
    }
}
