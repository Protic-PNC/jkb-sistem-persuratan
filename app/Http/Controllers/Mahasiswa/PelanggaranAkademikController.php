<?php

namespace App\Http\Controllers\Mahasiswa;

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

class PelanggaranAkademikController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $pelanggarans = PelanggaranAkademik::where('username', $user->username)->latest()->get();
        $totalPelanggaranAkademik = $pelanggarans->count();
        $totalDisetujui = $pelanggarans->where('status_surat', 'approved')->count();
        $totalDitolak = $pelanggarans->where('status_surat', 'rejected')->count();
        $totalDiproses = $pelanggarans->whereNotIn('status_surat', ['approved', 'rejected'])->count();

        return view('dashboard.mahasiswa.pelanggaran_akademiks.index', [
            'title' => 'Pelanggaran Akademik',
            'pelanggarans' => $pelanggarans,
            'totalPelanggaranAkademik' => $totalPelanggaranAkademik,
            'totalDiproses' => $totalDiproses,
            'totalDisetujui' => $totalDisetujui,
            'totalDitolak' => $totalDitolak,
        ]);
    }

    public function show(PelanggaranAkademik $pelanggaranAkademik)
    {
        return view('dashboard.mahasiswa.pelanggaran_akademiks.show', [
            'title' => 'Pelanggaran Akademik',
            'pelanggarans' => $pelanggaranAkademik,
        ]);
    }

    public function edit(PelanggaranAkademik $pelanggaranAkademik)
    {
        $kelas = Kelas::all();
        $user = Auth::user();
        return view('dashboard.mahasiswa.pelanggaran_akademiks.edit', compact('kelas', 'user'), [
            'title' => 'Edit',
            'pelanggarans' => $pelanggaranAkademik,
        ]);
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
        'ttd_mahasiswa' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
    ];

    $validatedData = $request->validate($rules);
    if ($request->hasFile('ttd_mahasiswa')) {
        if ($pelanggaranAkademik->ttd_mahasiswa) {
            Storage::disk('public')->delete($pelanggaranAkademik->ttd_mahasiswa);
        }
        $file = $request->file('ttd_mahasiswa');
        $filename = 'ttd_mahasiswa_' . time() . '.' . $file->getClientOriginalExtension();
        $validatedData['ttd_mahasiswa'] = $file->storeAs('ttd_mahasiswa', $filename);
    }

    if ($request->nama_mhs == $pelanggaranAkademik->nama_mhs) {
        $validatedData['jumlah_peringatan'] = $pelanggaranAkademik->jumlah_peringatan;
    } else {
        $validatedData['jumlah_peringatan'] = $pelanggaranAkademik->jumlah_peringatan + 1;
    }

    $statusSebelumnya = $pelanggaranAkademik->status_surat;

$validatedData['status_surat'] = (
    ($pelanggaranAkademik->ttd_mahasiswa || $request->hasFile('ttd_mahasiswa')) && 
    ($pelanggaranAkademik->ttd_pelapor) && 
    ($pelanggaranAkademik->ttd_dosen_wali) && 
    ($pelanggaranAkademik->ttd_ketua_jurusan)) ? 'selesai' : $statusSebelumnya;

$pelanggaranAkademik->update($validatedData);
$user = User::where('username', $request->username)->first();

if ($pelanggaranAkademik->status_surat == 'selesai' && $statusSebelumnya != 'selesai' && $user) {
    
    $message = "Halo {$pelanggaranAkademik->nama_mhs}, Surat Peringatan karena Pelanggaran Peraturan Akademik dengan No. Surat: {$pelanggaranAkademik->noSurat} telah selesai.";
    $no_telp = $user->no_telp;
        
        $response = Http::withHeaders([
            'Authorization' => 'GExfSpLCzErZt59W5DCZ',
        ])->post('https://api.fonnte.com/send', [
            'target' => $no_telp,
            'message' => $message,
            'countryCode' => '62',
        ]);

        if ($response->successful()) {
            Log::info('WhatsApp message sent successfully.', [
                'no_telp' => $no_telp,
                'response' => $response->body(),
            ]);
            Mail::to($user->email)->send(new PeringatanPelanggaranAkademikMail($pelanggaranAkademik));
            Log::info('Email sent successfully.', [
                'email' => $user->email
            ]);
        } else {
            Log::error('Failed to send WhatsApp message.', [
                'no_telp' => $no_telp,
                'response' => $response->body(),
            ]);
        }
    }
    
    return redirect('/dashboard/mahasiswa/pelanggaran-akademik');
}


    public function destroy(PelanggaranAkademik $pelanggaranAkademik)
    {
        $pelanggaranAkademik->delete();

        return redirect('/dashboard/mahasiswa/pelanggaran-akademik');
    }

    public function cetak(PelanggaranAkademik $pelanggaranAkademik)
    {
        $pdf = Pdf::loadview('dashboard.mahasiswa.pelanggaran_akademiks.cetak', [
            'title' => 'Cetak',
            'pelanggarans' => $pelanggaranAkademik,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Surat Peringatan karena Pelanggaran Peraturan Akademik_' . $pelanggaranAkademik->nama_mhs .'_'. $pelanggaranAkademik->username .'_' . '.pdf');
    }
}
