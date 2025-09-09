<?php

declare(strict_types=1);

namespace App\Domains\Role;

use App\Domains\Permission\Models\Permission;
use App\Domains\Permission\Models\PermissionGroup;
use App\Domains\Role\DataObjects\AssignGroupPermissionToRoleData;
use App\Domains\Role\DataObjects\AssignPermissionToRoleData;
use App\Domains\Role\DataObjects\RoleData;
use App\Domains\Role\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class RoleQueries
{
    public function listQuery(array $filterData): LengthAwarePaginator
    {
        return $this->getRolesQuery($filterData)->paginate($filterData['per_page']);
    }

    private function getRolesQuery(array $filterData): Builder
    {
        return Role::query()
            ->select('id', 'name')
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

    public function addNew(RoleData $roleData): Role
    {
        $data = $roleData->all();

        return Role::query()->create($data);
    }

    public function getById(int $roleId): Role
    {
        return Role::query()->select('id', 'name')
            ->findOrFail($roleId);
    }

    public function update(RoleData $roleData, int $roleId): void
    {
        $role = $this->getById($roleId);
        $role->update($roleData->all());
    }

    public function delete(int $roleId): void
    {
        $role = $this->getById($roleId);
        $role->delete();
    }

    public function roleList()
    {
        return Role::query()->select('id', 'name')->get();
    }

    public function assignPermissionToRole(int $roleId): array
    {
        $role = Role::with('permissions')
            ->where('id', $roleId)
            ->first();

        return [
            'permission_ids' => $role->permissions,
            'role_ids' => $role,
        ];
    }

    public function whereIn(array $roleIds)
    {
        return Role::query()
            ->whereIn('id', $roleIds)
            ->pluck('id', 'name');
    }

    public function rolePermission(array $filterData): LengthAwarePaginator
    {
        return $this->getRolePermissionQuery($filterData)->paginate($filterData['per_page']);
    }

    private function getRolePermissionQuery(array $filterData): Builder
    {
        return Permission::query()
            ->select('id', 'name', 'display_name')
            ->when(empty($filterData['search_text']), function ($query) use ($filterData): void {
                $query->whereHas('roles', function ($query) use ($filterData): void {
                    $query->where('id', $filterData['role_id']);
                });
                $query->selectRaw(' 1 as has_permission');
            })
            ->when($filterData['search_text'], function ($query) use ($filterData): void {
                $query->where('name', 'LIKE', '%'.$filterData['search_text'].'%');
                $query->orwhere('display_name', 'LIKE', '%'.$filterData['search_text'].'%');
            })
            ->when($filterData['sort_by'], function ($query) use ($filterData): void {
                $query->orderBy($filterData['sort_by'], $filterData['sort_direction'] ?? 'asc');
            }, function ($query): void {
                $query->orderBy('id', 'desc');
            });
    }

    public function attachGroupPermissionToRole(AssignGroupPermissionToRoleData $assignGroupPermissionToRoleData): void
    {
        $role = $this->getById($assignGroupPermissionToRoleData->role_id);

        $permissionGroups = PermissionGroup::with('permissions')
            ->where('id', $assignGroupPermissionToRoleData->permission_group_id)
            ->get();

        $groupPermissionIds = $permissionGroups->flatMap(function ($group) {
            return $group->permissions->pluck('id');
        })->unique()->toArray();

        $role->permissions()->syncWithoutDetaching($groupPermissionIds);
    }

    public function detachGroupPermissionToRole(AssignGroupPermissionToRoleData $assignGroupPermissionToRoleData): void
    {
        $role = $this->getById($assignGroupPermissionToRoleData->role_id);

        $permissionGroups = PermissionGroup::with('permissions')
            ->where('id', $assignGroupPermissionToRoleData->permission_group_id)
            ->get();

        $groupPermissionIds = $permissionGroups->flatMap(function ($group) {
            return $group->permissions->pluck('id');
        })->unique()->toArray();

        $role->permissions()->detach($groupPermissionIds);
    }

    public function attachPermissionToRole(AssignPermissionToRoleData $assignPermissionToRoleData): void
    {
        $role = $this->getById($assignPermissionToRoleData->role_id);

        $permissions = (array) $assignPermissionToRoleData->permission_id;

        if ($assignPermissionToRoleData->is_permission) {
            $role->permissions()->syncWithoutDetaching($permissions);
        } else {
            $role->permissions()->detach($permissions);
        }
    }

    public function groupPermissions(int $roleId): array
    {

        $role = $this->getById($roleId);
        $permissions = $role->permissions()->with('permissionGroups')->get();

        return $permissions->flatMap(function ($permission) {
            return $permission->permissionGroups;
        })->unique('id')->values()->map(function ($group): array {
            return [
                'id' => $group['id'],
                'name' => $group['name'],
                'description' => $group['description'],
            ];
        })->toArray();
    }
}
