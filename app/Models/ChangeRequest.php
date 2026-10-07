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

    /* Baris hasil import Excel SIMRS (dicap oleh SimrsChangeRequestSeeder). */
    public function isMigrasi(): bool
    {
        return $this->sumber_data === 'Migrasi SIMRS';
    }

    /*
     * Urutan pengerjaan otomatis: peta id => nomor untuk permintaan SIMRS yang
     * masih aktif. Nomor = posisi menurut tanggal permintaan terlama dulu.
     * Row yang tidak ada di peta (status Open/Done/Closed atau non-SIMRS)
     * tidak punya nomor. Mapping ini dihitung dari status terkini, jadi nomor
     * ikut maju otomatis begitu baris di depannya selesai.
     */
    public static function urutanPengerjaanMap(): array
    {
        $ids = static::where('permintaan_fitur', 'SIMRS')
            ->whereIn('status_pengerjaan', ['Pending', 'In Progress', 'QC'])
            ->orderBy('created_at')->orderBy('id')
            ->pluck('id')->all();

        $map = [];
        foreach ($ids as $i => $id) {
            $map[$id] = $i + 1;
        }

        return $map;
    }
}
