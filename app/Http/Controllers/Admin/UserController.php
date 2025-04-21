<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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
            'title' => 'User',
            'users' => $users,
            'totalUser' => $totalUser
        ]);
    }

    public function create()
    {
        $kelas = Kelas::all();
        $roles = Role::all();
        return view('dashboard.admin.users.create', compact('kelas', 'roles'), [
            'title' => 'Tambah User'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pemilik' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'no_telp' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id_role',
            'kelas_id' => 'nullable|string|max:255',
            'jurusan' => 'required|string|max:255',
            'perguruan_tinggi' => 'required|string|max:255',
        ]);

        User::create([
            'nama_pemilik' => $validated['nama_pemilik'],
            'username' => $validated['username'],
            'no_telp' => $validated['no_telp'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
            'kelas_id' => $validated['kelas_id'] ?? null,
            'jurusan' => $validated['jurusan'],
            'perguruan_tinggi' => $validated['perguruan_tinggi']
        ]);

        return redirect('/dashboard/admin/user');
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
        $validated = $request->validate([
            'nama_pemilik' => 'required|string|max:255',
            'username' => [
                'required', 'string', 'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'no_telp' => [
                'required', 'string', 'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'email' => [
                'required', 'string', 'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id_role',
            'kelas_id' => 'nullable|string|max:255',
            'jurusan' => 'required|string|max:255',
            'perguruan_tinggi' => 'required|string|max:255',
        ]);

        $user->update([
            'nama_pemilik' => $validated['nama_pemilik'],
            'username' => $validated['username'],
            'no_telp' => $validated['no_telp'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $user->password,
            'role_id' => $validated['role_id'],
            'kelas_id' => $validated['kelas_id'] ?? null,
            'jurusan' => $validated['jurusan'],
            'perguruan_tinggi' => $validated['perguruan_tinggi'],
        ]);

        return redirect('/dashboard/admin/user');
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
        return response()->json(['nama_mhs' => $mahasiswa->nama_pemilik]);
    }

    return response()->json(['nama_mhs' => '']);
    }
}
