<?php

namespace App\Http\Controllers\Ketua_Jurusan;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function getDosenWali(Request $request)
    {

    $kelas = Kelas::where('id_kelas', $request->kelas_id)->first();

    if ($kelas) {
        $dosenWali = User::where('username', $kelas->username_dosen_wali)->first();

        if ($dosenWali) {
            return response()->json(['nama_dosen_wali' => $dosenWali->nama_pemilik]);
        }
    }

    return response()->json(['nama_dosen_wali' => '']);
    }

    public function getMahasiswaByNPM(Request $request)
    {
    $mahasiswa = User::where('username', $request->npm)->first();

    if ($mahasiswa) {
        return response()->json(['nama_mhs' => $mahasiswa->nama_pemilik]);
    }

    return response()->json(['nama_mhs' => '']);
    }
}
