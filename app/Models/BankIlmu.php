<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankIlmu extends Model
{

    protected $table = 'bank_ilmu';

    protected $fillable = [
        'file_pdf',
        'file_path',
    ];
}
