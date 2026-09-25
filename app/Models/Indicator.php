<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{

    protected $fillable = [
        'pj',
        'nama_indikator',
        'jenis_indikator',
        'unit_terkait',
    ];

    public function targets()
    {
        return $this->hasMany(IndicatorTarget::class);
    }

    public function values()
    {
        return $this->hasMany(IndicatorValue::class);
    }
}
