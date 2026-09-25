<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class ItAdminAccessSeeder extends Seeder
{
    private const ACTIONS = ['create', 'read', 'update', 'delete'];

    // menu => [actions, mode]
    private const IT_TARGETS = [
        'helpdesk' => [['update', 'delete', 'manage'], 'replace'],
        'reports' => [['read'], 'replace'],
        'knowledge_base' => [self::ACTIONS, 'replace'],
        'hardware' => [self::ACTIONS, 'replace'],
        'toner' => [self::ACTIONS, 'replace'],
        'visitasi' => [self::ACTIONS, 'replace'],
        'peminjaman' => [self::ACTIONS, 'replace'],
        'dokumen_it' => [self::ACTIONS, 'replace'],
        'change_request' => [self::ACTIONS, 'surgical'],
    ];

    // menu => [actions, mode]
    private const SUPERADMIN_TARGETS = [
        'permissions' => [self::ACTIONS, 'replace'],
        '*' => [['*'], 'replace'],
    ];

    public function run(): void
    {
        $this->sync(self::IT_TARGETS, 'it');
        $this->sync(self::SUPERADMIN_TARGETS, 'superadmin');

        $this->command->info('Akses berbasis email: ' . count(config('it_access.it')) . ' akun IT, ' . count(config('it_access.superadmin')) . ' superadmin.');
    }

    private function sync(array $targets, string $configKey): void
    {
        $emails = array_map('strtolower', config('it_access.' . $configKey, []));

        foreach ($targets as $menu => [$actions, $mode]) {
            $this->dropUnitRules($menu);

            foreach ($actions as $action) {
                $permission = Permission::firstOrCreate(['menu' => $menu, 'action' => $action]);

                $query = $permission->rules();
                if ($mode === 'surgical') {
                    // buang rule lama berbasis unit maupun email lama, rule jabatan tetap utuh
                    $query->where(fn ($q) => $q->whereNotNull('unit')->orWhereNotNull('name'));
                }
                $query->delete();

                $permission->rules()->createMany(array_map(fn ($email) => ['name' => $email], $emails));
            }
        }
    }

    // Bersihkan sisa rule hardcoded unit pada semua action menu tsb (mis. helpdesk create/read)
    private function dropUnitRules(string $menu): void
    {
        Permission::where('menu', $menu)
            ->with('rules')
            ->get()
            ->each(fn ($permission) => $permission->rules()->whereNotNull('unit')->delete());
    }
}
