<?php

use App\Site;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Site::truncate();
        //Creates 3 Radom Sites
        factory(Site::class, 10)->create();
    }
}
