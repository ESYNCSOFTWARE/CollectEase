<?php

declare(strict_types=1);

namespace App\Domains\RoleStatus;

use App\Domains\RoleStatus\DataObjects\RoleStatusData;
use App\Domains\RoleStatus\Models\RoleStatus;
use Illuminate\Pagination\LengthAwarePaginator;

class RoleStatusQueries
{
    public function listQuery(array $filterData): LengthAwarePaginator
    {
        return RoleStatus::query()
            ->select('id', 'status_id', 'role_id', 'can_view', 'can_update')
            ->with('status', 'role')
            ->when(!empty($filterData['search_text']), function ($query) use ($filterData) {
                $query->where('name', 'LIKE', '%' . $filterData['search_text'] . '%');
            })
            ->when(!empty($filterData['sort_by']), function ($query) use ($filterData) {
                $query->orderBy($filterData['sort_by'], $filterData['sort_direction'] ?? 'asc');
            }, function ($query) {
                $query->orderBy('id', 'desc');
            })
            ->paginate($filterData['per_page']);
    }

    public function addNew(RoleStatusData $roleStatusData): RoleStatus
    {
        $data = $roleStatusData->all();
        return RoleStatus::query()->create($data);
    }

    public function getById(int $roleStatusId): RoleStatus
    {
        return RoleStatus::query()->select('id','status_id', 'role_id', 'can_view', 'can_update')
            ->findOrFail($roleStatusId);
    }

    public function update(RoleStatusData $roleStatusData, int $roleStatusId): void
    {
        $roleStatus = $this->getById($roleStatusId);
        $roleStatus->update($roleStatusData->all());
    }

    public function delete(int $roleStatusId): void
    {
        $roleStatus = $this->getById($roleStatusId);
        $roleStatus->delete();
    }

    public function canView(int $roleStatusId): void
    {
        $roleStatus = RoleStatus::query()->findorfail($roleStatusId);
        $roleStatus->can_view = ! $roleStatus->can_view;
        $roleStatus->save();
    }

    public function canUpdate(int $roleStatusId): void
    {
        $roleStatus = RoleStatus::query()->findorfail($roleStatusId);
        $roleStatus->can_update = ! $roleStatus->can_update;
        $roleStatus->save();
    }
}
