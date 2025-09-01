<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PinjamanController extends Controller
{
    public function index()
    {
        return view('admin.pinjaman.index');
    }
}
