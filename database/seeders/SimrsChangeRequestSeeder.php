<?php

namespace Database\Seeders;

use App\Models\ChangeRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class SimrsChangeRequestSeeder extends Seeder
{
    /*
     * Jaminan: seeder ini TIDAK PERNAH truncate / menimpa data.
     * Baris yang sudah ada (cocok deskripsi + tanggal) selalu dilewati apa adanya.
     * Pencocokan nama sepenuhnya otomatis via resolveUser(); tanpa peta manual.
     */
    public function run(): void
    {
        $path = public_path('assets/file/SIMRS.xlsx');
        if (!is_file($path)) {
            $this->command->error('File tidak ditemukan: ' . $path);
            return;
        }

        $rows = array_values(IOFactory::load($path)->getActiveSheet()->toArray());

        $users = User::all(['id', 'name', 'username', 'unit', 'jabatan', 'jabatan_id']);
        $allowedStatus = ['Open', 'In Progress', 'Pending', 'QC', 'Done', 'Closed'];

        $created = 0;
        $skipped = 0;
        $unmatched = [];

        foreach ($rows as $r) {
            $r = array_pad($r, 8, null);
            [, $tgl, $deskripsi, $status, $nama, $tiket, $mandays, $catatan] = $r;

            $deskripsi = trim((string) $deskripsi);
            if ($deskripsi === '' || strtolower($deskripsi) === 'deskripsi') {
                continue; // baris kosong di ekor file / baris header
            }
            if (strtolower(trim((string) $tgl)) === 'tanggal permintaan') {
                continue; // baris header
            }

            $createdAt = $this->parseDate($tgl);

            $nama = trim((string) $nama);
            $user = $this->resolveUser($nama, $users);
            if ($nama !== '' && !$user) {
                $unmatched[$nama] = true;
            }
            // nama disamakan ke tabel users bila user-nya ketemu
            $namaSimpan = $user ? $user->name : ($nama !== '' ? $nama : '-');

            $exists = ChangeRequest::where('permintaan_fitur', 'SIMRS')
                ->where('deskripsi', $deskripsi)
                ->whereDate('created_at', $createdAt->toDateString())
                ->exists();
            if ($exists) {
                $skipped++;
                continue;
            }

            $status = trim((string) $status);
            if (!in_array($status, $allowedStatus, true)) {
                $status = 'Open';
            }

            $tiket = ltrim(trim((string) $tiket), '#');
            $mandays = is_numeric($mandays) ? (float) $mandays : null;
            $catatan = trim((string) $catatan);
            if ($catatan === '' || $catatan === '--') {
                $catatan = null;
            }

            ChangeRequest::create([
                'user_id'           => $user?->id,
                'nama'              => $namaSimpan,
                'jabatan'           => $user->jabatan ?? '-',
                'jabatan_id'        => $user->jabatan_id ?? null,
                'permintaan_fitur'  => 'SIMRS',
                'deskripsi'         => $deskripsi,
                'status_pengerjaan' => $status,
                'no_tiket'          => $tiket !== '' ? $tiket : null,
                'mandays'           => $mandays,
                'catatan'           => $catatan,
                'created_at'        => $createdAt,
                'updated_at'        => $createdAt,
            ]);
            $created++;
        }

        $this->command->info("SIMRS: {$created} dibuat, {$skipped} dilewati (sudah ada, tidak diubah).");
        if (!empty($unmatched)) {
            $this->command->warn('Nama tanpa user cocok (' . count($unmatched) . '): ' . implode(', ', array_keys($unmatched)));
        }
    }

    private function parseDate($value): Carbon
    {
        if (is_numeric($value) && (float) $value > 20000) {
            return Carbon::instance(ExcelDate::excelToDateTimeObject((float) $value));
        }
        try {
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return now();
        }
    }

    private function norm(string $raw): string
    {
        return strtolower(trim(preg_replace('/\s+/', ' ', str_replace('.', ' ', $raw))));
    }

    private function resolveUser(string $raw, $users)
    {
        $norm = $this->norm($raw);
        if ($norm === '' || $norm === '-') {
            return null;
        }
        foreach ($users as $u) {
            if ($this->norm($u->name ?? '') === $norm || $this->norm($u->username ?? '') === $norm) {
                return $u; // cocok persis
            }
        }
        $first = explode(' ', $norm)[0];
        $candidates = [];
        foreach ($users as $u) {
            $uname = $this->norm($u->name ?? '');
            if (explode(' ', $uname)[0] === $first || $this->norm($u->username ?? '') === $first) {
                $candidates[] = $u;
            }
        }
        return count($candidates) === 1 ? $candidates[0] : null;
    }
}
