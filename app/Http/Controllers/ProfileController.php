<?php
namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;
        $kelas = $user->kelas;
        return view('profile.index', compact('user', 'kelas', 'role'), [
            'title' => 'Profile'
        ]);
    }

    public function edit(User $user)
    {
        $kelas = Kelas::all();
        $roles = Role::all();
        return view('profile.edit', compact('kelas', 'roles'), [
            'title' => 'Edit',
            'users' => $user
        ]);
    }

    public function update(Request $request, User $user)
{
    try {
        $validated = $request->validate([
            'email'            => ['nullable','required','string','max:255', Rule::unique('users')->ignore($user->id)],
            'password'         => 'nullable|string|min:8|confirmed',
            'current_password' => 'nullable|string',
            'profile_picture'  => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ], [
            'email.unique'            => 'Email sudah digunakan oleh akun lain.',
            'password.confirmed'      => 'Password baru dan konfirmasi password tidak cocok.',
            'password.min'            => 'Password harus terdiri dari minimal 8 karakter.',
            'profile_picture.image'   => 'File harus berupa gambar.',
            'profile_picture.mimes'   => 'Format gambar harus jpg, jpeg, png, atau gif.',
            'profile_picture.max'     => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($request->password && !$request->current_password) {
            return response()->json(['message'=>'Password saat ini harus diisi untuk mengubah password'], 422);
        }
        if ($request->current_password && !Hash::check($request->current_password, $user->password)) {
            return response()->json(['message'=>'Password saat ini tidak cocok'], 422);
        }
        if ($request->password && Hash::check($request->password, $user->password)) {
            return response()->json(['message'=>'Tidak ada perubahan data yang dilakukan', 'type'=>'info'], 204);
        }

        $changesDetected = false;

        if ($request->hasFile('profile_picture')) {
            $file     = $request->file('profile_picture');
            $newBytes = file_get_contents($file->getRealPath());

            $existingBytes = null;
            if ($user->profile_picture && Storage::exists('profile_pictures/'.$user->profile_picture)) {
                $existingBytes = Storage::get('profile_pictures/'.$user->profile_picture);
            }

            if ($existingBytes !== null && hash('md5', $newBytes) === hash('md5', $existingBytes)) {
            } else {
                $name = time().'.'.$file->getClientOriginalExtension();
                $file->storeAs('profile_pictures', $name);
                if ($existingBytes !== null) {
                    Storage::delete('profile_pictures/'.$user->profile_picture);
                }
                $user->profile_picture = $name;
                $changesDetected = true;
            }
        }

        if ($validated['email'] !== $user->email) {
            $changesDetected = true;
        }

        if ($request->password) {
            $changesDetected = true;
        }

        if (! $changesDetected) {
            return response()->json(['message'=>'Tidak ada perubahan data yang dilakukan', 'type'=>'info'], 204);
        }

        $user->update([
            'email'    => $validated['email'],
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return response()->json(['message'=>'Data berhasil diubah']);
    } catch (ValidationException $e) {
        return response()->json([
            'message'=>collect($e->errors())->flatten()->first()
        ], 422);
    }
}

}