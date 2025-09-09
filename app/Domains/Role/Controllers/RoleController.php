<?php

declare(strict_types=1);

namespace App\Domains\Role\Controllers;

use App\Domains\Permission\PermissionGroupQueries;
use App\Domains\Role\DataObjects\AssignGroupPermissionToRoleData;
use App\Domains\Role\DataObjects\AssignPermissionToRoleData;
use App\Domains\Role\DataObjects\RoleData;
use App\Domains\Role\RoleQueries;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;
use Inertia\Response;

class RoleController extends Controller
{
    public function __construct(
        protected RoleQueries $roleQueries
    ) {}

    public function fetchRoles(Request $request): array
    {
        $filterData = [
            'search_text' => $request->get('search_text'),
            'sort_by' => $request->get('sort_by'),
            'sort_direction' => $request->get('sort_direction'),
            'per_page' => $request->get('per_page'),
        ];

        $lengthAwarePaginator = $this->roleQueries->listQuery($filterData);

        return [
            'total_records' => $lengthAwarePaginator->total(),
            'data' => $lengthAwarePaginator->getCollection(),
        ];
    }

    public function index()
    {
        return Inertia::render('roles/Index');
    }

    public function create(): Response
    {
        return Inertia::render('roles/Manage');
    }

    public function store(RoleData $roleData): RedirectResponse
    {
        $this->roleQueries->addNew($roleData);

        return to_route('roles.index')->with('success', 'The role has been added successfully.');
    }

    public function edit(int $roleId): Response
    {
        return Inertia::render('roles/Manage', [
            'role' => $this->roleQueries->getById($roleId),
        ]);
    }

    public function update(RoleData $roleData, int $roleId): RedirectResponse
    {
        $this->roleQueries->update($roleData, $roleId);

        return to_route('roles.index')->with('success', 'The role has been updated successfully.');
    }

    public function delete(int $roleId)
    {
        $role = $this->roleQueries->getById($roleId);

        if ($role->users()->count() > 0) {
            return to_route('roles.index')->with('error', 'The role cannot be deleted as it is assigned to users.');
        }

        $this->roleQueries->delete($roleId);

        return to_route('roles.index')->with('success', 'The role has been deleted successfully.');
    }

    public function createPermissionToRole(int $roleId): Response
    {
        $permissionGroupQueries = App::make(PermissionGroupQueries::class);

        return Inertia::render('roles/PermissionAssignToRole', [
            'role' => $this->roleQueries->getById($roleId),
            'permission_groups' => $permissionGroupQueries->permissionGroupList(),
            'permission_group_ids' => $this->roleQueries->groupPermissions($roleId),

        ]);
    }

    public function detachGroupPermissionFromRole(AssignGroupPermissionToRoleData $assignGroupPermissionToRoleData): array
    {
        $this->roleQueries->detachGroupPermissionToRole($assignGroupPermissionToRoleData);

        return ['message' => 'Group permission removed from role successfully'];
    }

    public function attachGroupPermissionToRole(AssignGroupPermissionToRoleData $assignGroupPermissionToRoleData): array
    {
        $this->roleQueries->attachGroupPermissionToRole($assignGroupPermissionToRoleData);

        return ['message' => 'Group permission assigned to role successfully'];
    }

    public function assignPermissionToRole(AssignPermissionToRoleData $assignPermissionToRoleData): void
    {
        $this->roleQueries->attachPermissionToRole($assignPermissionToRoleData);
    }

    public function rolePermission(Request $request): array
    {
        $filterData = [
            'search_text' => $request->get('search_text'),
            'role_id' => $request->get('role_id'),
            'sort_by' => $request->get('sort_by'),
            'sort_direction' => $request->get('sort_direction'),
            'per_page' => $request->get('per_page'),
        ];

        $lengthAwarePaginator = $this->roleQueries->rolePermission($filterData);

        return [
            'total_records' => $lengthAwarePaginator->total(),
            'data' => $lengthAwarePaginator->getCollection(),
        ];
    }
}
