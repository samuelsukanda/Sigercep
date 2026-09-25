<?php

namespace Tests\Feature;

use App\Helpers\PermissionHelper;
use App\Http\Controllers\PermissionController;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ItAdminAccessTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Migrasi repo memakai SQL MySQL mentah, jadi tabel dibuat seperlunya di sqlite.
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('username')->nullable();
                $table->string('name')->nullable();
                $table->string('unit')->nullable();
                $table->string('jabatan')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->id();
                $table->string('menu');
                $table->string('action');
                $table->timestamps();
            });

            Schema::create('permission_rules', function (Blueprint $table) {
                $table->id();
                $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
                $table->string('unit')->nullable();
                $table->string('jabatan')->nullable();
                $table->string('name')->nullable();
                $table->timestamps();
            });
        }
    }

    private function makeUser(int $id, string $name, string $username, string $unit = 'Teknologi dan Informasi'): User
    {
        DB::table('users')->insert([
            'id' => $id,
            'name' => $name,
            'username' => $username,
            'unit' => $unit,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return User::find($id);
    }

    public function test_email_rule_grants_access_only_to_that_account(): void
    {
        Permission::create(['menu' => 'knowledge_base', 'action' => 'update'])
            ->rules()->create(['name' => 'sammuel@rs-hamori.co.id']);

        $this->actingAs($this->makeUser(1, 'sammuel', 'sammuel@rs-hamori.co.id'));
        $this->assertTrue(PermissionHelper::canAccess('knowledge_base', 'update'));

        $this->actingAs($this->makeUser(2, 'iyan.hermawan', 'iyan.hermawan@rs-hamori.co.id'));
        $this->assertFalse(PermissionHelper::canAccess('knowledge_base', 'update'));
    }

    public function test_open_rule_still_grants_every_user(): void
    {
        Permission::create(['menu' => 'helpdesk', 'action' => 'create'])
            ->rules()->create(['unit' => null, 'jabatan' => null, 'name' => null]);

        $this->actingAs($this->makeUser(3, 'Annisa Nur', 'annisa.nur@rs-hamori.co.id', 'Penagihan'));
        $this->assertTrue(PermissionHelper::canAccess('helpdesk', 'create'));
    }

    public function test_unit_alone_no_longer_grants_it_access(): void
    {
        $outsider = $this->makeUser(4, 'Budi Santoso', 'budi.santoso@rs-hamori.co.id', 'Teknologi dan Informasi');

        $this->assertFalse(PermissionHelper::isIt($outsider));
        $this->assertFalse(PermissionHelper::canManageApprovalMapping($outsider));
        $this->assertFalse(PermissionHelper::isSuperadmin($outsider));
    }

    public function test_it_accounts_are_recognised_by_email_only(): void
    {
        foreach (config('it_access.it') as $index => $email) {
            $this->assertTrue(
                PermissionHelper::isIt($this->makeUser(10 + $index, 'User ' . $index, $email, 'Unit Acak')),
                $email . ' harus dapat akses IT'
            );
        }
    }

    public function test_approval_mapping_limited_to_two_accounts(): void
    {
        foreach (config('it_access.approval_mapping') as $index => $email) {
            $this->assertTrue(PermissionHelper::canManageApprovalMapping($this->makeUser(30 + $index, 'AM ' . $index, $email)));
        }

        $this->assertFalse(PermissionHelper::canManageApprovalMapping($this->makeUser(40, 'Iyan', 'iyan.hermawan@rs-hamori.co.id')));
    }

    public function test_superadmin_limited_to_sammuel(): void
    {
        $this->assertTrue(PermissionHelper::isSuperadmin($this->makeUser(50, 'sammuel', 'sammuel@rs-hamori.co.id')));
        $this->assertFalse(PermissionHelper::isSuperadmin($this->makeUser(51, 'Deden Eka Nugraha', 'deden.eka@rs-hamori.co.id')));
    }

    public function test_it_account_can_manage_change_request_without_approval_mapping(): void
    {
        $this->actingAs($this->makeUser(60, 'Deden Eka Nugraha', 'deden.eka@rs-hamori.co.id', 'Teknologi dan Informasi'));

        $this->assertTrue(PermissionHelper::canManageChangeRequest());
    }

    public function test_email_only_rule_from_panel_still_matches_the_account(): void
    {
        $permission = Permission::create(['menu' => 'hardware', 'action' => 'update']);

        $request = Request::create('/permissions/' . $permission->id . '/add-rule', 'POST', [
            'name' => 'sammuel@rs-hamori.co.id',
            'unit' => '',
            'jabatan' => '',
        ]);

        (new PermissionController)->addRule($request, $permission);

        $rule = $permission->rules()->first();
        $this->assertNull($rule->unit, 'unit kosong harus jadi null, bukan string kosong');
        $this->assertNull($rule->jabatan, 'jabatan kosong harus jadi null, bukan string kosong');

        $this->actingAs($this->makeUser(70, 'Orang Lain', 'orang.lain@rs-hamori.co.id', 'Teknologi dan Informasi'));
        $this->assertFalse(PermissionHelper::canAccess('hardware', 'update'));

        $this->actingAs($this->makeUser(71, 'sammuel', 'sammuel@rs-hamori.co.id'));
        $this->assertTrue(PermissionHelper::canAccess('hardware', 'update'));
    }
}
