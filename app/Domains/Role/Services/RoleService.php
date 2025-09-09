<?php

declare(strict_types=1);

namespace App\Domains\Role\Services;

use App\Domains\Permission\PermissionQueries;
use App\Domains\Role\RoleQueries;
use Illuminate\Support\Facades\App;

class RoleService
{
    /**
     * @return array<string, mixed>
     */
    public function getCommonRecords(): array
    {
        $roleQueries = App::make(RoleQueries::class);
        $permissionQueries = App::make(PermissionQueries::class);

        return [
            'roles' => $roleQueries->roleList(),
            'permissions' => $permissionQueries->permissionList(),
        ];
    }
}
