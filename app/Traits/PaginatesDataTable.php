<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/*
 * Pagination + sorting untuk endpoint ajax DataTables.
 * Perilaku identik dengan kode inline yang sebelumnya diulang di tiap controller.
 */
trait PaginatesDataTable
{
    /**
     * @param  array<int, string>  $columns  daftar kolom urut per index DataTable
     * @param  array<int, array{0: string, 1: string}>  $extraOrders  urutan tambahan saat tidak ada request order
     */
    protected function datatablePage(
        Request $request,
        Builder $query,
        array $columns,
        string $defaultColumn = 'created_at',
        string $defaultDir = 'desc',
        array $extraOrders = [],
    ): Collection {
        if ($request->has('order')) {
            $orderColumn = $columns[$request->order[0]['column']] ?? $defaultColumn;
            $orderDir = $request->order[0]['dir'] ?? $defaultDir;
            $query->orderBy($orderColumn, $orderDir);
        } else {
            $query->orderBy($defaultColumn, $defaultDir);

            foreach ($extraOrders as [$column, $dir]) {
                $query->orderBy($column, $dir);
            }
        }

        return $query->skip($request->start ?? 0)->take($request->length ?? 10)->get();
    }
}
