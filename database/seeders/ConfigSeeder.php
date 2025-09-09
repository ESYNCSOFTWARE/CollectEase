<?php

namespace Database\Seeders;

use App\Domains\Config\Models\Config;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Config::truncate();

        $data = [
            [
                'key' => 'cache',
                'value' => false,
                'type' => 'boolean',
                'description' => 'PXM API (Cache)',
                'status' => 1,
                'platform' => 'backend',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key' => 'maintance_website',
                'value' => false,
                'type' => 'boolean',
                'description' => 'Website temporary down',
                'status' => 1,
                'platform' => 'fronted',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key' => 'alert_client_for_service_payment',
                'value' => false,
                'type' => 'boolean',
                'description' => 'Alert client for service payment',
                'status' => 1,
                'platform' => 'backend',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key' => 'alert_user_for_server_upgrade',
                'value' => false,
                'type' => 'boolean',
                'description' => 'Alert client for server upgrade',
                'status' => 1,
                'platform' => 'backend',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key' => 'login_by_email',
                'value' => true,
                'type' => 'boolean',
                'description' => 'User can login by email',
                'status' => 1,
                'platform' => 'fronted',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key' => 'login_by_mobile_no',
                'value' => false,
                'type' => 'boolean',
                'description' => 'User can login by mobile no',
                'status' => 1,
                'platform' => 'fronted',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key' => 'primary_color',
                'value' => '#0000FF',
                'type' => 'string',
                'description' => 'Primary color',
                'status' => 1,
                'platform' => 'fronted',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key' => 'primary_color_2',
                'value' => '#0000FF',
                'type' => 'string',
                'description' => 'Primary color 2',
                'status' => 1,
                'platform' => 'backend',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

        ];

        Config::insert($data);
    }
}
