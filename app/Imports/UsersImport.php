<?php
namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    public function model(array $row)
    {
        return new User([
            'nama_pemilik' => $row[0],
            'username' => $row[1], 
            'email' => $row[2], 
            'password' => bcrypt($row[3]),
            'role_id' => $row[4],
            'kelas_id' => $row[5],
            'jurusan' => $row[6], 
            'perguruan_tinggi' => $row[7],
        ]);
    }
}
