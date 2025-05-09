<?php

namespace App\Http\Controllers\Dosen_Wali;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PelanggaranAkademik;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\PeringatanPelanggaranAkademikMail;
use App\Mail\StatusPelanggaranAkademikChangedMail;


class PelanggaranAkademikController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        // Data kelas perwalian
        $pelanggaranSemuaKelasDosen = PelanggaranAkademik::where('nama_dosen_wali', $user->nama_pemilik)->latest()->get();
        // Data pelanggaran yang dilaporkan dosen sendiri
        $pelanggaranDosens = PelanggaranAkademik::where('nama_pelapor', $user->username)->latest()->get();

        // Total untuk masing-masing
        $totalPelanggaranAkademikKelas = $pelanggaranSemuaKelasDosen->count();
        $totalPelanggaranAkademikDosen = $pelanggaranDosens->count();

        // Gabungan untuk rekap
        $rekap = $pelanggaranSemuaKelasDosen->merge($pelanggaranDosens);
        $totalPelanggaranAkademik = $rekap->count();
        $totalDisetujui = $rekap->where('status_surat', 'approved')->count();
        $totalDitolak = $rekap->where('status_surat', 'rejected')->count();
        $totalDiproses = $rekap->whereNotIn('status_surat', ['approved', 'rejected'])->count();

        $kelasIds = Kelas::where('username_dosen_wali', $user->username)->pluck('id_kelas');
        $kelas = Kelas::find($kelasIds->first());

        return view('dashboard.dosen_wali.pelanggaran_akademiks.index', [
            'title' => 'Pelanggaran Akademik',
            'pelanggaranSemuaKelasDosen' => $pelanggaranSemuaKelasDosen,
            'pelanggaranDosens' => $pelanggaranDosens,
            'totalPelanggaranAkademikKelas' => $totalPelanggaranAkademikKelas,
            'totalPelanggaranAkademikDosen' => $totalPelanggaranAkademikDosen,
            'totalPelanggaranAkademik' => $totalPelanggaranAkademik,
            'totalDisetujui' => $totalDisetujui,
            'totalDitolak' => $totalDitolak,
            'totalDiproses' => $totalDiproses,
            'kelas' => $kelas,
        ]);
    }

    public function create()
    {
        $kelas = Kelas::all();
        $user = User::where('role_id', 3)->get()->first();
        return view('dashboard.dosen_wali.pelanggaran_akademiks.create', compact('kelas', 'user'), [
            'title' => 'Pelanggaran Akademik',
        ]);
    }

    public function show(PelanggaranAkademik $pelanggaranAkademik)
    {
        return view('dashboard.dosen_wali.pelanggaran_akademiks.show', [
            'title' => 'Pelanggaran Akademik',
            'pelanggarans' => $pelanggaranAkademik,
        ]);
    }

    public function edit(PelanggaranAkademik $pelanggaranAkademik)
    {
        $kelas = Kelas::all();
        $user = Auth::user();
        return view('dashboard.dosen_wali.pelanggaran_akademiks.edit', compact('kelas', 'user'), [
            'title' => 'Edit',
            'pelanggarans' => $pelanggaranAkademik,
        ]);
    }

    public function store(Request $request, PelanggaranAkademik $pelanggaranAkademik)
{   
    $validatedData = $request->validate([
        'nama_mhs' => 'required|string|max:255',
        'nama_pelapor' => 'required|string|max:255',
        'nama_dosen_wali' => 'required|string|max:255',
        'nama_ketua_jurusan' => 'required|string|max:255',
        'username' => 'required|string|max:255',
        'semester' => 'required|string|max:255',
        'kelas_id' => 'required|string|max:255',
        'peringatan' => 'required|string|in:lisan,tertulis',
        'hari' => 'required|string|max:255',
        'tglSurat' => 'required|date',
        'pasal' => 'required|string|max:255',
        'isi_pasal' => 'required|string|max:255',
        'ttd_pelapor' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048'
    ]);

    if ($request->hasFile('ttd_pelapor')) {
        $file = $request->file('ttd_pelapor');
        $filename = 'ttd_pelapor_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('ttd_pelapor', $filename);
        $validatedData['ttd_pelapor'] = str_replace('public/', 'storage/', $path);
    }

    $existingRecord = $pelanggaranAkademik->where('nama_mhs', $request->nama_mhs)->first();
    $validatedData['jumlah_peringatan'] = $existingRecord ? $existingRecord->jumlah_peringatan + 1 : 1;
    $validatedData['status_surat'] = 'belum selesai';

    PelanggaranAkademik::create($validatedData);

    return redirect('/dashboard/dosen-wali/pelanggaran-akademik');
}

public function update(Request $request, PelanggaranAkademik $pelanggaranAkademik)
{
    $rules = [
        'nama_mhs' => 'required|string|max:255',
        'nama_pelapor' => 'required|string|max:255',
        'nama_dosen_wali' => 'required|string|max:255',
        'nama_ketua_jurusan' => 'required|string|max:255',
        'username' => 'required|string|max:255',
        'semester' => 'required|string|max:255',
        'kelas_id' => 'required|string|max:255',
        'peringatan' => 'required|string|in:lisan,tertulis',
        'hari' => 'required|string|max:255',
        'tglSurat' => 'required|date',
        'pasal' => 'required|string|max:255',
        'isi_pasal' => 'required|string|max:255',
        'ttd_pelapor' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'ttd_dosen_wali' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ];

    $validatedData = $request->validate($rules);

    if ($request->hasFile('ttd_pelapor')) {
        if ($pelanggaranAkademik->ttd_pelapor) {
            Storage::disk('public')->delete($pelanggaranAkademik->ttd_pelapor);
        }
        $file = $request->file('ttd_pelapor');
        $filename = 'ttd_pelapor_' . time() . '.' . $file->getClientOriginalExtension();
        $validatedData['ttd_pelapor'] = $file->storeAs('ttd_pelapor', $filename);
    }

    if ($request->hasFile('ttd_dosen_wali')) {
        if ($pelanggaranAkademik->ttd_dosen_wali) {
            Storage::disk('public')->delete($pelanggaranAkademik->ttd_dosen_wali);
        }
        $file = $request->file('ttd_dosen_wali');
        $filename = 'ttd_dosen_wali_' . time() . '.' . $file->getClientOriginalExtension();
        $validatedData['ttd_dosen_wali'] = $file->storeAs('ttd_dosen_wali', $filename);
    }

    $validatedData['jumlah_peringatan'] = $request->nama_mhs === $pelanggaranAkademik->nama_mhs 
        ? $pelanggaranAkademik->jumlah_peringatan 
        : $pelanggaranAkademik->jumlah_peringatan + 1;

    $pelanggaranAkademik->update($validatedData);

    return redirect('/dashboard/dosen-wali/pelanggaran-akademik');
}

    public function destroy(PelanggaranAkademik $pelanggaranAkademik)
    {
        $pelanggaranAkademik->delete();

        return redirect('/dashboard/dosen-wali/pelanggaran-akademik');
    }

    public function cetak(PelanggaranAkademik $pelanggaranAkademik)
    {
        $pdf = Pdf::loadview('dashboard.dosen_wali.pelanggaran_akademiks.cetak', [
            'title' => 'Cetak',
            'pelanggarans' => $pelanggaranAkademik,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Surat Peringatan karena Pelanggaran Peraturan Akademik_' . $pelanggaranAkademik->nama_mhs .'_'. $pelanggaranAkademik->username .'_' . '.pdf');
    }

    public function tolak(Request $request, $id)
    {
        $request->validate(['alasan' => 'required|string']);
        $pelanggaran = PelanggaranAkademik::findOrFail($id);
        $user = Auth::user();
        $role = $user->role->nama_role ?? 'User';
        $alasanBaru = $request->alasan;

        if ($pelanggaran->status_surat !== 'rejected') {
            $pelanggaran->status_surat = 'rejected';
            $pelanggaran->alasan = $alasanBaru;
            $pelanggaran->save();
            $targetUser = User::where('username', $pelanggaran->username)->first();
            if ($targetUser) {
                Mail::to($targetUser->email)->send(new StatusPelanggaranAkademikChangedMail($pelanggaran, 'rejected'));
            }
        } else {
            $alasanLama = $pelanggaran->alasan ? $pelanggaran->alasan."\n" : '';
            $alasanUpdate = $alasanLama.'Tambahan alasan dari '.$role.': '.$alasanBaru;
            $pelanggaran->alasan = $alasanUpdate;
            $pelanggaran->save();
            $targetUser = User::where('username', $pelanggaran->username)->first();
            if ($targetUser) {
                Mail::to($targetUser->email)->send(new StatusPelanggaranAkademikChangedMail($pelanggaran, 'rejected_update'));
            }
        }
        return response()->json(['message' => 'Pelanggaran ditolak/alasan diperbarui.']);
    }
}
