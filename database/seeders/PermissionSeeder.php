<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\PermissionRule;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // CLEAN TABLE
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        PermissionRule::truncate();
        Permission::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Akses berbasis akun (email), sumber tunggal: config/it_access.php
        $itAdminRules = array_map(fn ($email) => ['name' => $email], config('it_access.it'));
        $superadminRules = array_map(fn ($email) => ['name' => $email], config('it_access.superadmin'));

        // SUPERADMIN (FULL AKSES) - hanya sammuel
        $super = Permission::create([
            'menu' => '*',
            'action' => '*'
        ]);
        $super->rules()->createMany($superadminRules);

        // DAFTAR SEMUA MENU
        $allMenus = [
            'komplain_ipsrs',
            'kesehatan_lingkungan',
            'outsourcing_vendor',
            'reservasi_ruangan',
            'reservasi_kendaraan',
            'desain_grafis',
            'kecelakaan_kerja',
            'kesiapan_ambulance',
            'mutu',
            'manajemen_risiko',
            'pelaporan_ikp',
            'pengajuan_dokumen',
            'bank_ilmu',
            'laporan_perilaku',
            'peminjaman_aset',
            'pengembalian_aset',
            'laporan_aset_rusak',
            'pemindahan_aset',
            'helpdesk',
        ];

        // SEMUA USER (HANYA CREATE DAN READ)
        $basicActions = ['create', 'read'];

        foreach ($allMenus as $menu) {
            foreach ($basicActions as $action) {
                Permission::create([
                    'menu' => $menu,
                    'action' => $action
                ])->rules()->create([
                    'unit' => null,
                    'jabatan' => null,
                    'name' => null
                ]);
            }
        }

        // READ ONLY MENU (TANPA CREATE, UPDATE, DELETE)
        $readOnlyMenus = [
            'bank_spo',
            'utw',
            'peraturan_perusahaan',
            'surat_keputusan',
            'mandatory_training',
            'komite_medik',
        ];

        foreach ($readOnlyMenus as $menu) {
            Permission::create([
                'menu' => $menu,
                'action' => 'read'
            ])->rules()->create([
                'unit' => null,
                'jabatan' => null,
                'name' => null
            ]);
        }

        // ADMIN HELP DESK - FULL AKSES KHUSUS 5 AKUN IT (create/read untuk semua user sudah di atas)
        $fullActions = ['create', 'read', 'update', 'delete'];

        foreach (['update', 'delete', 'manage'] as $action) {
            Permission::create([
                'menu' => 'helpdesk',
                'action' => $action
            ])->rules()->createMany($itAdminRules);
        }

        // KNOWLEDGE BASE - KELOLA DRAFT & ARTIKEL MILIK ORANG LAIN (5 AKUN IT)
        foreach ($fullActions as $action) {
            Permission::create([
                'menu' => 'knowledge_base',
                'action' => $action
            ])->rules()->createMany($itAdminRules);
        }

        // REPORT - READ ONLY UNTUK 5 AKUN IT
        Permission::create([
            'menu' => 'reports',
            'action' => 'read'
        ])->rules()->createMany($itAdminRules);

        // MUTU - FULL AKSES UNTUK MENU TERTENTU
        $mutuMenus = [
            'mutu',
            'bank_spo',
            'manajemen_risiko',
            'pelaporan_ikp',
            'pengajuan_dokumen',
            'bank_ilmu',
            'laporan_perilaku'
        ];

        $mutuRules = [
            ['jabatan' => 'ketua mutu', 'name' => 'pupu.pujiawati'],
            ['jabatan' => 'staf mutu', 'name' => 'indah.pertiwi'],
        ];

        foreach ($mutuMenus as $menu) {
            foreach ($fullActions as $action) {
                $permission = Permission::create([
                    'menu' => $menu,
                    'action' => $action
                ]);
                $permission->rules()->createMany($mutuRules);
            }
        }

        // SDM - FULL AKSES UNTUK MENU TERTENTU
        $sdmMenus = [
            'utw',
            'peraturan_perusahaan',
            'surat_keputusan',
            'mandatory_training'
        ];

        $sdmRules = [
            ['jabatan' => 'manajer sdm dan hukum', 'name' => 'jatu.priya'],
            ['jabatan' => 'spv sdm dan hukum', 'name' => 'ruri.kemala'],
            ['jabatan' => 'staf sdm', 'name' => 'novia.firstania'],
            ['jabatan' => 'staf diklat dan pengembangan', 'name' => 'rifaldi.zakhari'],
            ['jabatan' => 'staf hukum dan hubungan industrial', 'name' => 'muhamad.fajar'],
        ];

        foreach ($sdmMenus as $menu) {
            foreach ($fullActions as $action) {
                $permission = Permission::create([
                    'menu' => $menu,
                    'action' => $action
                ]);
                $permission->rules()->createMany($sdmRules);
            }
        }

        // KOMITE MEDIK - FULL AKSES (TIM KHUSUS)
        $komiteRules = [
            ['unit' => 'komite medik', 'jabatan' => 'staf komite medik', 'name' => 'meliana.fatimah']
        ];

        foreach ($fullActions as $action) {
            $permission = Permission::create([
                'menu' => 'komite_medik',
                'action' => $action
            ]);
            $permission->rules()->createMany($komiteRules);
        }

        // PERMISSION MANAGEMENT - HANYA UNTUK SAMMUEL
        $permManagementActions = ['read', 'create', 'update', 'delete'];

        foreach ($permManagementActions as $action) {
            $permission = Permission::create([
                'menu' => 'permissions',
                'action' => $action
            ]);

            $permission->rules()->createMany($superadminRules);
        }

        // MENU KHUSUS TIM IT (5 akun)
        $superOnlyMenus = ['toner', 'visitasi', 'peminjaman', 'dokumen_it', 'hardware'];

        foreach ($superOnlyMenus as $menu) {
            foreach ($fullActions as $action) {
                $permission = Permission::create([
                    'menu' => $menu,
                    'action' => $action
                ]);
                $permission->rules()->createMany($itAdminRules);
            }
        }

        // CHANGE REQUEST - FULL AKSES 5 AKUN IT (manager hanya lihat, requester via approval mapping)
        foreach ($fullActions as $action) {
            $permission = Permission::create([
                'menu' => 'change_request',
                'action' => $action
            ]);
            $permission->rules()->createMany($itAdminRules);
        }
    }
}
