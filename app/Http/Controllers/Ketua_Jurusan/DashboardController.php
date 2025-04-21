<?php

namespace App\Http\Controllers\Ketua_Jurusan;

use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Models\PelanggaranAkademik;
use App\Http\Controllers\Controller;
use App\Models\PengunduranDiri;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pelanggaranKajurs = PelanggaranAkademik::where('nama_pelapor', $user->nama_pemilik)
                                 ->latest()
                                 ->paginate(10);
        $pengunduranDiriKelas = PengunduranDiri::latest()->paginate(10);
        $pelanggaranAkademikSemuaKelas = PelanggaranAkademik::all();
        $pengunduranDiriSemuaKelas = PengunduranDiri::all();

        return view('dashboard.ketua_jurusan.index', [
            'title' => 'Dashboard Ketua Jurusan',
            'pelanggaranKelas' => $pelanggaranAkademikSemuaKelas,
            'pengundurans' => $pengunduranDiriKelas,
            'pelanggarans' => $pelanggaranKajurs,
            'totalPelanggaranAkademikKajur' => $pelanggaranKajurs->count(),
            'totalPelanggaranAkademikSemuaKelas' => $pelanggaranAkademikSemuaKelas->count(),
            'totalPengunduranSemuaKelas' => $pengunduranDiriSemuaKelas->count()
        ]);
    }
}
