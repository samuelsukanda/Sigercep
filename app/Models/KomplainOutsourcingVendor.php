<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomplainOutsourcingVendor extends Model
{

    protected $table = 'komplain_outsourcing_vendor';

    protected $fillable = [
        'nama',
        'unit',
        'tujuan_unit',
        'jam',
        'tanggal',
        'kendala',
        'area',
        'foto',
        'status',
        'keterangan',
    ];
}
