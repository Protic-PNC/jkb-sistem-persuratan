<?php

namespace Database\Seeders;

use App\Models\PelanggaranAkademik;
use App\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::create([
            'nama_role' => 'Admin',
        ]);

        Role::create([
            'nama_role' => 'Mahasiswa',
        ]);

        Role::create([
            'nama_role' => 'Ketua Jurusan',
        ]);

        Role::create([
            'nama_role' => 'Dosen Wali',
        ]);

        Role::create([
            'nama_role' => 'Bagian Keuangan',
        ]);

        Role::create([
            'nama_role' => 'Bagian Perpustakaan',
        ]);

        User::create([
            'nama_pemilik' => 'Admin',
            'username' => '123',
            'email' => 'example@gmail.com',
            'role_id' => '1',
            'jurusan' => 'Komputer dan Bisnis',
            'perguruan_tinggi' => 'Politeknik Negeri Cilacap',
            'password' => bcrypt('123')
        ]);
    }
}
