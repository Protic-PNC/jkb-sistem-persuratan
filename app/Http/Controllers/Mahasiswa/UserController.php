<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

    public function getMahasiswaByNPM(Request $request)
    {
    $mahasiswa = User::where('username', $request->npm)->first();

    if ($mahasiswa) {
        return response()->json(['nama_mhs' => $mahasiswa->nama_pemilik]);
    }

    return response()->json(['nama_mhs' => '']);
    }

}
