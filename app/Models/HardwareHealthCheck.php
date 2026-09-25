<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HardwareHealthCheck extends Model
{

    protected $table = 'hardware_health_checks';

    protected $fillable = [
        'nama_pc',
        'ip',
        'unit',
        'lantai',
        'checked_at',
        'items',
    ];

    protected $casts = [
        'checked_at' => 'date',
        'items'      => 'array',
    ];
}