<?php

namespace Database\Seeders;

use App\Domains\Permission\Services\PermissionModuleService;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissionModuleService = new PermissionModuleService;
        $permissionModuleService->run();
    }
}
