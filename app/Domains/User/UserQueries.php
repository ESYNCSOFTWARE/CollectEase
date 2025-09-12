<?php

declare(strict_types=1);

namespace App\Domains\User;

use App\Domains\User\DataObjects\UserData;
use App\Domains\User\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserQueries
{
    public function listQuery(array $filterData): LengthAwarePaginator
    {
        return User::query()
        ->select('id', 'name', 'status', 'email')
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

    public function addNew(UserData $userData): User
    {
        $data = $userData->all();
        $data['email_verified_at'] = now()->format('Y-m-d H:i:s');
        $data['status'] = true;

        return User::query()->create($data);
    }

    public function getById(int $userId): User
    {
        return User::query()->select('id', 'name', 'email', 'status', 'dashboard_component')
            ->findOrFail($userId);
    }

    public function update(UserData $userData, User $user): void
    {
        $userDetails = $userData->toArray();
        unset($userDetails['password']);
        $user->update($userDetails);
    }

    public function delete(int $userId): void
    {
        $user = $this->getById($userId);
        $user->delete();
    }

    public function status(int $userId): void
    {
        $user = User::query()->findorfail($userId);
        $user->status = ! $user->status;
        $user->save();
    }
}
