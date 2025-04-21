<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelanggaranAkademik extends Model
{
    use HasFactory;

    protected $primaryKey = 'noSurat';
    protected $guarded = ['noSurat'];

    public function getRouteKeyName()
    {
        return 'noSurat';
    }

    public function kelas() {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id_kelas');
    }
    
    public function users() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
