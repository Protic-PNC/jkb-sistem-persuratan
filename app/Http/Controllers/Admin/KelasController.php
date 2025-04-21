<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::all();
        $totalKelas = Kelas::count();

        if ($request->ajax()) {
            return view('dashboard.admin.kelas.table', compact('kelas'))->render();
        }

        return view('dashboard.admin.kelas.index', [
            'title' => 'Kelas',
            'kelas' => $kelas,
            'totalKelas' => $totalKelas
        ]);
    }

    public function create()
    {
        return view('dashboard.admin.kelas.create', [
            'title' => 'Tambah Kelas'
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'username_dosen_wali' => 'required|string|max:255'
        ]);

        Kelas::create($validatedData);

        return redirect('/dashboard/admin/kelas');
    }

    public function edit(Kelas $kelas)
    {
        return view('dashboard.admin.kelas.edit', [
            'title' => 'Edit Kelas',
            'kelas' => $kelas
        ]);
    }

    public function update(Request $request, Kelas $kelas)
    {
        $rules = [
            'nama_kelas' => 'required|string|max:255',
            'username_dosen_wali' => 'required|string|max:255',
        ];

        $validatedData = $request->validate($rules);

        $dataChanged = false;
        foreach ($validatedData as $key => $value) {
            if ($value != $kelas->$key) {
                $dataChanged = true;
                break;
            }
        }

        if (!$dataChanged) {
            return redirect('/dashboard/admin/kelas');
        }

        $kelas->update($validatedData);

        return redirect('/dashboard/admin/kelas');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();

        return redirect('/dashboard/admin/kelas');
    }
}
