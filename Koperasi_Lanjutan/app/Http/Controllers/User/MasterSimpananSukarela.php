<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MasterSimpananSukarela extends Controller
{
    public function index()
    {
        return view('user.simpanan.sukarela.pengajuan');
    }
}
