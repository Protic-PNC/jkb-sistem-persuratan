<?php

namespace App\Http\Controllers\Ketua_Jurusan;

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
        $pelanggaranSemuaKelas = PelanggaranAkademik::all();
        $pelanggaranKajurs = PelanggaranAkademik::where('nama_pelapor', $user->nama_pemilik)->latest()->get();
        $totalPelanggaranAkademikSemuaKelas = $pelanggaranSemuaKelas->count();
        $totalPelanggaranAkademikKajur = $pelanggaranKajurs->count();

        return view('dashboard.ketua_jurusan.pelanggaran_akademiks.index', [
            'title' => 'Pelanggaran Akademik',
            'pelanggaranSemuaKelas' => $pelanggaranSemuaKelas,
            'pelanggaranKajurs' => $pelanggaranKajurs,
            'totalPelanggaranAkademikSemuaKelas' => $totalPelanggaranAkademikSemuaKelas,
            'totalPelanggaranAkademikKajur' => $totalPelanggaranAkademikKajur
        ]);
    }

    public function create()
    {
        $kelas = Kelas::all();
        $user = User::where('role_id', 3)->get()->first();
        return view('dashboard.ketua_jurusan.pelanggaran_akademiks.create', compact('kelas', 'user'), [
            'title' => 'Pelanggaran Akademik',
        ]);
    }

    public function show(PelanggaranAkademik $pelanggaranAkademik)
    {
        return view('dashboard.ketua_jurusan.pelanggaran_akademiks.show', [
            'title' => 'Pelanggaran Akademik',
            'pelanggarans' => $pelanggaranAkademik,
        ]);
    }

    public function edit(PelanggaranAkademik $pelanggaranAkademik)
    {
        $kelas = Kelas::all();
        $user = Auth::user();
        return view('dashboard.ketua_jurusan.pelanggaran_akademiks.edit', compact('kelas', 'user'), [
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

        $existingRecord = PelanggaranAkademik::where('nama_mhs', $request->nama_mhs)->first();
        
        if ($existingRecord) {
            $validatedData['jumlah_peringatan'] = $existingRecord->jumlah_peringatan + 1;
        } else {
            $validatedData['jumlah_peringatan'] = 1;
        }

        $validatedData['status_surat'] =
        ($pelanggaranAkademik->ttd_mahasiswa) && 
        ($pelanggaranAkademik->ttd_pelapor || $request->hasFile('ttd_pelapor')) && 
        ($pelanggaranAkademik->ttd_ketua_jurusan || $request->hasFile('ttd_ketua_jurusan')) && 
        ($pelanggaranAkademik->ttd_ketua_jurusan) ? 'selesai' : 'belum selesai';

        $pelanggaranAkademik = PelanggaranAkademik::create($validatedData);
        $user = User::where('username', $request->username)->first();

        if ($pelanggaranAkademik->status_surat == 'belum selesai' && $user) {
            $message = "Halo {$pelanggaranAkademik->nama_mhs}, terdapat Surat Peringatan karena Pelanggaran Akademik No. Surat: {$pelanggaranAkademik->noSurat} untuk Anda. Harap segera untuk diproses pada website berikut http://127.0.0.1:8000";
            $no_telp = $user->no_telp;

            $response = Http::withHeaders([
                'Authorization' => 'GExfSpLCzErZt59W5DCZ',
            ])->post('https://api.fonnte.com/send', [
                'target' => $no_telp,
                'message' => $message,
                'countryCode' => '62',
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp notification sent successfully upon creation.', [
                    'no_telp' => $no_telp,
                    'response' => $response->body(),
                ]);
            } else {
                Log::error('Failed to send WhatsApp notification upon creation.', [
                    'no_telp' => $no_telp,
                    'response' => $response->body(),
                ]);
            }

            Mail::to($user->email)->send(new PeringatanPelanggaranAkademikMail($pelanggaranAkademik));
            Log::info('Email notification sent successfully upon creation.', [
                'email' => $user->email
            ]);
        }
        
        else if ($pelanggaranAkademik->status_surat == 'selesai' && $user) {
            
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

        return redirect('/dashboard/ketua-jurusan/pelanggaran-akademik');
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
            'ttd_ketua_jurusan' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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

        if ($request->hasFile('ttd_ketua_jurusan')) {
            if ($pelanggaranAkademik->ttd_ketua_jurusan) {
                Storage::disk('public')->delete($pelanggaranAkademik->ttd_ketua_jurusan);
            }
            $file = $request->file('ttd_ketua_jurusan');
            $filename = 'ttd_ketua_jurusan_' . time() . '.' . $file->getClientOriginalExtension();
            $validatedData['ttd_ketua_jurusan'] = $file->storeAs('ttd_ketua_jurusan', $filename);
        }

        if ($request->nama_mhs == $pelanggaranAkademik->nama_mhs) {
            $validatedData['jumlah_peringatan'] = $pelanggaranAkademik->jumlah_peringatan;
        } else {
            $validatedData['jumlah_peringatan'] = $pelanggaranAkademik->jumlah_peringatan + 1;
        }

        $statusSebelumnya = $pelanggaranAkademik->status_surat;

        $validatedData['status_surat'] = (
            ($pelanggaranAkademik->ttd_mahasiswa) && 
            ($pelanggaranAkademik->ttd_pelapor || $request->hasFile('ttd_pelapor')) && 
            ($pelanggaranAkademik->ttd_ketua_jurusan || $request->hasFile('ttd_ketua_jurusan')) && 
            ($pelanggaranAkademik->ttd_dosen_wali)
        ) ? 'selesai' : $statusSebelumnya;

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
        return redirect('/dashboard/ketua-jurusan/pelanggaran-akademik');
    }

    public function destroy(PelanggaranAkademik $pelanggaranAkademik)
    {
        $pelanggaranAkademik->delete();

        return redirect('/dashboard/ketua-jurusan/pelanggaran-akademik');
    }

    public function cetak(PelanggaranAkademik $pelanggaranAkademik)
    {
        $pdf = Pdf::loadview('dashboard.ketua_jurusan.pelanggaran_akademiks.cetak', [
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
            // Kirim email penolakan
            $targetUser = \App\Models\User::where('username', $pelanggaran->username)->first();
            if ($targetUser) {
                \Mail::to($targetUser->email)->send(new \App\Mail\StatusPelanggaranAkademikChangedMail($pelanggaran, 'rejected'));
            }
        } else {
            $alasanLama = $pelanggaran->alasan ? $pelanggaran->alasan."\n" : '';
            $alasanUpdate = $alasanLama.'Tambahan alasan dari '.$role.': '.$alasanBaru;
            $pelanggaran->alasan = $alasanUpdate;
            $pelanggaran->save();
            // Kirim email update alasan
            $targetUser = \App\Models\User::where('username', $pelanggaran->username)->first();
            if ($targetUser) {
                \Mail::to($targetUser->email)->send(new \App\Mail\StatusPelanggaranAkademikChangedMail($pelanggaran, 'rejected_update'));
            }
        }
        return response()->json(['message' => 'Pelanggaran ditolak/alasan diperbarui.']);
    }
}
