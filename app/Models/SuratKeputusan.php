<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratKeputusan extends Model
{

    protected $table = 'surat_keputusan';

    protected $fillable = [
        'file_pdf',
        'file_path',
        'unit',
    ];
}
