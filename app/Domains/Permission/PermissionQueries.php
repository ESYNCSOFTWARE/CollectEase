<?php

declare(strict_types=1);

namespace App\Domains\Permission;

use App\Domains\Permission\DataObjects\PermissionData;
use App\Domains\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PermissionQueries
{
    public function listQuery(array $filterData): LengthAwarePaginator
    {
        return $this->getPermissionsQuery($filterData)->paginate($filterData['per_page']);
    }

    private function getPermissionsQuery(array $filterData): Builder
    {
        return Permission::query()
            ->select('id', 'name', 'display_name', 'description')
            ->when($filterData['search_text'], function ($query) use ($filterData): void {
                $query->where(function ($query) use ($filterData): void {
                    $query
                        ->whereAny(['name'], 'LIKE', '%'.$filterData['search_text'].'%');
                });
            })
            ->when($filterData['sort_by'], function ($query) use ($filterData): void {
                $query->orderBy($filterData['sort_by'], $filterData['sort_direction']);
            }, function ($query): void {
                $query->orderBy('id', 'desc');
            });
    }

    public function addNew(PermissionData $permissionData): Permission
    {
        $data = $permissionData->all();

        return Permission::query()->create($data);
    }

    public function getById(int $permissionId): Permission
    {
        return Permission::query()->select('id', 'name', 'description', 'display_name')
            ->findOrFail($permissionId);
    }

    public function update(PermissionData $permissionData, int $permissionId): void
    {
        $permission = $this->getById($permissionId);
        $permission->update($permissionData->all());
    }

    public function delete(int $permissionId): void
    {
        $permission = $this->getById($permissionId);
        $permission->delete();

    }

    public function permissionList(): Collection
    {
        return Permission::query()->select('id', 'name', 'display_name')->get();
    }
}
