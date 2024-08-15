<?php

namespace App\Http\Controllers\Mahasiswa;

use Illuminate\Http\Request;
use App\Models\PernyataanMagang;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('dashboard.mahasiswa.index', [
            'title' => 'Dashboard Mahasiswa',
            'pernyataans' => PernyataanMagang::where('npm', $user->npm)
                                 ->latest()
                                 ->paginate(5),
            // 'pengundurans' => PengunduranDiri::where('npm', $user->npm)->latest()->paginate(5),
            // 'pelanggarans' => PelanggaranPeraturanAkademik::where('npm', $user->npm)->latest()->paginate(5),
            'totalPernyataan' => PernyataanMagang::where('npm', $user->npm)->count(),
            // 'totalPengunduran' => PengunduranDiri::where('npm', $user->npm)->count(),
            // 'totalPelanggaran' => PelanggaranPeraturanAkademik::where('npm', $user->npm)->count()
        ]);
    }
}
