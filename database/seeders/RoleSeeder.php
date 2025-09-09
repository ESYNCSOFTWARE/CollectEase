<?php

namespace Database\Seeders;

use App\Domains\Role\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Super Admin', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Manager', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        Role::insert($data);
    }
}
