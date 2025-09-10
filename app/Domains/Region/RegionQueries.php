<?php

declare(strict_types=1);

namespace App\Domains\Region;

use App\Domains\Region\DataObjects\RegionData;
use App\Domains\Region\Models\Region;
use Illuminate\Pagination\LengthAwarePaginator;

class RegionQueries
{
    public function listQuery(array $filterData): LengthAwarePaginator
    {
        return Region::query()
        ->select('id', 'name', 'code', 'status')
        ->when(!empty($filterData['search_text']), function ($query) use ($filterData) {
            $query->where('name', 'LIKE', '%'.$filterData['search_text'].'%');
        })
        ->when(!empty($filterData['sort_by']), function ($query) use ($filterData) {
            $query->orderBy($filterData['sort_by'], $filterData['sort_direction'] ?? 'asc');
        }, function ($query) {
            $query->orderBy('id', 'desc');
        })
        ->paginate($filterData['per_page']);
    }

    public function addNew(RegionData $regionData): Region
    {
        $data = $regionData->all();
        $data['status'] = true;

        return Region::query()->create($data);
    }

    public function getById(int $regionId): Region
    {
        return Region::query()->select('id', 'name', 'code', 'status')
            ->findOrFail($regionId);
    }

    public function update(RegionData $regionData, Region $region): void
    {
        $regionDetails = $regionData->toArray();
        $region->update($regionDetails);
    }

    public function delete(int $regionId): void
    {
        $region = $this->getById($regionId);
        $region->delete();
    }

    public function status(int $regionId): void
    {
        $region = Region::query()->findorfail($regionId);
        $region->status = ! $region->status;
        $region->save();
    }
}
