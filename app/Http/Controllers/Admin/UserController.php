<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use App\Models\Kelas;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();
        $totalUser = User::count();
        if ($request->ajax()) {
            return view('dashboard.admin.users.table', compact('users'))->render();
        }

        return view('dashboard.admin.users.index', [
            'title' => 'Akun',
            'users' => $users,
            'totalUser' => $totalUser
        ]);
    }

    public function create()
    {
        $kelas = Kelas::all();
        $roles = Role::all();
        return view('dashboard.admin.users.create', compact('kelas', 'roles'), [
            'title' => 'Tambah Akun'
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_pemilik' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'role_id' => 'required|exists:roles,id_role',
                'kelas_id' => 'nullable|string|max:255',
                'jurusan' => 'required|string|max:255',
                'perguruan_tinggi' => 'required|string|max:255',
            ], [
                'username.unique' => 'Username sudah digunakan oleh akun lain.',
                'email.unique' => 'Email sudah digunakan oleh akun lain.',
                'password.confirmed' => 'Password dan konfirmasi password tidak cocok.',
            ]);

            User::create([
                'nama_pemilik' => $validated['nama_pemilik'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $validated['role_id'],
                'kelas_id' => $validated['kelas_id'] ?? null,
                'jurusan' => $validated['jurusan'],
                'perguruan_tinggi' => $validated['perguruan_tinggi'],
            ]);

            return response()->json(['message' => 'Data berhasil disimpan.']);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => collect($e->errors())->flatten()->first()
            ], 422);
        }
    }

    public function edit(User $user)
    {
        $kelas = Kelas::all();
        $roles = Role::all();
        return view('dashboard.admin.users.edit', compact('kelas', 'roles'), [
            'title' => 'Edit',
            'users' => $user
        ]);
    }

    public function update(Request $request, User $user)
    {
        try {
            $validated = $request->validate(
                [
                    'nama_pemilik' => 'required|string|max:255',
                    'username'     => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
                    'email'        => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                    'password'     => [
                        'nullable',
                        'string',
                        'min:8',
                        'confirmed',
                        function ($attribute, $value, $fail) use ($user) {
                            if ($value && Hash::check($value, $user->password)) {
                                $fail('Password baru tidak boleh sama dengan password lama.');
                            }
                        },
                    ],
                    'role_id' => 'required|exists:roles,id_role',
                    'kelas_id' => 'nullable|string|max:255',
                    'jurusan' => 'required|string|max:255',
                    'perguruan_tinggi' => 'required|string|max:255'
                ],
                [
                    'username.unique' => 'Username sudah digunakan oleh akun lain',
                    'email.unique' => 'Email sudah digunakan oleh akun lain',
                    'password.min' => 'Password harus terdiri dari minimal 8 karakter.',
                ]
            );

            $user->update([
                'nama_pemilik' => $validated['nama_pemilik'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => $validated['password'] ? Hash::make($validated['password']) : $user->password,
                'role_id' => $validated['role_id'],
                'kelas_id' => $validated['kelas_id'] ?? null,
                'jurusan' => $validated['jurusan'],
                'perguruan_tinggi' => $validated['perguruan_tinggi'],
            ]);

            return response()->json(['message' => 'Data berhasil diubah']);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => collect($e->errors())->flatten()->first()
            ], 422);
        }
    }


    public function destroy(User $user)
    {
        $user->delete();
        return redirect('/dashboard/admin/user');
    }

    public function getDosenWali(Request $request)
    {

        $kelas = Kelas::where('id_kelas', $request->kelas_id)->first();

        if ($kelas) {
            $dosenWali = User::where('username', $kelas->username_dosen_wali)->first();

            if ($dosenWali) {
                return response()->json(['nama_dosen_wali' => $dosenWali->nama_pemilik]);
            }
        }

        return response()->json(['nama_dosen_wali' => '']);
    }

    public function getMahasiswaByNPM(Request $request)
    {
        $mahasiswa = User::where('username', $request->npm)->first();

        if ($mahasiswa) {
            $kelas = $mahasiswa->kelas;
            $nama_kelas = $kelas ? $kelas->nama_kelas : '';
            $nama_dosen_wali = '';
            if ($kelas) {
                $dosenWali = User::where('username', $kelas->username_dosen_wali)->first();
                $nama_dosen_wali = $dosenWali ? $dosenWali->nama_pemilik : '';
            }
            return response()->json([
                'nama_mhs' => $mahasiswa->nama_pemilik,
                'semester' => $mahasiswa->semester ?? '',
                'kelas_id' => $mahasiswa->kelas_id ?? '',
                'nama_kelas' => $nama_kelas,
                'nama_dosen_wali' => $nama_dosen_wali
            ]);
        }

        return response()->json([
            'nama_mhs' => '',
            'semester' => '',
            'kelas_id' => '',
            'nama_kelas' => '',
            'nama_dosen_wali' => ''
        ]);
    }

    public function showImportForm()
    {
        return view('dashboard.admin.users.import', [
            'title' => 'Unggah Data Akun',
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

            Excel::import(new UsersImport, $request->file('csv_file'));

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
        $filePath = public_path('storage/templates/template-akun.csv');

        if (!File::exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Response::download($filePath, 'template-akun.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function resetAll()
    {
        try {
            if (User::where('role_id', '!=', 1)->count() === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data akun sudah kosong'
                ], 200);
            }

            User::where('role_id', '!=', 1)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Semua akun berhasil direset'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mereset akun: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user details by username.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getUserByUsername(Request $request)
    {
        $username = $request->input('username');
        
        if (!$username) {
            return response()->json(['error' => 'Username is required'], 400);
        }
        
        $user = User::where('username', $username)->first();
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        return response()->json([
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'nama_pemilik' => $user->nama_pemilik,
                'email' => $user->email,
                'role_id' => $user->role_id
            ]
        ]);
    }
}
