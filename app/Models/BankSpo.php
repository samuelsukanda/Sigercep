<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankSpo extends Model
{

    protected $table = 'bank_spo';

    protected $fillable = [
        'file_pdf',
        'file_path',
        'unit',
        'jenis_spo',
    ];
}
