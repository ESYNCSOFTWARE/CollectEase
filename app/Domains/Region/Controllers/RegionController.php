<?php

declare(strict_types=1);

namespace App\Domains\Region\Controllers;

use App\Domains\Region\DataObjects\RegionData;
use App\Domains\Region\Resources\RegionListResource;
use App\Domains\Region\Services\RegionService;
use App\Domains\Region\RegionQueries;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;
use Inertia\Response;

class RegionController extends Controller
{
    public function __construct(
        protected RegionQueries $regionQueries
    ) {
    }

    public function fetchRegions(Request $request): array
    {
        $filterData = [
            'search_text' => $request->get('search_text'),
            'sort_by' => $request->get('sort_by'),
            'sort_direction' => $request->get('sort_direction'),
            'per_page' => $request->get('per_page'),
        ];

        $lengthAwarePaginator = $this->regionQueries->listQuery($filterData);

        return [
            'total_records' => $lengthAwarePaginator->total(),
            'data' => RegionListResource::collection($lengthAwarePaginator->getCollection()),
        ];
    }

    public function index()
    {
        return Inertia::render('regions/Index');
    }

    public function create(): Response
    {

        return Inertia::render('regions/Manage');
    }

    public function store(RegionData $regionData): RedirectResponse
    {
        $this->regionQueries->addNew($regionData);

        return to_route('regions.index')->with('success', 'The region has been added successfully.');
    }

    public function edit(int $regionId): Response
    {
        return Inertia::render('regions/Manage', [
            'region' => $this->regionQueries->getById($regionId),
        ]);
    }

    public function update(RegionData $regionData, int $regionId): RedirectResponse
    {
        $regionId = $this->regionQueries->getById($regionId);
        $this->regionQueries->update($regionData, $regionId);

        return to_route('regions.index')->with('success', 'The region has been updated successfully.');
    }

    public function delete(int $regionId)
    {
        $this->regionQueries->getById($regionId);

        $this->regionQueries->delete($regionId);

        return to_route('regions.index')->with('success', 'The region has been deleted successfully.');
    }

    public function getCommonResources(): array
    {
        $regionService = App::make(RegionService::class);

        return $regionService->getCommonRecords();
    }



    public function unauthorize()
    {
        return Inertia::render('Auth/Unauthorize');
    }



    public function status(int $regionId): RedirectResponse
    {
        $this->regionQueries->status($regionId);

        return to_route('region.index')->with('success', 'The region status has been updated successfully.');
    }
}
