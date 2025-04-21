<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\PengunduranDiri;
use App\Models\PernyataanMagang;
use App\Models\PelanggaranAkademik;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.admin.index', [
            'title' => 'Dashboard Admin',
            'pernyataans' => PernyataanMagang::latest()->paginate(10),
            'pengundurans' => PengunduranDiri::latest()->paginate(10),
            'pelanggarans' => PelanggaranAkademik::latest()->paginate(10),
            'totalPernyataan' => PernyataanMagang::count(),
            'totalPengunduran' => PengunduranDiri::count(),
            'totalPelanggaran' => PelanggaranAkademik::count()
        ]);
    }
}
