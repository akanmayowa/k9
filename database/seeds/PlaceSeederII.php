<?php

use App\Place;
use Illuminate\Database\Seeder;

class PlaceSeederII extends Seeder
{


    public function run()
    {
        Place::insert(
            ['name' => 'Aba North', 'state_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            
        );
    }
}
