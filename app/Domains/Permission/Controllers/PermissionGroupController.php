<?php

declare(strict_types=1);

namespace App\Domains\Permission\Controllers;

use App\Domains\Permission\DataObjects\AssignPermissionToGroupData;
use App\Domains\Permission\DataObjects\PermissionGroupData;
use App\Domains\Permission\PermissionGroupQueries;
use App\Domains\Permission\PermissionQueries;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;
use Inertia\Response;

class PermissionGroupController extends Controller
{
    public function __construct(
        protected PermissionGroupQueries $permissionGroupQueries
    ) {}

    public function fetchPermissionGroups(Request $request): array
    {

        $filterData = [
            'search_text' => $request->get('search_text'),
            'sort_by' => $request->get('sort_by'),
            'sort_direction' => $request->get('sort_direction'),
            'per_page' => $request->get('per_page'),
        ];

        $lengthAwarePaginator = $this->permissionGroupQueries->listQuery($filterData);

        return [
            'total_records' => $lengthAwarePaginator->total(),
            'data' => $lengthAwarePaginator->getCollection(),
        ];
    }

    public function index()
    {
        return Inertia::render('permission_groups/Index');
    }

    public function create(): Response
    {
        return Inertia::render('permission_groups/Manage');
    }

    public function store(PermissionGroupData $permissionGroupData): RedirectResponse
    {
        $this->permissionGroupQueries->addNew($permissionGroupData);

        return to_route('permission_groups.index')->with('success', 'The permission group name has been added successfully.');
    }

    public function edit(int $permissionGroupId): Response
    {
        return Inertia::render('permission_groups/Manage', [
            'permission_groups' => $this->permissionGroupQueries->getById($permissionGroupId),
        ]);
    }

    public function update(PermissionGroupData $permissionGroupData, int $permissionGroupId): RedirectResponse
    {
        $this->permissionGroupQueries->update($permissionGroupData, $permissionGroupId);

        return to_route('permission_groups.index')->with('success', 'The permission group name has been updated successfully.');
    }

    public function delete(int $permissionGroupId)
    {
        $permissionGroup = $this->permissionGroupQueries->getById($permissionGroupId);

        if ($permissionGroup->permissions()->count() > 0) {
            return to_route('permission_groups.index')->with('error', 'The permission group cannot be deleted because it is associated with one or more permissions');
        }

        $this->permissionGroupQueries->delete($permissionGroupId);

        return to_route('permission_groups.index')->with('success', 'The permission group has been deleted successfully.');
    }

    public function createPermissionToGroup(int $permissionGroupId): Response
    {
        $permissionGroup = $this->permissionGroupQueries->getById($permissionGroupId);
        $permissionQueries = App::make(PermissionQueries::class);
        $permissions = $permissionGroup->permissions()->get();

        return Inertia::render('permission_groups/PermissionAssignToGroup', [
            'permission_groups' => $permissionGroup,
            'permissions' => $permissionQueries->permissionList(),
            'permission_ids' => $permissions,
        ]);
    }

    public function assignGroupPermission(AssignPermissionToGroupData $assignPermissionToGroupData, int $permissionGroupId): RedirectResponse
    {
        $permissionIds = array_column($assignPermissionToGroupData->permission_ids, 'id');
        $permissionGroup = $this->permissionGroupQueries->getById($permissionGroupId);

        $permissionGroup->permissions()->sync($permissionIds);

        return to_route('permission_groups.index')->with('success', 'The permission are assigned to groups successfully.');
    }
}
