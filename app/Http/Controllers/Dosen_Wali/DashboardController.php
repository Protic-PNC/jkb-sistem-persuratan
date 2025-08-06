<?php

namespace App\Http\Controllers\Dosen_Wali;

use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Models\PengunduranDiri;
use App\Models\PelanggaranAkademik;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $kelasIds = Kelas::where('username_dosen_wali', $user->username)->pluck('id_kelas');
        $pelanggarans = PelanggaranAkademik::where('nama_pelapor', $user->nama_pemilik)
                                 ->latest()
                                 ->paginate(10);
        $pengundurans = PengunduranDiri::where('nama_dosen_wali', $user->nama_pemilik)
                                 ->latest()
                                 ->paginate(10);
        $pelanggaranAkademikKelas = PelanggaranAkademik::whereIn('kelas_id', $kelasIds)
            ->with('kelas')
            ->latest()
            ->paginate(10);
        $pengunduranDiriKelas = PengunduranDiri::whereIn('kelas_id', $kelasIds)
            ->with('kelas')
            ->latest()
            ->paginate(10);
        $kelas = Kelas::find($kelasIds->first());

        // Get pengunduran diri statistics
        $totalPengunduranDiri = PengunduranDiri::whereIn('kelas_id', $kelasIds)->count();
        $totalDiproses = PengunduranDiri::whereIn('kelas_id', $kelasIds)
            ->whereIn('status_surat', ['belum selesai', 'diproses'])
            ->count();
        $totalDisetujui = PengunduranDiri::whereIn('kelas_id', $kelasIds)
            ->where('status_surat', 'selesai')
            ->count();
        $totalDitolak = PengunduranDiri::whereIn('kelas_id', $kelasIds)
            ->where('status_surat', 'ditolak')
            ->count();

        return view('dashboard.dosen_wali.index', [
            'title' => 'Dashboard Dosen Wali',
            'pelanggaranKelas' => $pelanggaranAkademikKelas,
            'pengunduranKelas' => $pengunduranDiriKelas,
            'pelanggarans' => $pelanggarans,
            'pengundurans' => $pengundurans,
            'totalPelanggaranAkademik' => $pelanggarans->count(),
            'totalPelanggaranAkademikKelas' => $pelanggaranAkademikKelas->count(),
            'totalPengunduranDiri' => $totalPengunduranDiri,
            'totalDiproses' => $totalDiproses,
            'totalDisetujui' => $totalDisetujui,
            'totalDitolak' => $totalDitolak,
            'kelas' => $kelas
        ]);
    }
}
