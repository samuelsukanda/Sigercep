<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hardware extends Model
{

    protected $table = 'hardware';

    protected $fillable = [
        'ip',
        'nama',
        'unit',
        'lantai',
        'tanggal',
        'checklist',
    ];

    protected $casts = [
        'checklist' => 'array',
    ];
}
