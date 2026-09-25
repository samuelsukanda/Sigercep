<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitasi extends Model
{

    protected $table = 'visitasi';

    protected $fillable = [
        'nama',
        'tim',
        'tanggal',
        'kendala',
        'foto',
    ];
}
