<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\PermissionRule;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::with('rules')->orderBy('menu')->get();
        $users = \App\Models\User::orderBy('name')->get(['id', 'name', 'username', 'unit', 'jabatan']);
        $allMenus = config('permissions.menus', []);
        return view('layouts.permissions.index', compact('permissions', 'users', 'allMenus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'menu' => 'required|string|max:255',
            'action' => 'required|in:read,create,update,delete',
            'rules' => 'nullable|array'
        ]);

        $exists = Permission::where('menu', $request->menu)
            ->where('action', $request->action)
            ->exists();

        if ($exists) {
            return redirect()->route('permissions.index')->with('error', 'Permission untuk menu ' . $request->menu . ' dengan action ' . $request->action . ' sudah ada!');
        }

        $permission = Permission::create([
            'menu' => $request->menu,
            'action' => $request->action
        ]);

        if ($request->has('rules')) {
            foreach ($request->rules as $rule) {
                if (!empty($rule['unit']) || !empty($rule['jabatan']) || !empty($rule['name'])) {
                    $permission->rules()->create($this->normalizeRule($rule));
                }
            }
        }

        return redirect(route('permissions.index') . '?saved=1')->with('success', 'Permission berhasil ditambahkan!');
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'menu' => 'required|string|max:255',
            'action' => 'required|in:read,create,update,delete',
        ]);

        $permission->update([
            'menu' => $request->menu,
            'action' => $request->action
        ]);

        return redirect(route('permissions.index') . '?updated=1')->with('success', 'Permission berhasil diupdate!');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect(route('permissions.index') . '?deleted=1')->with('success', 'Permission berhasil dihapus!');
    }

    public function addRule(Request $request, Permission $permission)
    {
        $request->validate([
            'unit' => 'nullable|string',
            'jabatan' => 'nullable|string',
            'name' => 'nullable|string'
        ]);

        $permission->rules()->create($this->normalizeRule($request->only(['unit', 'jabatan', 'name'])));

        return redirect(route('permissions.index') . '?saved=1')->with('success', 'Rule berhasil ditambahkan!');
    }

    public function updateRule(Request $request, PermissionRule $rule)
    {
        $request->validate([
            'unit'    => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'name'    => 'nullable|string|max:255',
        ]);

        $data = $this->normalizeRule($request->only(['unit', 'jabatan', 'name']));

        if (!$data['unit'] && !$data['jabatan'] && !$data['name']) {
            return response()->json(['error' => 'Minimal satu field harus diisi.'], 422);
        }

        $rule->update($data);
        return response()->json(['success' => true, 'rule' => $rule]);
    }

    /* Field kosong harus null, bukan string kosong: rule dengan unit='' tidak akan pernah cocok. */
    private function normalizeRule(array $rule): array
    {
        return array_map(fn ($value) => $value === '' ? null : $value, $rule);
    }

    public function deleteRule(PermissionRule $rule)
    {
        $rule->delete();
        return redirect(route('permissions.index') . '?deleted=1')->with('success', 'Rule berhasil dihapus!');
    }
}
