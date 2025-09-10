<?php

declare(strict_types=1);

namespace App\Domains\Permission\Services;

use App\Domains\Permission\Enums\PermissionList;
use App\Domains\Permission\Models\Permission;
use App\Domains\Role\Models\Role;
use App\Domains\User\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PermissionModuleService
{
    /**
     * @return array<string, mixed>
     */
    public static function getModuleLists(): array
    {
        return [
            'dashboard' => [
                'permissions' => [
                    PermissionList::VIEW->value,
                ],
            ],
            'setup' => [
                'permissions' => [
                    PermissionList::VIEW->value,
                ],
            ],
            'settings' => [
                'permissions' => [
                    PermissionList::VIEW->value,
                ],
            ],
            'users' => [
                'permissions' => [
                    PermissionList::VIEW->value,
                    PermissionList::CREATE->value,
                    PermissionList::UPDATE->value,
                    PermissionList::DELETE->value,
                ],
            ],
            'regions' => [
                'permissions' => [
                    PermissionList::VIEW->value,
                    PermissionList::CREATE->value,
                    PermissionList::UPDATE->value,
                    PermissionList::DELETE->value,
                ],
            ],
            'permission_groups' => [
                'permissions' => [
                    PermissionList::VIEW->value,
                    PermissionList::CREATE->value,
                    PermissionList::UPDATE->value,
                    PermissionList::DELETE->value,
                ],
            ],
            'permissions' => [
                'permissions' => [
                    PermissionList::VIEW->value,
                    PermissionList::CREATE->value,
                    PermissionList::UPDATE->value,
                    PermissionList::DELETE->value,
                ],
            ],
            'roles' => [
                'permissions' => [
                    PermissionList::VIEW->value,
                    PermissionList::CREATE->value,
                    PermissionList::UPDATE->value,
                    PermissionList::DELETE->value,
                ],
            ],
            'configs' => [
                'permissions' => [
                    PermissionList::VIEW->value,
                    PermissionList::CREATE->value,
                    PermissionList::UPDATE->value,
                    PermissionList::DELETE->value,
                ],
            ],
        ];
    }

    public function run(): void
    {
        $this->assignSuperAdminRole();

        $modules = self::getModuleLists();

        foreach ($modules as $moduleName => $module) {
            $this->createPermissionsForModule($moduleName, $module['permissions']);
        }

        $this->assignAllPermissionsToSuperAdmin();
    }

    private function assignSuperAdminRole(): void
    {

        $superAdminRole = Role::query()->where('name', 'Super admin')->first();
        $user = User::query()->where('email', 'admin@gmail.com')->first();

        $user->roles()->attach($superAdminRole->id);
    }

    private function createPermissionsForModule(string $moduleName, array $permissions): void
    {

        foreach ($permissions as $permission) {
            $data = [
                'name' => $permission.'_'.Str::upper(Str::replace(' ', '_', $moduleName)),
                'display_name' => Str::title($permission).' '.$moduleName,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            Permission::query()->create($data);
        }
    }

    private function assignAllPermissionsToSuperAdmin(): void
    {

        $permissions = Permission::all();
        $superAdminRole = Role::query()->where('name', 'Super admin')->first();

        foreach ($permissions as $permission) {
            DB::table('permission_role')->insert([
                'role_id' => $superAdminRole->id,
                'permission_id' => $permission->id,
            ]);
        }
    }
}
