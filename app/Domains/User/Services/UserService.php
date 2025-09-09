<?php

declare(strict_types=1);

namespace App\Domains\User\Services;

use App\Domains\Permission\PermissionGroupQueries;
use App\Domains\Role\RoleQueries;
use Illuminate\Support\Facades\App;

class UserService
{
    /**
     * @return array<string, mixed>
     */
    public function getCommonRecords(): array
    {
        $permissionGroupQueries = App::make(PermissionGroupQueries::class);
        $roleQueries = App::make(RoleQueries::class);

        return [
            'permission_groups' => $permissionGroupQueries->permissionGroupList(),
            'roles' => $roleQueries->roleList(),
        ];
    }
}
