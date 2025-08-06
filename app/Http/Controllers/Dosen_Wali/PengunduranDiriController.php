<?php

namespace App\Http\Controllers\Dosen_Wali;

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
        $kelasIds = Kelas::where('username_dosen_wali', $user->username)->pluck('id_kelas');
        
        // Only show pengunduran diri for classes managed by this dosen
        $pengundurans = PengunduranDiri::whereIn('kelas_id', $kelasIds)->latest()->get();

        $totalPengunduranDiri = $pengundurans->count();
        $totalDiproses = $pengundurans->whereIn('status_surat', ['belum selesai', 'diproses'])->count();
        $totalDisetujui = $pengundurans->where('status_surat', 'selesai')->count();
        $totalDitolak = $pengundurans->where('status_surat', 'ditolak')->count();

        return view('dashboard.dosen_wali.pengunduran_diri.index', [
            'title' => 'Permohonan Pengunduran Diri',
            'pengundurans' => $pengundurans,
            'totalPengunduranDiri' => $totalPengunduranDiri,
            'totalDiproses' => $totalDiproses,
            'totalDisetujui' => $totalDisetujui,
            'totalDitolak' => $totalDitolak,
        ]);
    }

    public function create()
    {
        $kelas = Kelas::all();
        $user = Auth::user();
        return view('dashboard.dosen_wali.pengunduran_diri.create', compact('kelas', 'user'), [
            'title' => 'Permohonan Pengunduran Diri',
        ]);
    }

    public function show(PengunduranDiri $pengunduranDiri)
    {
        // Check if dosen has access to this document
        $user = Auth::user();
        $kelasIds = Kelas::where('username_dosen_wali', $user->username)->pluck('id_kelas');
        
        if (!in_array($pengunduranDiri->kelas_id, $kelasIds->toArray())) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('dashboard.dosen_wali.pengunduran_diri.show', [
            'title' => 'Permohonan Pengunduran Diri',
            'pengundurans' => $pengunduranDiri,
        ]);
    }

    public function edit(PengunduranDiri $pengunduranDiri)
    {
        // Check if dosen has access to this document
        $user = Auth::user();
        $kelasIds = Kelas::where('username_dosen_wali', $user->username)->pluck('id_kelas');
        
        if (!in_array($pengunduranDiri->kelas_id, $kelasIds->toArray())) {
            abort(403, 'Unauthorized action.');
        }
        
        $kelas = Kelas::all();
        return view('dashboard.dosen_wali.pengunduran_diri.edit', compact('kelas', 'user'), [
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
            'ttd_ketua_jurusan' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        // Check if dosen has access to this class
        $user = Auth::user();
        $kelasIds = Kelas::where('username_dosen_wali', $user->username)->pluck('id_kelas');
        
        if (!in_array($request->kelas_id, $kelasIds->toArray())) {
            abort(403, 'Unauthorized action.');
        }

        if ($request->hasFile('ttd_dosen_wali')) {
            $file = $request->file('ttd_dosen_wali');
            $filename = 'ttd_dosen_wali_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('ttd_dosen_wali', $filename);
            $validatedData['ttd_dosen_wali'] = str_replace('public/', 'storage/', $path);
        }

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

        return redirect('/dashboard/dosen-wali/pengunduran-diri');
    }

    public function update(Request $request, PengunduranDiri $pengunduranDiri)
    {
        // Check if dosen has access to this document
        $user = Auth::user();
        $kelasIds = Kelas::where('username_dosen_wali', $user->username)->pluck('id_kelas');
        
        if (!in_array($pengunduranDiri->kelas_id, $kelasIds->toArray())) {
            abort(403, 'Unauthorized action.');
        }
        
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
            'ttd_ketua_jurusan' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];

        $validatedData = $request->validate($rules);

        if ($request->hasFile('ttd_dosen_wali')) {
            if ($pengunduranDiri->ttd_dosen_wali) {
                Storage::disk('public')->delete($pengunduranDiri->ttd_dosen_wali);
            }
            $file = $request->file('ttd_dosen_wali');
            $filename = 'ttd_dosen_wali_' . time() . '.' . $file->getClientOriginalExtension();
            $validatedData['ttd_dosen_wali'] = $file->storeAs('ttd_dosen_wali', $filename);
        }

        $statusSebelumnya = $pengunduranDiri->status_surat;

        // If document was approved or rejected before, reset to diproses
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
        return redirect('/dashboard/dosen-wali/pengunduran-diri');
    }

    public function destroy(PengunduranDiri $pengunduranDiri)
    {
        // Check if dosen has access to this document
        $user = Auth::user();
        $kelasIds = Kelas::where('username_dosen_wali', $user->username)->pluck('id_kelas');
        
        if (!in_array($pengunduranDiri->kelas_id, $kelasIds->toArray())) {
            abort(403, 'Unauthorized action.');
        }
        
        $pengunduranDiri->delete();

        return redirect('/dashboard/dosen-wali/pengunduran-diri');
    }

    public function cetak(PengunduranDiri $pengunduranDiri)
    {
        // Check if dosen has access to this document
        $user = Auth::user();
        $kelasIds = Kelas::where('username_dosen_wali', $user->username)->pluck('id_kelas');
        
        if (!in_array($pengunduranDiri->kelas_id, $kelasIds->toArray())) {
            abort(403, 'Unauthorized action.');
        }
        
        $pdf = Pdf::loadview('dashboard.dosen_wali.pengunduran_diri.cetak', [
            'title' => 'Cetak',
            'pengundurans' => $pengunduranDiri,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Surat Permohonan Pengunduran Diri_' . $pengunduranDiri->nama_mhs .'_'. $pengunduranDiri->username .'_' . '.pdf');
    }

    public function setujui($id)
    {
        $pengundurans = PengunduranDiri::findOrFail($id);
        
        // Check if dosen has access to this document
        $user = Auth::user();
        $kelasIds = Kelas::where('username_dosen_wali', $user->username)->pluck('id_kelas');
        
        if (!in_array($pengundurans->kelas_id, $kelasIds->toArray())) {
            abort(403, 'Unauthorized action.');
        }
        
        $pengundurans->status_surat = 'selesai';
        $pengundurans->alasan = null;
        $pengundurans->save();
        $user = User::where('username', $pengundurans->username)->first();

        if ($user) {
            $message = "Halo {$pengundurans->nama_mhs}, Surat Permohonan Pengunduran Diri No. Surat: {$pengundurans->noSurat} telah disetujui.";
            $no_telp = $user->no_telp;

            $response = Http::withHeaders([
                'Authorization' => 'GExfSpLCzErZt59W5DCZ',
            ])->post('https://api.fonnte.com/send', [
                'target' => $no_telp,
                'message' => $message,
                'countryCode' => '62',
            ]);

            Mail::to($user->email)->send(new PermohonanPengunduranDiriMail($pengundurans));
        }

        return response()->json(['message' => 'Surat disetujui.']);
    }

    public function tolak(Request $request, $id)
    {
        $request->validate(['alasan' => 'required|string']);
        $pengundurans = PengunduranDiri::findOrFail($id);
        
        // Check if dosen has access to this document
        $user = Auth::user();
        $kelasIds = Kelas::where('username_dosen_wali', $user->username)->pluck('id_kelas');
        
        if (!in_array($pengundurans->kelas_id, $kelasIds->toArray())) {
            abort(403, 'Unauthorized action.');
        }
        
        $pengundurans->status_surat = 'ditolak';
        $pengundurans->alasan = $request->alasan;
        $pengundurans->save();
        $user = User::where('username', $pengundurans->username)->first();

        if ($user) {
            $message = "Halo {$pengundurans->nama_mhs}, Surat Permohonan Pengunduran Diri No. Surat: {$pengundurans->noSurat} telah ditolak. Alasan: {$pengundurans->alasan}";
            $no_telp = $user->no_telp;

            $response = Http::withHeaders([
                'Authorization' => 'GExfSpLCzErZt59W5DCZ',
            ])->post('https://api.fonnte.com/send', [
                'target' => $no_telp,
                'message' => $message,
                'countryCode' => '62',
            ]);

            Mail::to($user->email)->send(new PermohonanPengunduranDiriMail($pengundurans));
        }

        return response()->json(['message' => 'Surat ditolak.']);
    }

    public function updateAlasan(Request $request, $noSurat)
    {
        $request->validate([
            'alasan' => 'nullable|string',
        ]);

        $pengundurans = PengunduranDiri::where('noSurat', $noSurat)->firstOrFail();
        
        // Check if dosen has access to this document
        $user = Auth::user();
        $kelasIds = Kelas::where('username_dosen_wali', $user->username)->pluck('id_kelas');
        
        if (!in_array($pengundurans->kelas_id, $kelasIds->toArray())) {
            abort(403, 'Unauthorized action.');
        }
        
        $pengundurans->alasan = $request->alasan;
        $pengundurans->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Alasan penolakan berhasil diperbarui.'
        ]);
    }
}
