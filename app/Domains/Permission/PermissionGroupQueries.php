<?php

declare(strict_types=1);

namespace App\Domains\Permission;

use App\Domains\Permission\DataObjects\PermissionGroupData;
use App\Domains\Permission\Models\PermissionGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PermissionGroupQueries
{
    public function listQuery(array $filterData): LengthAwarePaginator
    {
        return $this->getPermissionGroupsQuery($filterData)->paginate($filterData['per_page']);
    }

    private function getPermissionGroupsQuery(array $filterData): Builder
    {
        return PermissionGroup::query()
            ->select('id', 'name', 'description')
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

    public function addNew(PermissionGroupData $permissionGroupData): PermissionGroup
    {
        $data = $permissionGroupData->all();

        return PermissionGroup::query()->create($data);
    }

    public function getById(int $permissionGroupId): PermissionGroup
    {
        return PermissionGroup::query()->select('id', 'name', 'description')
            ->findOrFail($permissionGroupId);
    }

    public function update(PermissionGroupData $permissionGroupData, int $permissionGroupId): void
    {
        $permissionGroup = $this->getById($permissionGroupId);
        $permissionGroup->update($permissionGroupData->all());
    }

    public function delete(int $permissionGroupId): void
    {
        $permissionGroup = $this->getById($permissionGroupId);
        $permissionGroup->delete();

    }

    public function permissionGroupList(): Collection
    {
        return PermissionGroup::query()->select('id', 'name')->get();
    }

    public function assignPermissionToGroup(int $permissionGroupId): array
    {
        $permissionGroup = PermissionGroup::with('permissions')
            ->where('id', $permissionGroupId)
            ->first();

        return [
            'permission_ids' => $permissionGroup->permissions,
            'permission_group_ids' => $permissionGroup,
        ];
    }
}
