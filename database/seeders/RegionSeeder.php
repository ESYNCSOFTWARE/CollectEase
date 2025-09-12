<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Domains\Region\Models\Region;



class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'East', 'code' => 'E', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'West', 'code' => 'W', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'North', 'code' => 'N', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'South', 'code' => 'S', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        Region::insert($data);

    }
}
