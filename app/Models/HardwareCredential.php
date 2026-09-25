<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HardwareCredential extends Model
{

    protected $table = 'hardware_credentials';

    protected $fillable = [
        'nama_pc',
        'ip',
        'unit',
        'lantai',
        'items',
    ];

    protected $casts = [
        'items' => 'array',
    ];
}