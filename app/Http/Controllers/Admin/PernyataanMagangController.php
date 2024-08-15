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
    $search = $request->input('search');

    $pernyataans = PernyataanMagang::when($search, function ($query, $search) {
        return $query->where('noSurat', 'like', "%{$search}%")
                     ->orWhere('nama_mhs', 'like', "%{$search}%")
                     ->orWhere('npm', 'like', "%{$search}%")
                     ->orWhere('jurusan', 'like', "%{$search}%")
                     ->orWhere('tglSurat', 'like', "%{$search}%");
    })->latest()->paginate(5);

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
            'npm' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'perguruan_tinggi' => 'required|string|max:255',
            'tglSurat' => 'required|date'
        ]);

        PernyataanMagang::create($validatedData);

        return redirect('/dashboard/admin/pernyataan-magang')->with('success', 'Surat berhasil ditambahkan!');
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
            'npm' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'perguruan_tinggi' => 'required|string|max:255',
            'tglSurat' => 'required|date',
        ];

        $validatedData = $request->validate($rules);

        $dataChanged = false;
        foreach ($validatedData as $key => $value) {
            if ($value != $pernyataanMagang->$key) {
                $dataChanged = true;
                break;
            }
        }

        if (!$dataChanged) {
            return redirect('/dashboard/admin/pernyataan-magang')->with('info', 'Tidak ada perubahan surat yang dilakukan.');
        }

        $pernyataanMagang->update($validatedData);

        return redirect('/dashboard/admin/pernyataan-magang')->with('success', 'Surat berhasil diedit!');
    }

    public function destroy(PernyataanMagang $pernyataanMagang)
    {
        $pernyataanMagang->delete();

        return redirect('/dashboard/admin/pernyataan-magang')->with('success', 'Surat berhasil dihapus!');
    }

    public function cetak(PernyataanMagang $pernyataanMagang)
    {
        $pdf = Pdf::loadview('dashboard.admin.pernyataan_magangs.cetak', [
            'title' => 'Cetak',
            'pernyataans' => $pernyataanMagang,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Surat Pernyataan Magang_' . $pernyataanMagang->nama_mhs .'_'. $pernyataanMagang->npm .'_'. $pernyataanMagang->jurusan . '.pdf');
    }
}
