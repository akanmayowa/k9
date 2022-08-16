<?php

use Illuminate\Database\Seeder;
use App\SiteType;

class SiteTypeSeeder extends Seeder
{


    public function run()
    {
        SiteType::insert([
            ['id' =>1, 'name' => 'speedaf', 'created_at' => now(), 'updated_at' => now()],
            ['id' =>2, 'name' => 'franchisee', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
