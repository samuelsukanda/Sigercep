<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HardwareEvaluasi extends Model
{

    protected $table = 'hardware_evaluasi';

    protected $fillable = [
        'bulan',
        'nomor',
        'kendala',
        'rtl',
    ];
}
