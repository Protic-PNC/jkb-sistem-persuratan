<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $guarded = ['id_role'];

    public function users() {
        return $this->hasMany(User::class, 'role_id', 'id_role');
    }
}
