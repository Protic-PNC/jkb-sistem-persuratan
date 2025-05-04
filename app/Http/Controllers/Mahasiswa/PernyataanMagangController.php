<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PernyataanMagang;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PernyataanMagangController extends Controller
{
    
    public function index(Request $request)
    {
    $user = Auth::user();
    $pernyataans = PernyataanMagang::where('username', $user->username)->latest()->get();
    $totalPernyataanMagang = $pernyataans->count();
    $totalBelumSelesai = PernyataanMagang::where('username', $user->username)
    ->where(function ($query) {
        $query->whereNull('status')
              ->orWhere('status', 'belum selesai');
    })
    ->count();


    if ($request->ajax()) {
        return view('dashboard.mahasiswa.pernyataan_magangs.table', compact('pernyataans'))->render();
    }

    return view('dashboard.mahasiswa.pernyataan_magangs.index', [
        'title' => 'Pernyataan Magang',
        'pernyataans' => $pernyataans,
        'totalPernyataanMagang' => $totalPernyataanMagang,
        'totalBelumSelesai' => $totalBelumSelesai,
    ]);
    }


    public function create()
    {
        return view('dashboard.mahasiswa.pernyataan_magangs.create', [
            'title' => 'Pernyataan Magang',
        ]);
    }

    public function store(Request $request)
{
    try {
        $validatedData = $request->validate([
            'nama_ortu' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:255',
            'nama_mhs' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                'unique:pernyataan_magangs,username',
                function ($attribute, $value, $fail) {
                    $user = User::where('username', $value)->first();
                    if (!$user) {
                        $fail("Username {$value} tidak ditemukan.");
                    } elseif ($user->role_id !== 2) {
                        $fail("Username {$value} bukan mahasiswa.");
                    }
                }
            ],
            'jurusan' => 'required|string|max:255',
            'perguruan_tinggi' => 'required|string|max:255',
            'tglSurat' => 'required|date',],
            [
                'username.unique' => 'Username tersebut sudah digunakan dalam surat pernyataan magang lain.',
            ]);

        PernyataanMagang::create($validatedData);

        return response()->json(['message' => 'Data berhasil disimpan']);
    } catch (ValidationException $e) {
        return response()->json([
            'message' => collect($e->errors())->flatten()->first()
        ], 422);
    }
}

    public function show(PernyataanMagang $pernyataanMagang)
    {
        return view('dashboard.mahasiswa.pernyataan_magangs.show', [
            'title' => 'Pernyataan Magang',
            'pernyataans' => $pernyataanMagang,
        ]);
    }

    public function edit(PernyataanMagang $pernyataanMagang)
    {
        return view('dashboard.mahasiswa.pernyataan_magangs.edit', [
            'title' => 'Edit',
            'pernyataans' => $pernyataanMagang,
        ]);
    }

    public function update(Request $request, PernyataanMagang $pernyataanMagang)
{
    try {
        $validatedData = $request->validate([
            'nama_ortu' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:255',
            'nama_mhs' => 'required|string|max:255',
            'username' => [
                'required', 'string', 'max:255',
                function ($attribute, $value, $fail) {
                    $user = User::where('username', $value)->first();
                    if (!$user) {
                        $fail("Username {$value} tidak ditemukan.");
                    } elseif ($user->role_id !== 2) {
                        $fail("Username {$value} bukan mahasiswa.");
                    }
                }
            ],
            'jurusan' => 'required|string|max:255',
            'perguruan_tinggi' => 'required|string|max:255',
            'tglSurat' => 'required|date',],
            [
                'username.unique' => 'Username tersebut sudah digunakan dalam surat pernyataan magang lain.',
            ]);

        $pernyataanMagang->update($validatedData);

        return response()->json(['message' => 'Data berhasil diubah']);
    } catch (ValidationException $e) {
        return response()->json([
            'message' => collect($e->errors())->flatten()->first()
        ], 422);
    }
}

    public function destroy(PernyataanMagang $pernyataanMagang)
    {
        PernyataanMagang::where('noSurat', $pernyataanMagang->noSurat)->delete();

        return redirect('/dashboard/mahasiswa/pernyataan-magang');
    }

    public function cetak(PernyataanMagang $pernyataanMagang)
    {
        $pdf = Pdf::loadview('dashboard.mahasiswa.pernyataan_magangs.cetak', [
            'title' => 'Cetak',
            'pernyataans' => $pernyataanMagang,
        ])->setPaper('a4', 'potrait');

        return $pdf->stream('Surat Pernyataan Magang_' . $pernyataanMagang->nama_mhs .'_'. $pernyataanMagang->username .'_'. $pernyataanMagang->jurusan . '.pdf');
    }

    public function uploadForm($id)
{
    $pernyataans = PernyataanMagang::findOrFail($id);
    return view('dashboard.mahasiswa.pernyataan_magangs.upload', [
    'title' => 'Pernyataan Magang',
    'pernyataans' => $pernyataans
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

    $pernyataans = PernyataanMagang::findOrFail($id);
    $file = $request->file('file_pdf');

    if ($pernyataans->file_pdf && Storage::disk('public')->exists($pernyataans->file_pdf)) {
        $old = Storage::get('public/' . $pernyataans->file_pdf);
        if (md5_file($file->getRealPath()) === md5($old)) {
            return response()->json([
                'status' => 'info',
                'message' => 'Tidak ada perubahan data yang dilakukan.'
            ]);
        }
    }

    $path = $file->store('surat-magang', 'public');
    $pernyataans->file_pdf = $path;
    $pernyataans->save();

    return response()->json([
        'status' => 'success',
        'message' => 'File berhasil diupload.'
    ]);
}
}