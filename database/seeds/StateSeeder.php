<?php

use App\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{

    public function run()
    {

        State::insert([
            ['name' => 'abia', 'capital'=> 'umuahia', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'abuja', 'capital'=> ' fct',  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'adamawa', 'capital'=> ' yola',  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'akwa ibom ', 'capital'=> 'uyo',  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'anambra', 'capital'=> 'awka',  'created_at' => now(), 'updated_at' => now() ],
            ['name' => 'bauchi', 'capital'=> 'bauchi',  'created_at' => now(), 'updated_at' => now() ],
            ['name' => 'bayelsa', 'capital'=> 'yenagoa',  'created_at' => now(), 'updated_at' => now() ],
            ['name' => 'benue', 'capital'=> 'makurdi' ,  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'borno', 'capital'=> 'maiduguri',  'created_at' => now(), 'updated_at' => now() ],
            ['name' => 'cross river', 'capital'=> 'calabar',  'created_at' => now(), 'updated_at' => now() ],
            ['name' => 'delta', 'capital'=> 'asaba',  'created_at' => now(), 'updated_at' => now() ],
            ['name' => 'ebonyi', 'capital'=> 'abakaliki',  'created_at' => now(), 'updated_at' => now() ],
            ['name' => 'edo', 'capital'=> 'benin',  'created_at' => now(), 'updated_at' => now() ],
            ['name' => 'ekiti', 'capital'=> 'ado ekiti', 'created_at' => now(), 'updated_at' => now() ],
            ['name' => 'enugu', 'capital'=> 'enugu',  'created_at' => now(), 'updated_at' => now() ],
            ['name' => 'gombe', 'capital'=> 'gombe' , 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'imo', 'capital'=> 'owerri',  'created_at' => now(), 'updated_at' => now() ],
            ['name' => 'jigawa', 'capital'=> 'dutse' , 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'kaduna', 'capital'=> 'kaduna' , 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'kano', 'capital'=> 'kano' , 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'katsina', 'capital'=> 'katsina' , 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'kebbi', 'capital'=> 'birnin kebbi' , 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'kogi', 'capital'=> 'lokoja' , 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'kwara', 'capital'=> 'ilorin' , 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'lagos', 'capital'=> 'ikeja', 'created_at' => now(), 'updated_at' => now() ],
            ['name' => 'nasarawa', 'capital'=> 'lafia', 'created_at' => now(), 'updated_at' => now() ],
            ['name' => 'niger', 'capital'=> 'minna' , 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ogun', 'capital'=> 'abeokuta', 'created_at' => now(), 'updated_at' => now() ],
            ['name' => 'ondo', 'capital'=> 'akure', 'created_at' => now(), 'updated_at' => now() ],
            ['name' => 'osun', 'capital'=> 'oshogbo',  'created_at' => now(), 'updated_at' => now() ],

            ['name' => 'oyo', 'capital'=> 'ibadan' , 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'plateau', 'capital'=> 'jos' , 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'rivers', 'capital'=> 'port harcourt', 'created_at' => now(), 'updated_at' => now() ],
            ['name' => 'sokoto', 'capital'=> 'sokoto', 'created_at' => now(), 'updated_at' => now() ],
            ['name' => 'taraba', 'capital'=> 'jalingo', 'created_at' => now(), 'updated_at' => now() ],
            ['name' => 'yobe', 'capital'=> 'damaturu', 'created_at' => now(), 'updated_at' => now() ],
            ['name' => 'zamfara', 'capital'=> 'gusau' , 'created_at' => now(), 'updated_at' => now()],
        ]);


    }
}
