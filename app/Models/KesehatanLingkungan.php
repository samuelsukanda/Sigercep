<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KesehatanLingkungan extends Model
{

    protected $table = 'kesehatan_lingkungan';

    protected $fillable = [
        'nama',
        'unit',
        'tanggal',
        'lokasi_masalah',
        'jenis_hama',
        'dokumentasi',
        'status',
        'keterangan',
    ];
}
