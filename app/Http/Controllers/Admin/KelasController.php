<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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
    try {
        $validatedData = $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas',
            'username_dosen_wali' => 'required|string|max:255|unique:kelas,username_dosen_wali',
        ], [
            'nama_kelas.unique' => 'Nama kelas sudah terdaftar.',
            'username_dosen_wali.unique' => 'Username dosen wali sudah terdaftar.',
        ]);

        Kelas::create($validatedData);

        return response()->json(['message' => 'Data berhasil disimpan.']);
    }catch (ValidationException $e) {
    return response()->json([
        'message' => collect($e->errors())->flatten()->first()
    ], 422);
    }
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
    try{
        $validatedData = $request->validate([
            'nama_kelas' => ['required', 'string', 'max:255', Rule::unique('kelas')->ignore($kelas->id)],
            'username_dosen_wali' => ['required', 'string', 'max:255', Rule::unique('kelas')->ignore($kelas->id)],
        ], [
            'nama_kelas.unique' => 'Nama kelas sudah terdaftar.',
            'username_dosen_wali.unique' => 'Username dosen wali sudah terdaftar.',
        ]);

        $kelas->update($validatedData);
        
        return response()->json(['message' => 'Data berhasil diubah']);
    } catch (ValidationException $e) {
        return response()->json([
            'message' => collect($e->errors())->flatten()->first()
        ], 422);
        }
}

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();

        return redirect('/dashboard/admin/kelas');
    }
}
