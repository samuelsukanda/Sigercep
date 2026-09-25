<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\Permission;
use App\Models\ApprovalMapping;
use App\Models\Setting;

class PermissionHelper
{
    /* Akun IT (5 orang) - kunci aksesfull: hardware, helpdesk, change request */
    public static function isIt($user = null)
    {
        return self::inList('it', $user);
    }

    /* Panel Approval Change Request - hanya 2 akun */
    public static function canManageApprovalMapping($user = null)
    {
        return self::inList('approval_mapping', $user);
    }

    /* Panel permission, user monitoring, daftar user - hanya sammuel */
    public static function isSuperadmin($user = null)
    {
        return self::inList('superadmin', $user);
    }

    private static function inList(string $key, $user = null)
    {
        $user = $user ?: Auth::user();
        if (!$user) return false;

        $username = strtolower(trim($user->username ?? ''));

        return $username !== '' && in_array($username, array_map('strtolower', config('it_access.' . $key, [])), true);
    }

    /* Akses modul Change Request: IT, user peminta (mapping per-user), approver (mapping/tahap 2) */
    public static function canManageChangeRequest($user = null)
    {
        $user = $user ?: Auth::user();
        if (!$user) return false;

        $jabatan = strtolower(trim($user->jabatan ?? ''));

        if (self::isIt($user)) return true;
        if (self::isStage2($user)) return true;

        // Cocokkan lewat user (mapping per-akun) dulu
        if (ApprovalMapping::where('requester_user_id', $user->id)
            ->orWhere('approver_user_id', $user->id)
            ->exists()) {
            return true;
        }

        // Lalu lewat jabatan_id (dari HRIS)
        if ($user->jabatan_id) {
            $byId = ApprovalMapping::where(function ($q) use ($user) {
                $q->where('requester_jabatan_id', $user->jabatan_id)
                    ->orWhere('approver_jabatan_id', $user->jabatan_id);
            })->exists();

            if ($byId) return true;
        }

        // Fallback ke teks (data lama / user belum ter-sync id)
        return ApprovalMapping::where(function ($q) use ($jabatan) {
            $q->whereRaw('LOWER(requester_jabatan) = ?', [$jabatan])
                ->orWhereRaw('LOWER(approver_jabatan) = ?', [$jabatan]);
        })->exists();
    }

    public static function isStage2($user = null)
    {
        $user = $user ?: Auth::user();
        if (!$user) return false;

        // User khusus terpilih dari panel (settings)
        $stage2UserId = Setting::get('stage2_user_id');
        if ($stage2UserId && $user->id == $stage2UserId) return true;

        $stage2Id = config('approvals.stage2_jabatan_id');
        if ($stage2Id && $user->jabatan_id == $stage2Id) return true;

        return strtolower(trim($user->jabatan ?? '')) === strtolower(trim(config('approvals.stage2_jabatan')));
    }

    public static function canAccess($menu, $action)
    {
        $user = Auth::user();
        if (!$user) return false;

        $name = strtolower(trim($user->name ?? ''));
        $username = strtolower(trim($user->username ?? ''));
        $unit = strtolower(trim($user->unit ?? ''));
        $jabatan = strtolower(trim($user->jabatan ?? ''));

        // SUPERADMIN (WILDCARD)
        $hasWildcard = Permission::where('menu', '*')
            ->where('action', '*')
            ->whereHas('rules', function ($q) use ($unit, $jabatan, $name, $username) {
                $q->where(function ($q2) use ($unit) {
                    $q2->whereNull('unit')
                        ->orWhereRaw('LOWER(unit) = ?', [$unit]);
                })->where(function ($q2) use ($jabatan) {
                    $q2->whereNull('jabatan')
                        ->orWhereRaw('LOWER(jabatan) = ?', [$jabatan]);
                })->where(function ($q2) use ($name, $username) {
                    $q2->whereNull('name')
                        ->orWhereRaw('LOWER(name) = ?', [$name])
                        ->orWhereRaw('LOWER(name) = ?', [$username]);
                });
            })
            ->exists();

        if ($hasWildcard) return true;

        // CEK EXACT PERMISSION
        $permission = Permission::where('menu', $menu)
            ->where('action', $action)
            ->first();

        if (!$permission) return false;

        return $permission->rules()
            ->where(function ($q) use ($unit, $jabatan, $name, $username) {
                $q->where(function ($q2) use ($unit) {
                    $q2->whereNull('unit')
                        ->orWhereRaw('LOWER(unit) = ?', [$unit]);
                })->where(function ($q2) use ($jabatan) {
                    $q2->whereNull('jabatan')
                        ->orWhereRaw('LOWER(jabatan) = ?', [$jabatan]);
                })->where(function ($q2) use ($name, $username) {
                    $q2->whereNull('name')
                        ->orWhereRaw('LOWER(name) = ?', [$name])
                        ->orWhereRaw('LOWER(name) = ?', [$username]);
                });
            })
            ->exists();
    }
}
