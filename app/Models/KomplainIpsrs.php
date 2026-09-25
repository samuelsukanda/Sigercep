<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomplainIpsrs extends Model
{

    protected $table = 'komplain_ipsrs';

    protected $fillable = [
        'nama',
        'unit',
        'tujuan_unit',
        'tanggal',
        'kendala',
        'foto',
        'status',
        'keterangan',
    ];
}
