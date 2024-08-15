<?php

namespace Database\Seeders;

use App\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\PernyataanMagang;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::create([
            'name' => 'Admin',
            'npm' => '220302083',
            'email' => 'example@gmail.com',
            'role_id' => '1',
            'jurusan' => 'Pegawai',
            'perguruan_tinggi' => 'Politeknik Negeri Cilacap',
            'password' => bcrypt('123')
        ]);
        User::create([
            'name' => 'Evan Arlen Handy',
            'npm' => '220302082',
            'email' => 'example1@gmail.com',
            'role_id' => '2',
            'jurusan' => 'Komputer dan Bisnis',
            'perguruan_tinggi' => 'Politeknik Negeri Cilacap',
            'password' => bcrypt('123')
        ]);

        Role::create([
            'role_name' => 'admin',
        ]);

        Role::create([
            'role_name' => 'mahasiswa',
        ]);

        User::factory(10)->create();

        PernyataanMagang::factory(10)->create();
    }
}
