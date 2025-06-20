<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    protected $table = 'alamats';

    protected $fillable = [
        'name',
        'alamat',
        'user_id'
    ];
}
