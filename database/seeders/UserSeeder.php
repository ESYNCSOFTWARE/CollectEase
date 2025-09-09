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
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('admin@321'),
                'status' => 1,
                'type_id' => 1, // Super Admin
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'email_verified_at' => Carbon::now(),
            ],
            [
                'name' => 'Manager',
                'email' => 'testmanager@gmail.com',
                'password' => bcrypt('admin@321'),
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
