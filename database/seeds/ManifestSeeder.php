<?php

use App\Manifest;
use Illuminate\Database\Seeder;

class ManifestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
        //The manifest needs to start from 50000000000
        Manifest::firstOrCreate(
            ['id' => 50000000000],
            [
                'scan_site_id' => 0,
                'next_site_id' => 0,
                'status' => 0,
                'is_flagged' => 0,
                // 'is_deleted' => 1, //Yes softdelete instead
                'created_by' => 0,
                'acknowledged_by' => null,
                'acknowledged_at' => null,
                'updated_by' => 0
            ]
            );


            $manifest = Manifest::find(50000000000);
            $manifest->delete();
            echo "Successful";
        }
        catch(Exception $ex)
        {
            echo "Operqtion Failed \n" + $ex->getMessage();
        }

    }
}
