<?php

use App\Manifest;
use App\Tarriff;
use App\Permission;
use App\State;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call([ManifestSeeder::class]);

        // $this->call([TarriffLocationSeeder::class]);

        // $this->call([TarriffZonningSeeder::class]);

        // $this->call([RoleSeeder::class]);

        // $this->call([PermissionSeeder::class]);

        //  $this->call([TarriffSeeder::class]);

        //  $this->call([StateSeeder::class]);

        //  $this->call([PlaceSeeder::class]);

        //  $this->call([LocalGovernmentSeeder::class]);

            $this->call([SiteTypeSeeder::class]);

    }
}
