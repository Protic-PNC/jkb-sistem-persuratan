<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PernyataanMagang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

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
}
