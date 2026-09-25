<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{

    protected $table = 'peminjaman';

    protected $fillable = [
        'nama',
        'unit',
        'tanggal',
        'barang',
        'tanda_tangan',
        'status',
    ];
}
