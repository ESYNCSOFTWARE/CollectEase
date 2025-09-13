<?php

declare(strict_types=1);

namespace App\Domains\Status\Controllers;

use App\Domains\Status\DataObjects\StatusData;
use App\Domains\Status\Resources\StatusListResource;
use App\Domains\Status\Services\StatusService;
use App\Domains\Status\StatusQueries;
use App\Domains\Role\RoleQueries;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;
use Inertia\Response;

class StatusController extends Controller
{
    public function __construct(
        protected StatusQueries $statusQueries
    ) {
    }

    public function fetchStatuses(Request $request): array
    {
        $filterData = [
            'search_text' => $request->get('search_text'),
            'sort_by' => $request->get('sort_by'),
            'sort_direction' => $request->get('sort_direction'),
            'per_page' => $request->get('per_page'),
        ];

        $lengthAwarePaginator = $this->statusQueries->listQuery($filterData);

        return [
            'total_records' => $lengthAwarePaginator->total(),
            'data' => StatusListResource::collection($lengthAwarePaginator->getCollection()),
        ];
    }

    public function index()
    {
        return Inertia::render('statuses/Index');
    }

    public function create(): Response
    {
        $roleQueries = App::make(RoleQueries::class);
        return Inertia::render('statuses/Manage', [
            'roles' => $roleQueries->roleList(),
        ]);
    }

    public function store(Request $request, StatusData $statusData): RedirectResponse
    {
        $roles = collect($request->input('role_ids', []))
            ->mapWithKeys(function ($role) {
                if (is_array($role)) {
                    return [
                        $role['id'] => [
                            'can_view' => $role['can_view'] ?? false,
                            'can_update' => $role['can_update'] ?? false,
                        ],
                    ];
                }

                return [
                    $role => [
                        'can_view' => false,
                        'can_update' => false,
                    ],
                ];
            })
            ->all();

        $status = $this->statusQueries->addNew($statusData, $roles);

        return to_route('statuses.index', [$status->load('roles')])
            ->with('success', 'The status has been added successfully.');
    }


    public function edit(int $statusId): Response
    {
        $status = $this->statusQueries->getById($statusId);

        $status->load('roles');
        $roleQueries = App::make(RoleQueries::class);

        return Inertia::render('statuses/Manage', [
            'status' => $status,
            'roles' => $roleQueries->roleList(),
        ]);
    }



    public function update(Request $request, StatusData $statusData, int $statusId)
    {
        $status = $this->statusQueries->getById($statusId);

        $roleData = collect($request->input('role_ids', []))->mapWithKeys(function ($role) {
            $id = is_array($role) ? $role['id'] : $role;
            $pivot = [
                'can_view' => isset($role['can_view']) ? (bool) $role['can_view'] : false,
                'can_update' => isset($role['can_update']) ? (bool) $role['can_update'] : false,
            ];
            return [$id => $pivot];
        })->all();

        $this->statusQueries->update($statusData, $status, $roleData);

        return to_route('statuses.index')
            ->with('success', 'The status has been updated successfully.');
    }


    public function delete(int $statusId)
    {
        $this->statusQueries->getById($statusId);

        $this->statusQueries->delete($statusId);

        return to_route('statuses.index')->with('success', 'The status has been deleted successfully.');
    }

    public function getCommonResources(): array
    {
        $statusService = App::make(StatusService::class);

        return $statusService->getCommonRecords();
    }

    public function unauthorize()
    {
        return Inertia::render('Auth/Unauthorize');
    }

    public function status(int $statusId): RedirectResponse
    {
        $this->statusQueries->status($statusId);

        return to_route('statuses.index')->with('success', 'The isDefault has been updated successfully.');
    }

    public function createStatusToRole(int $statusId): Response
    {
        $statusQueries = App::make(statusQueries::class);

        return Inertia::render('statuses/StatusAssignToRole', [
            'status' => $this->statusQueries->getById($statusId),


        ]);
    }


}