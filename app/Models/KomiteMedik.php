<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomiteMedik extends Model
{

    protected $table = 'komite_medik';

    protected $fillable = [
        'file_pdf',
        'file_path',
        'unit',
    ];
}
