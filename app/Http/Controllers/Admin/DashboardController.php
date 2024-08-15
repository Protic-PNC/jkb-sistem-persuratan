<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PelanggaranPeraturanAkademik;
use App\Models\PengunduranDiri;
use App\Models\PernyataanMagang;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.admin.index', [
            'title' => 'Dashboard Admin',
            'pernyataans' => PernyataanMagang::latest()->paginate(5),
            // 'pengundurans' => PengunduranDiri::latest()->paginate(5),
            // 'pelanggarans' => PelanggaranPeraturanAkademik::latest()->paginate(5),
            'totalPernyataan' => PernyataanMagang::count(),
            // 'totalPengunduran' => PengunduranDiri::count(),
            // 'totalPelanggaran' => PelanggaranPeraturanAkademik::count()
        ]);
    }
}
