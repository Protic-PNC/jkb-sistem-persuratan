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
        $validated = $request->validate([
            'no_telp' => [
                'nullable', 'required', 'string', 'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'email' => [
                'nullable', 'required', 'string', 'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'current_password' => 'nullable|string',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->password && !$request->current_password) {
            return redirect()->back();
        }

        if ($request->current_password && !Hash::check($request->current_password, $user->password)) {
            return redirect()->back();
        }

        if ($request->hasFile('profile_picture')) {
            $profilePicture = $request->file('profile_picture');
            $profilePictureName = time() . '.' . $profilePicture->getClientOriginalExtension();
            $profilePicture->storeAs('profile_pictures', $profilePictureName);
            if ($user->profile_picture) {
                Storage::delete('profile_pictures/' . $user->profile_picture);
            }
            $user->profile_picture = $profilePictureName;
        }

        $user->update([
            'no_telp' => $validated['no_telp'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $user->password,
        ]);

        return redirect('/profile');
    }
}