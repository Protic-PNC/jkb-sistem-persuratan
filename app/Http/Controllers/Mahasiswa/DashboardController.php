<?php

namespace App\Http\Controllers\Mahasiswa;

use Illuminate\Http\Request;
use App\Models\PengunduranDiri;
use App\Models\PernyataanMagang;
use App\Models\PelanggaranAkademik;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('dashboard.mahasiswa.index', [
            'title' => 'Dashboard Mahasiswa',
            'pernyataans' => PernyataanMagang::where('username', $user->username)
                                 ->latest()
                                 ->paginate(10),
            'pelanggarans' => PelanggaranAkademik::where('username', $user->username)
                                 ->latest()
                                 ->paginate(10),
            'pengundurans' => PengunduranDiri::where('username', $user->username)->latest()->paginate(10),
            'totalPernyataan' => PernyataanMagang::where('username', $user->username)->count(),
            'totalPengunduran' => PengunduranDiri::where('username', $user->username)->count(),
            'totalPelanggaran' => PelanggaranAkademik::where('username', $user->username)->count()
        ]);
    }
}
