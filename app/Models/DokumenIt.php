<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenIt extends Model
{

    protected $table = 'dokumen_it';

    protected $fillable = [
        'file_pdf',
        'file_path',
        'jenis_dokumen',
    ];
}
