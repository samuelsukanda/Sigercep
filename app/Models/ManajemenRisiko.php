<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManajemenRisiko extends Model
{

    protected $table = 'manajemen_risiko';

    protected $fillable = [
        'nama',
        'unit',
        'tanggal',
        'uraian',
        'dampak',
        'kemungkinan',
        'nilai',
        'keterangan',
    ];
}
