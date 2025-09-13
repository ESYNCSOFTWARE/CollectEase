<?php

declare(strict_types=1);

namespace App\Domains\RoleStatus\Controllers;

use App\Domains\RoleStatus\DataObjects\RoleStatusData;
use App\Domains\RoleStatus\RoleStatusQueries;
use App\Domains\Role\RoleQueries;
use App\Domains\Status\StatusQueries;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;
use Inertia\Response;

class RoleStatusController extends Controller
{
    public function __construct(
        protected RoleStatusQueries $roleStatusQueries
    ) {}

    public function fetchRoleStatuses(Request $request): array
    {
        $filterData = [
            'search_text' => $request->get('search_text'),
            'sort_by' => $request->get('sort_by'),
            'sort_direction' => $request->get('sort_direction'),
            'per_page' => $request->get('per_page'),
        ];

        $lengthAwarePaginator = $this->roleStatusQueries->listQuery($filterData);

        return [
            'total_records' => $lengthAwarePaginator->total(),
            'data' => $lengthAwarePaginator->getCollection(),
        ];
    }

    public function index()
    {
        return Inertia::render('role_statuses/Index');
    }

    public function create(): Response
    {
        $roles = resolve(RoleQueries::class);
        $statuses = resolve(StatusQueries::class);


        return Inertia::render('role_statuses/Manage', [
            'roles' => $roles->roleList(),
            'statuses' => $statuses->statusList(),
        ]);
    }

    public function store(RoleStatusData $roleStatusData): RedirectResponse
    {
        $this->roleStatusQueries->addNew($roleStatusData);

        return to_route('role_statuses.index')->with('success', 'The role status has been added successfully.');
    }

    public function edit(int $roleStatusId): Response
    {
        return Inertia::render('role_statuses/Manage', [
            'roleStatus' => $this->roleStatusQueries->getById($roleStatusId),
        ]);
    }

    public function update(RoleStatusData $roleStatusData, int $roleStatusId): RedirectResponse
    {
        $this->roleStatusQueries->update($roleStatusData, $roleStatusId);

        return to_route('role_statuses.index')->with('success', 'The role status has been updated successfully.');
    }

    public function delete(int $roleStatusId)
    {
        $this->roleStatusQueries->delete($roleStatusId);

        return to_route('role_statuses.index')->with('success', 'The role status has been deleted successfully.');
    }


    public function canView(int $roleStatusId): RedirectResponse
    {
        $this->roleStatusQueries->canView($roleStatusId);

        return to_route('role_statuses.index')->with('success', 'The role status can view has been updated successfully.');
    }

    public function canUpdate(int $roleStatusId): RedirectResponse
    {
        $this->roleStatusQueries->canUpdate($roleStatusId);

        return to_route('role_statuses.index')->with('success', 'The role status can update has been updated successfully.');
    }
}
