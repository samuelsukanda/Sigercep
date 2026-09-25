<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndicatorTarget extends Model
{

    protected $fillable = [
        'indicator_id',
        'tahun',
        'target_value',
        'target_type',
        'operator',
    ];

    public function indicator()
    {
        return $this->belongsTo(Indicator::class);
    }
}
