<?php

namespace App\Http\Controllers\Pengurus;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pengurus\PinjamanSetting;

class PinjamanSettingController extends Controller
{
    // tampilkan semua tenor & bunga
    public function index()
    {
        $settings = PinjamanSetting::all();
        return view('pengurus.pinjaman.settings', compact('settings'));
    }

    // simpan setting baru
    public function store(Request $request)
    {
        $request->validate([
            'tenor' => 'required|integer|min:1|max:36',
            'bunga' => 'required|numeric|min:0|max:100',
        ]);

        PinjamanSetting::create([
            'tenor' => $request->tenor,
            'bunga' => $request->bunga,
        ]);

        return back()->with('success', 'Setting tenor & bunga berhasil ditambahkan.');
    }
}
