<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PernyataanMagang extends Model
{
    use HasFactory;

    protected $guarded = ['noSurat'];
    protected $primaryKey = 'noSurat';

    public function getRouteKeyName()
    {
        return 'noSurat';
    }
}
