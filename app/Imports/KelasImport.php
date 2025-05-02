<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KelasImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $namaKelas = trim($row['nama_kelas'], "\"'");
            $username = trim($row['username_dosen_wali'], "\"'");

            $validator = Validator::make([
                'nama_kelas' => $namaKelas,
                'username_dosen_wali' => $username,
            ], [
                'nama_kelas' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('kelas', 'nama_kelas'),
                ],
                'username_dosen_wali' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('kelas', 'username_dosen_wali'),
                    function ($attribute, $value, $fail) {
                        $user = User::where('username', $value)->first();
                        if (!$user) {
                            $fail("Username '{$value}' tidak ditemukan di sistem.");
                        } elseif ($user->role !== 'dosen wali') {
                            $fail("Username '{$value}' bukan dosen wali.");
                        }
                    }
                ],
            ], [
                'nama_kelas.required' => "Baris ".($index + 2).": Nama kelas tidak boleh kosong.",
                'username_dosen_wali.required' => "Baris ".($index + 2).": Username dosen wali tidak boleh kosong.",
                'nama_kelas.unique' => "Baris ".($index + 2).": Nama kelas '{$namaKelas}' sudah terdaftar.",
                'username_dosen_wali.unique' => "Baris ".($index + 2).": Username '{$username}' sudah digunakan.",
            ]);

            if ($validator->fails()) {
                throw new \Illuminate\Validation\ValidationException($validator);
            }

            Kelas::create([
                'nama_kelas' => $namaKelas,
                'username_dosen_wali' => $username,
            ]);
        }
    }
}
