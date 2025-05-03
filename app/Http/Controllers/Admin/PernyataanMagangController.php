<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PernyataanMagang;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\StatusSuratPernyataanMagangChangedMail;

class PernyataanMagangController extends Controller
{
    public function index(Request $request)
    {
    $pernyataans = PernyataanMagang::all();
    $totalPernyataanMagang = PernyataanMagang::count();

    if ($request->ajax()) {
        return view('dashboard.admin.pernyataan_magangs.table', compact('pernyataans'))->render();
    }
    return view('dashboard.admin.pernyataan_magangs.index', [
        'title' => 'Pernyataan Magang',
        'pernyataans' => $pernyataans,
        'totalPernyataanMagang' => $totalPernyataanMagang,
    ]);
    }


    public function create()
    {
        return view('dashboard.admin.pernyataan_magangs.create', [
            'title' => 'Pernyataan Magang',
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_ortu' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:255',
            'nama_mhs' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:pernyataan_magangs,username',
            'jurusan' => 'required|string|max:255',
            'perguruan_tinggi' => 'required|string|max:255',
            'tglSurat' => 'required|date'
        ]);

        PernyataanMagang::create($validatedData);

        return redirect('/dashboard/admin/pernyataan-magang');
    }

    public function checkUsername(Request $request)
    {
        $username = $request->input('username');
        $exists = PernyataanMagang::where('username', $username)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function show(PernyataanMagang $pernyataanMagang)
    {
        return view('dashboard.admin.pernyataan_magangs.show', [
            'title' => 'Pernyataan Magang',
            'pernyataans' => $pernyataanMagang,
        ]);
    }

    public function edit(PernyataanMagang $pernyataanMagang)
    {
        return view('dashboard.admin.pernyataan_magangs.edit', [
            'title' => 'Edit',
            'pernyataans' => $pernyataanMagang,
        ]);
    }

    public function update(Request $request, PernyataanMagang $pernyataanMagang)
    {
        $rules = [
            'nama_ortu' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:255',
            'nama_mhs' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'perguruan_tinggi' => 'required|string|max:255',
            'tglSurat' => 'required|date',
        ];

        $validatedData = $request->validate($rules);
        $pernyataanMagang->update($validatedData);

        return redirect('/dashboard/admin/pernyataan-magang');
    }

    public function destroy(PernyataanMagang $pernyataanMagang)
    {
        $pernyataanMagang->delete();

        return redirect('/dashboard/admin/pernyataan-magang');
    }

    public function cetak(PernyataanMagang $pernyataanMagang)
    {
        $pdf = Pdf::loadview('dashboard.admin.pernyataan_magangs.cetak', [
            'title' => 'Cetak',
            'pernyataans' => $pernyataanMagang,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Surat Pernyataan Magang_' . $pernyataanMagang->nama_mhs .'_'. $pernyataanMagang->username .'_'. $pernyataanMagang->jurusan . '.pdf');
    }

    public function uploadForm($id)
{
    $pernyataans = PernyataanMagang::findOrFail($id);
    return view('dashboard.admin.pernyataan_magangs.upload', [
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



public function setujui($id)
{
    $pernyataans = PernyataanMagang::findOrFail($id);
    $pernyataans->status = 'approved';
    $pernyataans->alasan = null;
    $pernyataans->save();
    $user = User::where('username', $pernyataans->username)->first();

    Mail::to($user->email)->send(new StatusSuratPernyataanMagangChangedMail($pernyataans, 'approved'));

    return response()->json(['message' => 'Surat disetujui.']);
}

public function tolak(Request $request, $id)
{
    $request->validate(['alasan' => 'required|string']);
    $pernyataans = PernyataanMagang::findOrFail($id);
    $pernyataans->status = 'rejected';
    $pernyataans->alasan = $request->alasan;
    $pernyataans->save();
    $user = User::where('username', $pernyataans->username)->first();

    Mail::to($user->email)->send(new StatusSuratPernyataanMagangChangedMail($pernyataans, 'rejected'));

    return response()->json(['message' => 'Surat ditolak.']);
}
}
