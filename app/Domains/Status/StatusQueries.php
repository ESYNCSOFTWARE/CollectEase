<?php

declare(strict_types=1);

namespace App\Domains\Status;

use App\Domains\Status\DataObjects\StatusData;
use App\Domains\Status\Models\Status;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Domains\Role\Models\Role;


class StatusQueries
{
    public function listQuery(array $filterData): LengthAwarePaginator
    {
        return Status::query()
            ->select('id', 'name', 'type', 'code', 'description', 'color', 'sort_order', 'is_default')
            ->when(!empty($filterData['search_text']), function ($query) use ($filterData) {
                $search = $filterData['search_text'];
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%")
                        ->orWhere('type', 'LIKE', "%{$search}%")
                        ->orWhere('code', 'LIKE', "%{$search}%");
                });
            })

            ->when(!empty($filterData['sort_by']), function ($query) use ($filterData) {
                $query->orderBy($filterData['sort_by'], $filterData['sort_direction'] ?? 'asc');
            }, function ($query) {
                $query->orderBy('id', 'desc');
            })
            ->paginate($filterData['per_page']);
    }

    public function addNew(StatusData $statusData, array $roleData = []): Status
    {
        $data = $statusData->all();
        $data['is_default'] = true;

        $status = Status::query()->create($data);

        if (!empty($roleData)) {
            $status->roles()->attach($roleData);
        }

        return $status;
    }

    public function getById(int $statusId): Status
    {
        return Status::query()
            ->select('id', 'name', 'type', 'code', 'description', 'color', 'is_default', 'sort_order')
            ->with([
                'roles' => function ($query) {
                    $query->select('roles.id', 'roles.name')
                        ->withPivot(['can_view', 'can_update']);
                }
            ])
            ->findOrFail($statusId);
    }

    public function update(StatusData $statusData, Status $status, array $roleData = []): Status
    {
        $status->update($statusData->all());

        if (!empty($roleData)) {
            $status->roles()->sync($roleData);
        }

        return $status;
    }

    public function delete(int $statusId): void
    {
        $status = $this->getById($statusId);
        $status->delete();
    }

    public function status(int $statusId): void
    {
        $status = Status::query()->findorfail($statusId);
        $status->is_default = !$status->is_default;
        $status->save();
    }

    public function unauthorize()
    {
        return Inertia::render('Auth/Unauthorize');
    }

}