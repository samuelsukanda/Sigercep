<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MandatoryTraining extends Model
{

    protected $table = 'mandatory_training';

    protected $fillable = [
        'file_pdf',
        'file_path',
    ];
}
