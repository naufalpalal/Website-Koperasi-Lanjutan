<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SimpananController extends Controller
{
    public function indexSukarela()
    {
        return view('admin.layouts.simpanan.sukarela.index');
    }

    public function indexWajib()
    {
        return view('admin.layouts.simpanan.wajib.index');
    }
}
