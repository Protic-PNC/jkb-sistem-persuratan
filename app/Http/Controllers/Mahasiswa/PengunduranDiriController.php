<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Models\PengunduranDiri;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\PermohonanPengunduranDiriMail;

class PengunduranDiriController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $pengundurans = PengunduranDiri::where('username', $user->username)->latest()->get();

        $totalPengunduranDiri = $pengundurans->count();
        $totalDiproses = $pengundurans->where('status_surat', 'belum selesai')->count() + $pengundurans->where('status_surat', 'diproses')->count();
        $totalDisetujui = $pengundurans->where('status_surat', 'selesai')->count();
        $totalDitolak = $pengundurans->where('status_surat', 'ditolak')->count();

        return view('dashboard.mahasiswa.pengunduran_diri.index', [
            'title' => 'Permohonan Pengunduran Diri',
            'pengundurans' => $pengundurans,
            'totalPengunduranDiri' => $totalPengunduranDiri,
            'totalDiproses' => $totalDiproses,
            'totalDisetujui' => $totalDisetujui,
            'totalDitolak' => $totalDitolak
        ]);
    }

    public function create()
    {
        $kelas = Kelas::all();
        $user = User::where('role_id', 3)->get()->first();
        return view('dashboard.mahasiswa.pengunduran_diri.create', compact('kelas', 'user'), [
            'title' => 'Permohonan Pengunduran Diri',
        ]);
    }

    public function show(PengunduranDiri $pengunduranDiri)
    {
        return view('dashboard.mahasiswa.pengunduran_diri.show', [
            'title' => 'Permohonan Pengunduran Diri',
            'pengundurans' => $pengunduranDiri,
        ]);
    }

    public function edit(PengunduranDiri $pengunduranDiri)
    {
        $kelas = Kelas::all();
        $user = Auth::user();
        return view('dashboard.mahasiswa.pengunduran_diri.edit', compact('kelas', 'user'), [
            'title' => 'Edit',
            'pengundurans' => $pengunduranDiri,
        ]);
    }

    public function store(Request $request)
    {   
        $validatedData = $request->validate([
            'nama_mhs' => 'required|string|max:255',
            'nama_dosen_wali' => 'required|string|max:255',
            'nama_ketua_jurusan' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
            'kelas_id' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'no_telp' => 'required|string|max:255',
            'tglSurat' => 'required|date',
            'alasan' => 'required|string|max:255',
            'ttd_mahasiswa' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ttd_dosen_wali' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ttd_ketua_jurusan' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'file_pdf' => 'nullable|mimes:pdf|max:2048'
        ]);

        if ($request->hasFile('ttd_mahasiswa')) {
            $file = $request->file('ttd_mahasiswa');
            $filename = 'ttd_mahasiswa_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('ttd_mahasiswa', $filename);
            $validatedData['ttd_mahasiswa'] = str_replace('public/', 'storage/', $path);
        }

        if ($request->hasFile('ttd_dosen_wali')) {
            $file = $request->file('ttd_dosen_wali');
            $filename = 'ttd_dosen_wali_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('ttd_dosen_wali', $filename);
            $validatedData['ttd_dosen_wali'] = str_replace('public/', 'storage/', $path);
        }

        if ($request->hasFile('ttd_ketua_jurusan')) {
            $file = $request->file('ttd_ketua_jurusan');
            $filename = 'ttd_ketua_jurusan_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('ttd_ketua_jurusan', $filename);
            $validatedData['ttd_ketua_jurusan'] = str_replace('public/', 'storage/', $path);
        }

        if ($request->hasFile('file_pdf')) {
            $file = $request->file('file_pdf');
            $path = $file->store('surat-pengunduran-diri', 'public');
            $validatedData['file_pdf'] = $path;
        }

        // Always set initial status to diproses, similar to admin controller
        $validatedData['status_surat'] = 'diproses';

        $pengunduranDiri = PengunduranDiri::create($validatedData);
        $user = User::where('username', $request->username)->first();

        if ($user) {
            $message = "Halo {$pengunduranDiri->nama_mhs}, terdapat Surat Permohonan Pengunduran Diri No. Surat: {$pengunduranDiri->noSurat} untuk Anda. Harap segera untuk diproses pada website berikut http://127.0.0.1:8000";
            $no_telp = $user->no_telp;

            $response = Http::withHeaders([
                'Authorization' => 'hYMRNdtcPe83pM1cTb5p',
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

            Mail::to($user->email)->send(new PermohonanPengunduranDiriMail($pengunduranDiri));
            Log::info('Email notification sent successfully upon creation.', [
                'email' => $user->email
            ]);
        }

        return redirect('/dashboard/mahasiswa/pengunduran-diri');
    }

    public function update(Request $request, PengunduranDiri $pengunduranDiri)
    {
        $rules = [
            'nama_mhs' => 'required|string|max:255',
            'nama_dosen_wali' => 'required|string|max:255',
            'nama_ketua_jurusan' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
            'kelas_id' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'no_telp' => 'required|string|max:255',
            'tglSurat' => 'required|date',
            'alasan' => 'required|string|max:255',
            'ttd_mahasiswa' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ttd_dosen_wali' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ttd_ketua_jurusan' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'file_pdf' => 'nullable|mimes:pdf|max:2048'
        ];

        $validatedData = $request->validate($rules);
        
        if ($request->hasFile('ttd_mahasiswa')) {
            if ($pengunduranDiri->ttd_mahasiswa) {
                Storage::disk('public')->delete($pengunduranDiri->ttd_mahasiswa);
            }
            $file = $request->file('ttd_mahasiswa');
            $filename = 'ttd_mahasiswa_' . time() . '.' . $file->getClientOriginalExtension();
            $validatedData['ttd_mahasiswa'] = $file->storeAs('ttd_mahasiswa', $filename);
        }

        if ($request->hasFile('ttd_dosen_wali')) {
            if ($pengunduranDiri->ttd_dosen_wali) {
                Storage::disk('public')->delete($pengunduranDiri->ttd_dosen_wali);
            }
            $file = $request->file('ttd_dosen_wali');
            $filename = 'ttd_dosen_wali_' . time() . '.' . $file->getClientOriginalExtension();
            $validatedData['ttd_dosen_wali'] = $file->storeAs('ttd_dosen_wali', $filename);
        }

        if ($request->hasFile('ttd_ketua_jurusan')) {
            if ($pengunduranDiri->ttd_ketua_jurusan) {
                Storage::disk('public')->delete($pengunduranDiri->ttd_ketua_jurusan);
            }
            $file = $request->file('ttd_ketua_jurusan');
            $filename = 'ttd_ketua_jurusan_' . time() . '.' . $file->getClientOriginalExtension();
            $validatedData['ttd_ketua_jurusan'] = $file->storeAs('ttd_ketua_jurusan', $filename);
        }

        if ($request->hasFile('file_pdf')) {
            if ($pengunduranDiri->file_pdf && Storage::disk('public')->exists($pengunduranDiri->file_pdf)) {
                Storage::disk('public')->delete($pengunduranDiri->file_pdf);
            }
            $file = $request->file('file_pdf');
            $path = $file->store('surat-pengunduran-diri', 'public');
            $validatedData['file_pdf'] = $path;
        }

        $statusSebelumnya = $pengunduranDiri->status_surat;

        if ($statusSebelumnya === 'selesai' || $statusSebelumnya === 'ditolak') {
            $validatedData['status_surat'] = 'diproses';
            if ($statusSebelumnya === 'ditolak') {
                $validatedData['alasan'] = null;
            }
        } else {
            $validatedData['status_surat'] = $statusSebelumnya;
        }

        $pengunduranDiri->update($validatedData);
        $user = User::where('username', $request->username)->first();

        if ($user) {
            $message = "Halo {$pengunduranDiri->nama_mhs}, Surat Permohonan Pengunduran Diri No. Surat: {$pengunduranDiri->noSurat} telah diperbarui.";
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
                Mail::to($user->email)->send(new PermohonanPengunduranDiriMail($pengunduranDiri));
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
        return redirect('/dashboard/mahasiswa/pengunduran-diri');
    }

    public function destroy(PengunduranDiri $pengunduranDiri)
    {
        $pengunduranDiri->delete();

        return redirect('/dashboard/mahasiswa/pengunduran-diri');
    }

    public function cetak(PengunduranDiri $pengunduranDiri)
    {
        $pdf = Pdf::loadview('dashboard.mahasiswa.pengunduran_diri.cetak', [
            'title' => 'Cetak',
            'pengundurans' => $pengunduranDiri,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Surat Permohonan Pengunduran Diri_' . $pengunduranDiri->nama_mhs .'_'. $pengunduranDiri->username .'_' . '.pdf');
    }

    public function uploadForm($id)
    {
        $pengundurans = PengunduranDiri::findOrFail($id);
        return view('dashboard.mahasiswa.pengunduran_diri.upload', [
            'title' => 'Upload Surat Pengunduran Diri',
            'pengundurans' => $pengundurans
        ]);
    }

    public function upload(Request $request, $id)
    {
        if (!$request->hasFile('file_pdf')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada file yang diupload.'
            ]);
        }

        $request->validate([
            'file_pdf' => 'required|mimes:pdf|max:2048',
        ]);

        $pengundurans = PengunduranDiri::findOrFail($id);
        $file = $request->file('file_pdf');

        if ($pengundurans->file_pdf && Storage::disk('public')->exists($pengundurans->file_pdf)) {
            Storage::disk('public')->delete($pengundurans->file_pdf);
        }

        $path = $file->store('surat-pengunduran-diri', 'public');
        $pengundurans->file_pdf = $path;
        if ($pengundurans->status_surat === 'ditolak') {
            $pengundurans->status_surat = 'diproses';
            $pengundurans->alasan = null;
        }

        $pengundurans->save();

        return response()->json([
            'status' => 'success',
            'message' => 'File berhasil diupload.'
        ]);
    }
}
