<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mutu extends Model
{

    protected $table = 'mutu';

    protected $fillable = [
        'indikator',
        'periode',
        'unit',
        'pj_data',
        'numerator',
        'penumerator',
        'capaian',
    ];
}
