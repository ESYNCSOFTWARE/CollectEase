<?php

declare(strict_types=1);

namespace App\Domains\Status\Controllers;

use App\Domains\Status\DataObjects\StatusData;
use App\Domains\Status\Resources\StatusListResource;
use App\Domains\Status\Services\StatusService;
use App\Domains\Status\StatusQueries;
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

        return Inertia::render('statuses/Manage');
    }

    public function store(StatusData $statusData): RedirectResponse
    {
        $this->statusQueries->addNew($statusData);

        return to_route('statuses.index')->with('success', 'The status has been added successfully.');
    }

    public function edit(int $statusId): Response
    {
        return Inertia::render('statuses/Manage', [
            'status' => $this->statusQueries->getById($statusId),
        ]);
    }

    public function update(StatusData $statusData, int $statusId): RedirectResponse
    {
        $statusId = $this->statusQueries->getById($statusId);
        $this->statusQueries->update($statusData, $statusId);

        return to_route('statuses.index')->with('success', 'The status has been updated successfully.');
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