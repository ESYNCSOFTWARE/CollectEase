<?php

declare(strict_types=1);

namespace App\Domains\Permission\Controllers;

use App\Domains\Permission\DataObjects\PermissionData;
use App\Domains\Permission\PermissionQueries;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PermissionController extends Controller
{
    public function __construct(
        protected PermissionQueries $permissionQueries
    ) {}

    public function fetchPermissions(Request $request): array
    {

        $filterData = [
            'search_text' => $request->get('search_text'),
            'sort_by' => $request->get('sort_by'),
            'sort_direction' => $request->get('sort_direction'),
            'per_page' => $request->get('per_page'),
        ];

        $lengthAwarePaginator = $this->permissionQueries->listQuery($filterData);

        return [
            'total_records' => $lengthAwarePaginator->total(),
            'data' => $lengthAwarePaginator->getCollection(),
        ];
    }

    public function index()
    {
        return Inertia::render('permissions/Index');
    }

    public function create(): Response
    {
        return Inertia::render('permissions/Manage');
    }

    public function store(PermissionData $permissionData): RedirectResponse
    {
        $this->permissionQueries->addNew($permissionData);

        return to_route('permissions.index')->with('success', 'The permission name has been added successfully.');
    }

    public function edit(int $permissionId): Response
    {
        return Inertia::render('permissions/Manage', [
            'permissions' => $this->permissionQueries->getById($permissionId),
        ]);
    }

    public function update(PermissionData $permissionData, int $permissionId): RedirectResponse
    {
        $this->permissionQueries->update($permissionData, $permissionId);

        return to_route('permissions.index')->with('success', 'The permission name has been updated successfully.');
    }

    public function delete(int $permissionId)
    {
        $permission = $this->permissionQueries->getById($permissionId);

        if ($permission->permissionGroups()->count() > 0) {
            return to_route('permissions.index')->with('error', 'The permission cannot be deleted because it is associated with one or more as permisison group or role');
        }

        $this->permissionQueries->delete($permissionId);

        return to_route('permissions.index')->with('success', 'The permission name has been deleted successfully.');
    }
}
