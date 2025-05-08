<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Kelas;
use App\Imports\KelasImport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
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
                'username_dosen_wali' => [
                    'required',
                    'string',
                    'max:255',
                    'unique:kelas,username_dosen_wali',
                    function ($attribute, $value, $fail) {
                        $user = User::where('username', $value)->first();
                        if (!$user) {
                            $fail("Username '{$value}' tidak ditemukan.");
                        } elseif ($user->role_id !== 4) {
                            $fail("Username '{$value}' bukan dosen wali.");
                        }
                    }
                ],
            ], [
                'nama_kelas.unique' => 'Nama kelas sudah terdaftar.',
                'username_dosen_wali.unique' => 'Username dosen wali sudah terdaftar.',
            ]);

            Kelas::create($validatedData);

            return response()->json(['message' => 'Data berhasil disimpan.']);
        } catch (ValidationException $e) {
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
        try {
            $validatedData = $request->validate([
                'nama_kelas' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('kelas', 'nama_kelas')->ignore($kelas->id_kelas, 'id_kelas'),
                ],
                'username_dosen_wali' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('kelas', 'username_dosen_wali')->ignore($kelas->id_kelas, 'id_kelas'),
                    function ($attribute, $value, $fail) {
                        $user = User::where('username', $value)->first();
                        if (!$user) {
                            $fail("Username {$value} tidak ditemukan.");
                        } elseif ($user->role_id !== 4) {
                            $fail("Username {$value} bukan dosen wali.");
                        }
                    }
                ],
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

    public function showImportForm()
    {
        return view('dashboard.admin.kelas.import', [
            'title' => 'Kelas'
        ]);
    }

    public function importCSV(Request $request)
    {
        try {
            $request->validate([
                'csv_file' => 'required|mimes:csv,txt'
            ], [
                'csv_file.required' => 'Silakan unggah file CSV terlebih dahulu.',
                'csv_file.mimes' => 'Format file harus CSV atau TXT.'
            ]);

            Excel::import(new KelasImport, $request->file('csv_file'));

            return response()->json(['message' => 'Data berhasil diimport.']);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
                'message' => collect($e->errors())->flatten()->first()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengimport data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadTemplate()
    {
        $filePath = public_path('storage/templates/template-kelas.csv');

        if (!File::exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Response::download($filePath, 'template-kelas.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
