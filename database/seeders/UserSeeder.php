<?php

namespace Database\Seeders;

use App\Domains\User\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Super dmin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('admin@321'),
                'status' => 1,
                'type_id' => 1, // Super Admin
                'dashboard_component' => 'SuperAdminDashboard',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'email_verified_at' => Carbon::now(),
            ],
            [
                'name' => 'Agent',
                'email' => 'agent@gmail.com',
                'password' => bcrypt('agent@321'),
                'dashboard_component' => 'AgentDashboard',
                'status' => 1,
                'type_id' => 2, // HR Manager
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'email_verified_at' => Carbon::now(),
            ],
        ];

        User::insert($data);
    }
}
