<?php

namespace App\Http\Controllers\Bagian_Keuangan;

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
    $pengundurans = PengunduranDiri::all();

    $totalPengunduranDiri = $pengundurans->count();
    return view('dashboard.bagian_keuangan.pengunduran_diri.index', [
        'title' => 'Permohonan Pengunduran Diri',
        'pengundurans' => $pengundurans,
        'totalPengunduranDiri' => $totalPengunduranDiri,
    ]);
    }

    public function create()
    {
        $kelas = Kelas::all();
        $user = User::where('role_id', 3)->get()->first();
        return view('dashboard.bagian_keuangan.pengunduran_diri.create', compact('kelas', 'user'), [
            'title' => 'Permohonan Pengunduran Diri',
        ]);
    }

    public function show(PengunduranDiri $pengunduranDiri)
    {
        return view('dashboard.bagian_keuangan.pengunduran_diri.show', [
            'title' => 'Permohonan Pengunduran Diri',
            'pengundurans' => $pengunduranDiri,
        ]);
    }

    public function edit(PengunduranDiri $pengunduranDiri)
    {
        $kelas = Kelas::all();
        $user = Auth::user();
        return view('dashboard.bagian_keuangan.pengunduran_diri.edit', compact('kelas', 'user'), [
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
        'ttd_bagian_keuangan' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
    ]);

    if ($request->hasFile('ttd_bagian_keuangan')) {
        $file = $request->file('ttd_bagian_keuangan');
        $filename = 'ttd_bagian_keuangan_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('ttd_bagian_keuangan', $filename);
        $validatedData['ttd_bagian_keuangan'] = str_replace('public/', 'storage/', $path);
    }

    $validatedData['status_surat'] = ($request->hasFile('ttd_mahasiswa') && 
    $request->hasFile('ttd_dosen_wali') && 
    $request->hasFile('ttd_bagian_keuangan') && 
    $request->hasFile('ttd_ketua_jurusan')) ? 'selesai' : 'belum selesai';

    $pengunduranDiri = PengunduranDiri::create($validatedData);
    $user = User::where('username', $request->username)->first();

    if ($pengunduranDiri->status_surat == 'belum selesai' && $user) {
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
    
    else if ($pengunduranDiri->status_surat == 'selesai' && $user) {
        
        $message = "Halo {$pengunduranDiri->nama_mhs}, Surat Permohonan Pengunduran Diri dengan No. Surat: {$pengunduranDiri->noSurat} telah selesai.";
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

    return redirect('/dashboard/bagian-keuangan/pengunduran-diri');
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
        'ttd_bagian_keuangan' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
    ];

    $validatedData = $request->validate($rules);
    if ($request->hasFile('ttd_bagian_keuangan')) {
        if ($pengunduranDiri->ttd_bagian_keuangan) {
            Storage::disk('public')->delete($pengunduranDiri->ttd_bagian_keuangan);
        }
        $file = $request->file('ttd_bagian_keuangan');
        $filename = 'ttd_bagian_keuangan_' . time() . '.' . $file->getClientOriginalExtension();
        $validatedData['ttd_bagian_keuangan'] = $file->storeAs('ttd_bagian_keuangan', $filename);
    }

$statusSebelumnya = $pengunduranDiri->status_surat;

$validatedData['status_surat'] = (
    ($pengunduranDiri->ttd_mahasiswa || $request->hasFile('ttd_mahasiswa')) && 
    ($pengunduranDiri->ttd_dosen_wali || $request->hasFile('ttd_dosen_wali')) &&
    ($pengunduranDiri->ttd_bagian_keuangan || $request->hasFile('ttd_bagian_keuangan')) &&
    ($pengunduranDiri->ttd_ketua_jurusan || $request->hasFile('ttd_ketua_jurusan'))
) ? 'selesai' : $statusSebelumnya;

$pengunduranDiri->update($validatedData);
$user = User::where('username', $request->username)->first();

if ($pengunduranDiri->status_surat == 'selesai' && $statusSebelumnya != 'selesai' && $user) {
    
    $message = "Halo {$pengunduranDiri->nama_mhs}, Surat Peringatan karena Pelanggaran Peraturan Akademik dengan No. Surat: {$pengunduranDiri->noSurat} telah selesai.";
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
    return redirect('/dashboard/bagian-keuangan/pengunduran-diri');
}

    public function destroy(PengunduranDiri $pengunduranDiri)
    {
        $pengunduranDiri->delete();

        return redirect('/dashboard/bagian-keuangan/pengunduran-diri');
    }

    public function cetak(PengunduranDiri $pengunduranDiri)
    {
        $pdf = Pdf::loadview('dashboard.bagian_keuangan.pengunduran_diri.cetak', [
            'title' => 'Cetak',
            'pengundurans' => $pengunduranDiri,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Surat Permohonan Pengunduran Diri_' . $pengunduranDiri->nama_mhs .'_'. $pengunduranDiri->username .'_' . '.pdf');
    }

    public function tolak(PengunduranDiri $pengunduranDiri)
{
    $pengunduranDiri->status_surat = 'ditolak';
    $pengunduranDiri->save();
    $message = "Halo {$pengunduranDiri->nama_mhs}, Surat Permohonan Pengunduran Diri dengan No. Surat: {$pengunduranDiri->noSurat} telah ditolak.";
    $user = User::where('username', $pengunduranDiri->username)->first();
    
    if ($user) {
        $no_telp = $user->no_telp;
        $response = Http::withHeaders([
            'Authorization' => 'YOUR_API_KEY',
        ])->post('https://api.fonnte.com/send', [
            'target' => $no_telp,
            'message' => $message,
            'countryCode' => '62',
        ]);
        
        if ($response->successful()) {
            Log::info('WhatsApp notification sent successfully.', [
                'no_telp' => $no_telp,
                'response' => $response->body(),
            ]);
        } else {
            Log::error('Failed to send WhatsApp notification.', [
                'no_telp' => $no_telp,
                'response' => $response->body(),
            ]);
        }
        Mail::to($user->email)->send(new PermohonanPengunduranDiriMail($pengunduranDiri));
        Log::info('Email notification sent successfully.', [
            'email' => $user->email
        ]);
    }

    return redirect('/dashboard/bagian-keuangan/pengunduran-diri')->with('status', 'Surat ditolak');
}
}
