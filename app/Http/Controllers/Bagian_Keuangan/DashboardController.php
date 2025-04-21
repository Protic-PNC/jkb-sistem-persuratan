<?php

namespace App\Http\Controllers\Bagian_Keuangan;

use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Models\PengunduranDiri;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.bagian_keuangan.index', [
            'title' => 'Dashboard Bagian Keuangan',
            'pengundurans' => PengunduranDiri::latest()->paginate(10),
            'totalPengunduran' => PengunduranDiri::count(),
        ]);
    }
}
