<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Role;
use App\Models\Kelas;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * Map each row of the Excel to a User model instance
     *
     * @param array $row
     * @return \App\Models\User|null
     */
    public function model(array $row)
    {
        $role  = Role::where('nama_role', $row['role'])->first();
        $kelas = isset($row['kelas']) && $row['kelas'] !== ''
                 ? Kelas::where('nama_kelas', $row['kelas'])->first()
                 : null;

        // Skip if user already exists by email or username
        $existingUser = User::where('email', $row['email'])
                            ->orWhere('username', $row['username'])
                            ->first();
        if ($existingUser) {
            return null;
        }

        return new User([
            'nama_pemilik'     => $row['nama_pemilik'],
            'username'         => $row['username'],
            'email'            => $row['email'],
            'password'         => Hash::make($row['password']),
            'role_id'          => $role?->id_role,
            'kelas_id'         => $kelas?->id_kelas,
            'jurusan'          => $row['jurusan'],
            'perguruan_tinggi' => $row['perguruan_tinggi'],
        ]);
    }

    /**
     * Define validation rules, with conditional 'kelas'
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email'            => 'required|email|unique:users,email',
            'username'         => 'required|unique:users,username',
            'nama_pemilik'     => 'required',
            'password'         => 'required|min:8',
            'role'             => 'required|exists:roles,nama_role',
            'jurusan'          => 'required',
            'perguruan_tinggi' => 'required',
            'kelas'            => 'required_if:role,Mahasiswa|nullable|exists:kelas,nama_kelas',
        ];
    }

    /**
     * Custom validation messages
     *
     * @return array
     */
    public function customValidationMessages(): array
    {
        return [
            'email.required'            => 'Kolom email harus diisi.',
            'email.email'               => 'Format email tidak valid.',
            'email.unique'              => 'Email :input sudah digunakan.',
            'username.required'         => 'Kolom username harus diisi.',
            'username.unique'           => 'Username :input sudah digunakan.',
            'nama_pemilik.required'     => 'Kolom nama pemilik harus diisi.',
            'password.required'         => 'Kolom password harus diisi.',
            'password.min'              => 'Password harus terdiri dari minimal 8 karakter.',
            'role.required'             => 'Kolom role harus diisi.',
            'role.exists'               => 'Role :input tidak valid.',
            'kelas.required_if'         => 'Kolom kelas harus diisi jika role adalah Mahasiswa.',
            'kelas.exists'              => 'Kelas :input tidak valid atau tidak ditemukan.',
            'jurusan.required'          => 'Kolom jurusan harus diisi.',
            'perguruan_tinggi.required' => 'Kolom perguruan_tinggi harus diisi.',
        ];
    }
}
