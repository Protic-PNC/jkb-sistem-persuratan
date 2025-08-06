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

        // Get pengunduran diri statistics
        $totalPengunduranDiri = $pengunduranDiriSemuaKelas->count();
        $totalDiproses = $pengunduranDiriSemuaKelas->whereIn('status_surat', ['belum selesai', 'diproses'])->count();
        $totalDisetujui = $pengunduranDiriSemuaKelas->where('status_surat', 'selesai')->count();
        $totalDitolak = $pengunduranDiriSemuaKelas->where('status_surat', 'ditolak')->count();

        return view('dashboard.ketua_jurusan.index', [
            'title' => 'Dashboard Ketua Jurusan',
            'pelanggaranKelas' => $pelanggaranAkademikSemuaKelas,
            'pengundurans' => $pengunduranDiriKelas,
            'pelanggarans' => $pelanggaranKajurs,
            'totalPelanggaranAkademikKajur' => $pelanggaranKajurs->count(),
            'totalPelanggaranAkademikSemuaKelas' => $pelanggaranAkademikSemuaKelas->count(),
            'totalPengunduranDiri' => $totalPengunduranDiri,
            'totalDiproses' => $totalDiproses,
            'totalDisetujui' => $totalDisetujui,
            'totalDitolak' => $totalDitolak
        ]);
    }
}
