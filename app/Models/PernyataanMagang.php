<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PernyataanMagang extends Model
{
    use HasFactory;

    protected $primaryKey = 'noSurat';
    protected $guarded = ['noSurat'];

    public function getRouteKeyName()
    {
        return 'noSurat';
    }
}
