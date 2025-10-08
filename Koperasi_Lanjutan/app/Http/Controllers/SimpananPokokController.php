<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SimpananPokok;

class SimpananPokokController extends Controller
{
    public function index()
    {
        $simpanan = SimpananPokok::with('user')->get();
        return response()->json($simpanan);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'jumlah' => 'required|numeric',
            'tahun' => 'required|integer',
            'bulan' => 'required|integer',
        ]);

        $simpanan = SimpananPokok::create([
            'user_id' => $request->user_id,
            'jumlah' => $request->jumlah,
            'tahun' => $request->tahun,
            'bulan' => $request->bulan,
            'status' => 'Belum Dibayar',
        ]);

        return response()->json($simpanan, 201);
    }

    public function show($id)
    {
        $simpanan = SimpananPokok::with('user')->findOrFail($id);
        return response()->json($simpanan);
    }

    public function update(Request $request, $id)
    {
        $simpanan = SimpananPokok::findOrFail($id);

        $simpanan->update($request->only(['jumlah', 'tahun', 'bulan', 'status']));

        return response()->json($simpanan);
    }

    public function destroy($id)
    {
        $simpanan = SimpananPokok::findOrFail($id);
        $simpanan->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
