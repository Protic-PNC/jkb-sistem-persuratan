<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PelanggaranAkademik;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\StatusPelanggaranAkademikChangedMail;

class PelanggaranAkademikController extends Controller
{
    public function index(Request $request)
    {
        $pelanggarans = PelanggaranAkademik::all();
        $totalPelanggaran = PelanggaranAkademik::count();
        $totalDiproses = PelanggaranAkademik::whereNull('status_surat')->orWhere('status_surat', 'diproses')->count();
        $totalDisetujui = PelanggaranAkademik::where('status_surat', 'approved')->count();
        $totalDitolak = PelanggaranAkademik::where('status_surat', 'rejected')->count();

        if ($request->ajax()) {
            return view('dashboard.admin.pelanggaran_akademiks.table', compact('pelanggarans'))->render();
        }

        return view('dashboard.admin.pelanggaran_akademiks.index', [
            'title' => 'Pelanggaran Akademik',
            'pelanggarans' => $pelanggarans,
            'totalPelanggaran' => $totalPelanggaran,
            'totalDiproses' => $totalDiproses,
            'totalDisetujui' => $totalDisetujui,
            'totalDitolak' => $totalDitolak,
        ]);
    }

    public function create()
    {
        $kelas = Kelas::all();
        $user = User::where('role_id', 3)->get()->first();
        return view('dashboard.admin.pelanggaran_akademiks.create', compact('kelas', 'user'), [
            'title' => 'Pelanggaran Akademik',
        ]);
    }

    public function store(Request $request)
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
            'ttd_mahasiswa' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ttd_pelapor' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ttd_dosen_wali' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ttd_ketua_jurusan' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $ttdFields = ['ttd_mahasiswa', 'ttd_pelapor', 'ttd_dosen_wali', 'ttd_ketua_jurusan'];
        foreach ($ttdFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = $field . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($field, $filename, 'public');
                $validatedData[$field] = $path;
            }
        }

        $validatedData['status_surat'] = 'diproses';
        $existingRecord = PelanggaranAkademik::where('nama_mhs', $request->nama_mhs)->first();
        $validatedData['jumlah_peringatan'] = $existingRecord ? $existingRecord->jumlah_peringatan + 1 : 1;

        PelanggaranAkademik::create($validatedData);

        return redirect('/dashboard/admin/pelanggaran-akademik');
    }


    public function show(PelanggaranAkademik $pelanggaranAkademik)
    {
        return view('dashboard.admin.pelanggaran_akademiks.show', [
            'title' => 'Detail Pelanggaran',
            'pelanggarans' => $pelanggaranAkademik,
        ]);
    }

    public function edit(PelanggaranAkademik $pelanggaranAkademik)
    {
        $kelas = Kelas::all();
        $user = Auth::user();
        return view('dashboard.admin.pelanggaran_akademiks.edit', compact('kelas', 'user'), [
            'title' => 'Edit',
            'pelanggarans' => $pelanggaranAkademik,
        ]);
    }

    public function update(Request $request, PelanggaranAkademik $pelanggaranAkademik)
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
            'ttd_mahasiswa' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ttd_pelapor' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ttd_dosen_wali' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ttd_ketua_jurusan' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $ttdFields = ['ttd_mahasiswa', 'ttd_pelapor', 'ttd_dosen_wali', 'ttd_ketua_jurusan'];
        foreach ($ttdFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = $field . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($field, $filename, 'public');
                $validatedData[$field] = $path;
            } else {
                $validatedData[$field] = $pelanggaranAkademik->$field;
            }
        }

        $pelanggaranAkademik->update($validatedData);

        return redirect('/dashboard/admin/pelanggaran-akademik');
    }


    public function destroy(PelanggaranAkademik $pelanggaranAkademik)
    {
        $pelanggaranAkademik->delete();

        return redirect('/dashboard/admin/pelanggaran-akademik');
    }

    public function cetak(PelanggaranAkademik $pelanggaranAkademik)
    {
        $pdf = Pdf::loadview('dashboard.admin.pelanggaran_akademiks.cetak', [
            'title' => 'Cetak',
            'pelanggarans' => $pelanggaranAkademik,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Surat Peringatan karena Pelanggaran Peraturan Akademik_' . $pelanggaranAkademik->nama_mhs . '_' . $pelanggaranAkademik->username . '_' . '.pdf');
    }

    public function setujui($id)
    {
        $pelanggaran = PelanggaranAkademik::findOrFail($id);
        $pelanggaran->status_surat = 'approved';
        $pelanggaran->alasan = null;
        $pelanggaran->save();

        $user = User::where('username', $pelanggaran->username)->first();
        Mail::to($user->email)->send(new StatusPelanggaranAkademikChangedMail($pelanggaran, 'approved'));

        return response()->json(['message' => 'Pelanggaran disetujui.']);
    }

    public function tolak(Request $request, $id)
    {
        $request->validate(['alasan' => 'required|string']);
        $pelanggaran = PelanggaranAkademik::findOrFail($id);
        $pelanggaran->status_surat = 'rejected';
        $pelanggaran->alasan = $request->alasan;
        $pelanggaran->save();

        $user = User::where('username', $pelanggaran->username)->first();
        Mail::to($user->email)->send(new StatusPelanggaranAkademikChangedMail($pelanggaran, 'rejected'));

        return response()->json(['message' => 'Pelanggaran ditolak.']);
    }

    public function reminderTandaTangan($noSurat)
    {
        $pelanggaran = PelanggaranAkademik::where('noSurat', $noSurat)->firstOrFail();

        if ($pelanggaran->status_surat !== 'diproses') {
            return response()->json(['message' => 'Surat tidak dalam status diproses.']);
        }

        $emails = [];

        if (is_null($pelanggaran->ttd_dosen_wali)) {
            $emailDosenWali = User::where('role_id', 4)
                ->where('nama_pemilik', $pelanggaran->nama_dosen_wali)
                ->first()?->email;

            if ($emailDosenWali) {
                $emails[] = $emailDosenWali;
            }
        }

        if (is_null($pelanggaran->ttd_ketua_jurusan)) {
            $emailKetuaJurusan = User::where('role_id', 3)
                ->where('nama_pemilik', $pelanggaran->nama_ketua_jurusan)
                ->first()?->email;

            if ($emailKetuaJurusan) {
                $emails[] = $emailKetuaJurusan;
            }
        }

        if (!empty($emails)) {
            Mail::to($emails)->send(new StatusPelanggaranAkademikChangedMail($pelanggaran, 'reminder_ttd_pelanggaran'));

            $pesan = 'Reminder tanda tangan telah dikirim ke ';
            if (count($emails) == 2) {
                $pesan .= 'dosen wali dan ketua jurusan.';
            } elseif (isset($emailDosenWali)) {
                $pesan .= 'dosen wali.';
            } else {
                $pesan .= 'ketua jurusan.';
            }

            return response()->json(['message' => $pesan]);
        }

        return response()->json(['message' => 'Tidak ada penerima yang valid untuk pengingat tanda tangan.']);
    }
}
