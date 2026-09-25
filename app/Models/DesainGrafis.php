<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesainGrafis extends Model
{

    protected $table = 'desain_grafis';

    protected $fillable = [
        'nama',
        'unit',
        'keperluan',
        'tanggal',
        'desain',
        'status',
        'panjang',
        'tinggi',
        'satuan',
        'menit',
        'detik',
    ];
}
