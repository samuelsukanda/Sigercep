<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanPerilaku extends Model
{

    protected $table = 'laporan_perilaku';

    protected $fillable = [
        'nama',
        'nik',
        'unit',
        'tanggal',
        'kategori_laporan',
        'keterangan_perilaku',
        'file_pdf',
        'file_path',
    ];
}
