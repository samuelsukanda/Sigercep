<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Toner extends Model
{

    protected $table = 'toner';

    protected $fillable = [
        'nama',
        'unit',
        'toner',
        'jumlah',
        'tanggal',
        'tanda_tangan',
    ];
}
