<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Utw extends Model
{

    protected $table = 'utw';

    protected $fillable = [
        'file_pdf',
        'file_path',
        'unit',
    ];
}
