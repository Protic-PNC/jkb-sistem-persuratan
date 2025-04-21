<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_kelas';
    protected $guarded = ['id_kelas'];

    public function users() {
        return $this->hasMany(User::class, 'kelas_id', 'id_kelas');
    }

    public function pelanggaranAkademik() {
        return $this->hasMany(PelanggaranAkademik::class, 'kelas_id', 'id_kelas');
    }
    
    public function pengunduranDiri() {
        return $this->hasMany(pengunduranDiri::class, 'kelas_id', 'id_kelas');
    }
}