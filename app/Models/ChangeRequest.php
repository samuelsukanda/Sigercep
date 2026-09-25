<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChangeRequest extends Model
{

    protected $table = 'change_requests';

    protected $fillable = [
        'user_id',
        'nama',
        'jabatan',
        'jabatan_id',
        'permintaan_fitur',
        'deskripsi',
        'file_pendukung',
        'file_path',
        'status',
        'status_pengerjaan',
        'no_tiket',
        'mandays',
        'catatan',
        'approval_1_status',
        'approval_1_by',
        'approval_1_at',
        'approval_1_ttd',
        'approval_2_status',
        'approval_2_by',
        'approval_2_at',
        'approval_2_ttd',
        'reject_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
