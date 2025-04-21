<?php

namespace App\Http\Controllers\Mahasiswa;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PernyataanMagang;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PernyataanMagangController extends Controller
{
    
    public function index(Request $request)
    {
    $user = Auth::user();
    $pernyataans = PernyataanMagang::where('username', $user->username)->latest()->get();
    $totalPernyataanMagang = $pernyataans->count();

    if ($request->ajax()) {
        return view('dashboard.mahasiswa.pernyataan_magangs.table', compact('pernyataans'))->render();
    }

    return view('dashboard.mahasiswa.pernyataan_magangs.index', [
        'title' => 'Pernyataan Magang',
        'pernyataans' => $pernyataans,
        'totalPernyataanMagang' => $totalPernyataanMagang,
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
        $validatedData = $request->validate([
            'nama_ortu' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:255',
            'nama_mhs' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'perguruan_tinggi' => 'required|string|max:255',
            'tglSurat' => 'required|date'
        ]);

        PernyataanMagang::create($validatedData);

        return redirect('/dashboard/mahasiswa/pernyataan-magang');
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

        return redirect('/dashboard/mahasiswa/pernyataan-magang');
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
}
