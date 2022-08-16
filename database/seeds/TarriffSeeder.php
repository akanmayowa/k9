<?php

use App\Tarriff;
use Illuminate\Database\Seeder;

class TarriffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tarriff::truncate();
        Tarriff::insert(
            [
                ['weight_start' => 0.5,     'weight_end' => 1, 	   'zone_1_cost_in_cents' => 12.0,  'zone_2_cost_in_cents' => 17.0, 'zone_3_cost_in_cents' => 21.5, 'zone_4_cost_in_cents' => 26.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 1,       'weight_end' => 1.5,    'zone_1_cost_in_cents' => 14.5, 'zone_2_cost_in_cents' => 18.0, 'zone_3_cost_in_cents' => 23.0, 'zone_4_cost_in_cents' => 27.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 1.5,     'weight_end' => 2, 	   'zone_1_cost_in_cents' => 15.5,  'zone_2_cost_in_cents' => 20.5, 'zone_3_cost_in_cents' => 24.0, 'zone_4_cost_in_cents' => 28.8, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 2,       'weight_end' => 2.5,   'zone_1_cost_in_cents' => 17.0,  'zone_2_cost_in_cents' => 21.5, 'zone_3_cost_in_cents' => 26.5, 'zone_4_cost_in_cents' => 30.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 2.5,     'weight_end' => 3, 	   'zone_1_cost_in_cents' => 18.0,  'zone_2_cost_in_cents' => 23.0, 'zone_3_cost_in_cents' => 27.5, 'zone_4_cost_in_cents' => 32.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 3,       'weight_end' => 3.5, 	'zone_1_cost_in_cents' => 20.5, 'zone_2_cost_in_cents' => 24.0, 'zone_3_cost_in_cents' => 29.0, 'zone_4_cost_in_cents' => 33.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 3.5,    	'weight_end' => 4, 	   'zone_1_cost_in_cents' => 21.5,  'zone_2_cost_in_cents' => 26.5, 'zone_3_cost_in_cents' => 30.0, 'zone_4_cost_in_cents' => 35.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 4,       'weight_end' => 4.5, 	'zone_1_cost_in_cents' => 23.0, 'zone_2_cost_in_cents' => 27.5, 'zone_3_cost_in_cents' => 32.5, 'zone_4_cost_in_cents' => 36.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 4.5,     'weight_end' => 5, 	   'zone_1_cost_in_cents' => 24.0,  'zone_2_cost_in_cents' => 29.0, 'zone_3_cost_in_cents' => 33.5, 'zone_4_cost_in_cents' => 38.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 5,       'weight_end' => 5.5, 	'zone_1_cost_in_cents' => 26.5, 'zone_2_cost_in_cents' => 30.0, 'zone_3_cost_in_cents' => 35.0, 'zone_4_cost_in_cents' => 40.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 5.5,     'weight_end' => 6, 	   'zone_1_cost_in_cents' => 29.0,  'zone_2_cost_in_cents' => 33.5, 'zone_3_cost_in_cents' => 38.5, 'zone_4_cost_in_cents' => 43.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 6,       'weight_end' => 6.5, 	'zone_1_cost_in_cents' => 30.0, 'zone_2_cost_in_cents' => 35.0, 'zone_3_cost_in_cents' => 40.0, 'zone_4_cost_in_cents' => 44.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 6.5,     'weight_end' => 7, 	   'zone_1_cost_in_cents' => 31.0, 'zone_2_cost_in_cents' => 36.0, 'zone_3_cost_in_cents' => 41.0, 'zone_4_cost_in_cents' => 45.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 7,       'weight_end' => 7.5, 	'zone_1_cost_in_cents' => 33.5, 'zone_2_cost_in_cents' => 38.5, 'zone_3_cost_in_cents' => 43.5, 'zone_4_cost_in_cents' => 47.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 7.5,     'weight_end' => 8, 	   'zone_1_cost_in_cents' => 35.0, 'zone_2_cost_in_cents' => 39.5, 'zone_3_cost_in_cents' => 44.5, 'zone_4_cost_in_cents' => 49.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 8,       'weight_end' => 8.5, 	'zone_1_cost_in_cents' => 36.0, 'zone_2_cost_in_cents' => 41.0, 'zone_3_cost_in_cents' => 45.5, 'zone_4_cost_in_cents' => 50.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 8.5,     'weight_end' => 9, 	   'zone_1_cost_in_cents' => 38.5, 'zone_2_cost_in_cents' => 43.5, 'zone_3_cost_in_cents' => 47.0, 'zone_4_cost_in_cents' => 51.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 9,       'weight_end' => 9.5, 	'zone_1_cost_in_cents' => 40.0, 'zone_2_cost_in_cents' => 44.5, 'zone_3_cost_in_cents' => 50.0, 'zone_4_cost_in_cents' => 54.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 9.5,     'weight_end' => 10,    'zone_1_cost_in_cents' => 41.0, 'zone_2_cost_in_cents' => 45.5, 'zone_3_cost_in_cents' => 50.5, 'zone_4_cost_in_cents' => 55.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 10,      'weight_end' => 10.5,  'zone_1_cost_in_cents' => 43.5, 'zone_2_cost_in_cents' => 47.0, 'zone_3_cost_in_cents' => 51.5, 'zone_4_cost_in_cents' => 56.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 10.5,    'weight_end' => 11,    'zone_1_cost_in_cents' => 45.5, 'zone_2_cost_in_cents' => 50.5, 'zone_3_cost_in_cents' =>55.0, 'zone_4_cost_in_cents' => 60.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 11,      'weight_end' => 11.5,  'zone_1_cost_in_cents' => 47.0, 'zone_2_cost_in_cents' => 51.5, 'zone_3_cost_in_cents' => 56.5, 'zone_4_cost_in_cents' => 61.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 11.5,    'weight_end' => 12,    'zone_1_cost_in_cents' => 49.5, 'zone_2_cost_in_cents' => 53.0, 'zone_3_cost_in_cents' => 57.5, 'zone_4_cost_in_cents' => 62.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 12,      'weight_end' => 12.5,  'zone_1_cost_in_cents' => 50.5, 'zone_2_cost_in_cents' => 55.0, 'zone_3_cost_in_cents' => 60.0, 'zone_4_cost_in_cents' => 65.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 12.5, 	'weight_end' => 13,    'zone_1_cost_in_cents' => 61.5, 'zone_2_cost_in_cents' => 56.5, 'zone_3_cost_in_cents' => 61.0, 'zone_4_cost_in_cents' => 66.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 13, 		'weight_end' => 13.5,  'zone_1_cost_in_cents' => 53.0, 'zone_2_cost_in_cents' => 57.5, 'zone_3_cost_in_cents' => 62.5, 'zone_4_cost_in_cents' => 68.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 13.5, 	'weight_end' => 14,    'zone_1_cost_in_cents' => 55.5, 'zone_2_cost_in_cents' => 60.0, 'zone_3_cost_in_cents' => 65.0, 'zone_4_cost_in_cents' => 70.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 14, 		'weight_end' => 14.5,  'zone_1_cost_in_cents' => 56.5, 'zone_2_cost_in_cents' => 61.0, 'zone_3_cost_in_cents' => 66.0, 'zone_4_cost_in_cents' => 71.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 14.5, 	'weight_end' => 15,    'zone_1_cost_in_cents' => 57.5, 'zone_2_cost_in_cents' => 62.5, 'zone_3_cost_in_cents' => 67.5, 'zone_4_cost_in_cents' => 73.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 15, 		'weight_end' => 15.5,  'zone_1_cost_in_cents' => 60.0, 'zone_2_cost_in_cents' => 65.0, 'zone_3_cost_in_cents' => 68.5, 'zone_4_cost_in_cents' => 74.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 15.5, 	'weight_end' => 16,    'zone_1_cost_in_cents' => 61.0, 'zone_2_cost_in_cents' => 66.0, 'zone_3_cost_in_cents' => 71.0, 'zone_4_cost_in_cents' => 75.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 16, 		'weight_end' => 16.5,  'zone_1_cost_in_cents' => 62.5, 'zone_2_cost_in_cents' => 67.5, 'zone_3_cost_in_cents' => 73.5, 'zone_4_cost_in_cents' => 77.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 16.5, 	'weight_end' => 17,    'zone_1_cost_in_cents' => 65.0, 'zone_2_cost_in_cents' => 68.5, 'zone_3_cost_in_cents' => 74.5, 'zone_4_cost_in_cents' => 79.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 17, 		'weight_end' => 17.5,  'zone_1_cost_in_cents' => 66.0, 'zone_2_cost_in_cents' => 71.0, 'zone_3_cost_in_cents' => 75.5, 'zone_4_cost_in_cents' => 80.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 17.5, 	'weight_end' => 18,    'zone_1_cost_in_cents' => 67.5, 'zone_2_cost_in_cents' => 73.5, 'zone_3_cost_in_cents' => 77.0, 'zone_4_cost_in_cents' => 81.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 18, 		'weight_end' => 18.5,  'zone_1_cost_in_cents' => 68.5, 'zone_2_cost_in_cents' => 74.5, 'zone_3_cost_in_cents' => 79.5, 'zone_4_cost_in_cents' => 84.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 18.5, 	'weight_end' => 19,    'zone_1_cost_in_cents' => 71.0, 'zone_2_cost_in_cents' => 75.0, 'zone_3_cost_in_cents' => 80.5, 'zone_4_cost_in_cents' => 85.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 19, 		'weight_end' => 19.5,  'zone_1_cost_in_cents' => 72.0, 'zone_2_cost_in_cents' => 77.0, 'zone_3_cost_in_cents' => 81.5, 'zone_4_cost_in_cents' => 86.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 19.5, 	'weight_end' => 20,    'zone_1_cost_in_cents' =>  73.5, 'zone_2_cost_in_cents' => 79.5, 'zone_3_cost_in_cents' => 83.0, 'zone_4_cost_in_cents' => 89.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 20.0, 'weight_end' => 21.0, 'zone_1_cost_in_cents' => 74.5, 'zone_2_cost_in_cents' => 85.5, 'zone_3_cost_in_cents' => 85.0, 'zone_4_cost_in_cents' => 91.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 21.0, 'weight_end' => 22.0, 'zone_1_cost_in_cents' => 75.5, 'zone_2_cost_in_cents' => 83.0, 'zone_3_cost_in_cents' => 89.0, 'zone_4_cost_in_cents' => 96.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 22.0, 'weight_end' => 23.0, 'zone_1_cost_in_cents' => 76.5, 'zone_2_cost_in_cents' => 85.5, 'zone_3_cost_in_cents' => 91.0, 'zone_4_cost_in_cents' => 101.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 23.0, 'weight_end' => 24.0, 'zone_1_cost_in_cents' =>  77.5, 'zone_2_cost_in_cents' => 87.0, 'zone_3_cost_in_cents' => 94.5, 'zone_4_cost_in_cents' =>106.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 24.0, 'weight_end' => 25.0, 'zone_1_cost_in_cents' => 78.5, 'zone_2_cost_in_cents' => 90.0, 'zone_3_cost_in_cents' => 97.5, 'zone_4_cost_in_cents' => 110.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 25.0, 'weight_end' => 26.0, 'zone_1_cost_in_cents' => 79.5, 'zone_2_cost_in_cents' => 92.5, 'zone_3_cost_in_cents' => 100.5, 'zone_4_cost_in_cents' => 115.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 26.0, 'weight_end' => 27.0, 'zone_1_cost_in_cents' => 80.0, 'zone_2_cost_in_cents' => 95.0, 'zone_3_cost_in_cents' => 103.5, 'zone_4_cost_in_cents' => 120.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 27.0, 'weight_end' => 28.0, 'zone_1_cost_in_cents' => 80.5, 'zone_2_cost_in_cents' => 97.50, 'zone_3_cost_in_cents' => 106.5, 'zone_4_cost_in_cents' => 125.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 28.0, 'weight_end' => 29.0, 'zone_1_cost_in_cents' => 81.5, 'zone_2_cost_in_cents' => 99.5, 'zone_3_cost_in_cents' => 109.5, 'zone_4_cost_in_cents' => 129.50, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 29.0, 'weight_end' => 30.0, 'zone_1_cost_in_cents' => 83.0, 'zone_2_cost_in_cents' => 102.0, 'zone_3_cost_in_cents' => 112.0, 'zone_4_cost_in_cents' => 134.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 30.0, 'weight_end' => 31.0, 'zone_1_cost_in_cents' => 84.0, 'zone_2_cost_in_cents' => 104.50, 'zone_3_cost_in_cents' => 115.0, 'zone_4_cost_in_cents' => 139.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 31.0, 'weight_end' => 32.0, 'zone_1_cost_in_cents' => 85.0, 'zone_2_cost_in_cents' => 107.0, 'zone_3_cost_in_cents' => 118.50, 'zone_4_cost_in_cents' => 144.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 32.0, 'weight_end' => 33.0, 'zone_1_cost_in_cents' => 86.5, 'zone_2_cost_in_cents' => 109.5, 'zone_3_cost_in_cents' => 121.0, 'zone_4_cost_in_cents' => 150.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 33.0, 'weight_end' => 34.0, 'zone_1_cost_in_cents' => 87.5, 'zone_2_cost_in_cents' => 121.5, 'zone_3_cost_in_cents' => 124.5, 'zone_4_cost_in_cents' => 153.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 34.0, 'weight_end' => 35.0, 'zone_1_cost_in_cents' => 89.0, 'zone_2_cost_in_cents' => 124.0, 'zone_3_cost_in_cents' => 127.5, 'zone_4_cost_in_cents' => 158.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 35.0, 'weight_end' => 36.0, 'zone_1_cost_in_cents' => 90.0, 'zone_2_cost_in_cents' => 126.0, 'zone_3_cost_in_cents' => 130.5, 'zone_4_cost_in_cents' => 163.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 36.0, 'weight_end' => 37.0, 'zone_1_cost_in_cents' => 91.0, 'zone_2_cost_in_cents' => 128.5, 'zone_3_cost_in_cents' => 133.5, 'zone_4_cost_in_cents' => 168.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 37.0, 'weight_end' => 38.0, 'zone_1_cost_in_cents' => 92.50, 'zone_2_cost_in_cents' => 131.0, 'zone_3_cost_in_cents' => 136.5, 'zone_4_cost_in_cents' => 173.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 38.0, 'weight_end' => 39.0, 'zone_1_cost_in_cents' => 94.0, 'zone_2_cost_in_cents' => 133.50, 'zone_3_cost_in_cents' => 139.50, 'zone_4_cost_in_cents'=> 178.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 39.0, 'weight_end' => 40.0, 'zone_1_cost_in_cents' => 95.0, 'zone_2_cost_in_cents' => 136.0, 'zone_3_cost_in_cents' => 142.5, 'zone_4_cost_in_cents' =>  182.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 40.0, 'weight_end' => 41.0, 'zone_1_cost_in_cents' => 96.0, 'zone_2_cost_in_cents' => 138.0, 'zone_3_cost_in_cents' => 145.0, 'zone_4_cost_in_cents' =>  187.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 41.0, 'weight_end' => 42.0, 'zone_1_cost_in_cents' => 97.5, 'zone_2_cost_in_cents' => 140.5, 'zone_3_cost_in_cents' => 149.0, 'zone_4_cost_in_cents' =>  192.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 42.0, 'weight_end' => 43.0, 'zone_1_cost_in_cents' => 98.5, 'zone_2_cost_in_cents' => 143.0, 'zone_3_cost_in_cents' => 151.5, 'zone_4_cost_in_cents' =>  197.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 43.0, 'weight_end' => 44.0, 'zone_1_cost_in_cents' => 99.5, 'zone_2_cost_in_cents' => 145.5, 'zone_3_cost_in_cents' => 155.5, 'zone_4_cost_in_cents' =>  202.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 44.0, 'weight_end' => 45.0, 'zone_1_cost_in_cents' => 101.0, 'zone_2_cost_in_cents' => 148.0, 'zone_3_cost_in_cents' => 157.5, 'zone_4_cost_in_cents' => 206.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 45.0, 'weight_end' => 46.0, 'zone_1_cost_in_cents' => 102.0, 'zone_2_cost_in_cents' => 150.0, 'zone_3_cost_in_cents' => 160.5, 'zone_4_cost_in_cents' => 211.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 46.0, 'weight_end' => 47.0, 'zone_1_cost_in_cents' => 103.5, 'zone_2_cost_in_cents' => 152.5, 'zone_3_cost_in_cents' => 163.5, 'zone_4_cost_in_cents' => 216.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 47.0, 'weight_end' =>48.0, 'zone_1_cost_in_cents' => 104.5, 'zone_2_cost_in_cents' => 155.0, 'zone_3_cost_in_cents' =>  166.5, 'zone_4_cost_in_cents' =>  221.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 48.0, 'weight_end' => 49.0, 'zone_1_cost_in_cents' => 106.0, 'zone_2_cost_in_cents' => 157.5, 'zone_3_cost_in_cents' => 170.0, 'zone_4_cost_in_cents' => 225.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 49.0, 'weight_end' => 50.0, 'zone_1_cost_in_cents' => 107.0, 'zone_2_cost_in_cents' => 160.0, 'zone_3_cost_in_cents' => 172.5, 'zone_4_cost_in_cents' => 231.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 50.0, 'weight_end' => 51.0, 'zone_1_cost_in_cents' => 108.0, 'zone_2_cost_in_cents' => 162.0, 'zone_3_cost_in_cents' => 175.5, 'zone_4_cost_in_cents' => 235.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 51.0, 'weight_end' => 52.0, 'zone_1_cost_in_cents' => 109.5, 'zone_2_cost_in_cents' => 164.0, 'zone_3_cost_in_cents' => 178.0, 'zone_4_cost_in_cents' => 239.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 52.0, 'weight_end' => 53.0, 'zone_1_cost_in_cents' => 110.5, 'zone_2_cost_in_cents' => 166.5, 'zone_3_cost_in_cents' => 180.0, 'zone_4_cost_in_cents' => 243.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 53.0, 'weight_end' => 54.0, 'zone_1_cost_in_cents' => 111.5, 'zone_2_cost_in_cents' => 167.5, 'zone_3_cost_in_cents' => 182.5, 'zone_4_cost_in_cents' => 248.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 54.0, 'weight_end' => 55.0, 'zone_1_cost_in_cents' => 113.0, 'zone_2_cost_in_cents' => 169.5, 'zone_3_cost_in_cents' => 185.0, 'zone_4_cost_in_cents' =>  252.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 55.0, 'weight_end' => 56.0, 'zone_1_cost_in_cents' => 114.0, 'zone_2_cost_in_cents' => 171.0, 'zone_3_cost_in_cents' => 187.5, 'zone_4_cost_in_cents' => 256.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 56.0, 'weight_end' => 57.0, 'zone_1_cost_in_cents' => 115.5, 'zone_2_cost_in_cents' => 173.0, 'zone_3_cost_in_cents' => 189.5, 'zone_4_cost_in_cents' => 260.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 57.0, 'weight_end' => 58.0, 'zone_1_cost_in_cents' => 116.5, 'zone_2_cost_in_cents' => 175.0, 'zone_3_cost_in_cents' => 192.0, 'zone_4_cost_in_cents' => 265.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 58.0, 'weight_end' => 59.0, 'zone_1_cost_in_cents' => 118.0, 'zone_2_cost_in_cents' => 177.0, 'zone_3_cost_in_cents' => 194.5, 'zone_4_cost_in_cents' => 270.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 59.0, 'weight_end' => 60.0, 'zone_1_cost_in_cents' => 119.0, 'zone_2_cost_in_cents' => 179.0, 'zone_3_cost_in_cents' => 197.0, 'zone_4_cost_in_cents' => 273.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 60.0, 'weight_end' => 61.0, 'zone_1_cost_in_cents' => 120.0, 'zone_2_cost_in_cents' => 180.0, 'zone_3_cost_in_cents' => 199.5, 'zone_4_cost_in_cents' => 277.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 61.0, 'weight_end' => 62.0, 'zone_1_cost_in_cents' => 121.5, 'zone_2_cost_in_cents' => 182.0, 'zone_3_cost_in_cents' => 201.5, 'zone_4_cost_in_cents' => 281.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 62.0, 'weight_end' => 63.0, 'zone_1_cost_in_cents' => 122.5, 'zone_2_cost_in_cents' => 184.0, 'zone_3_cost_in_cents' => 204.0, 'zone_4_cost_in_cents' => 285.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 63.0, 'weight_end' => 64.0, 'zone_1_cost_in_cents' => 124.0, 'zone_2_cost_in_cents' => 186.0, 'zone_3_cost_in_cents' => 206.5, 'zone_4_cost_in_cents' => 290.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 64.0, 'weight_end' => 65.0, 'zone_1_cost_in_cents' => 125.0, 'zone_2_cost_in_cents' => 187.5, 'zone_3_cost_in_cents' => 209.0, 'zone_4_cost_in_cents' => 294.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 65.0, 'weight_end' => 66.0, 'zone_1_cost_in_cents' => 126.0, 'zone_2_cost_in_cents' => 189.0, 'zone_3_cost_in_cents' => 211.5, 'zone_4_cost_in_cents' => 298.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 66.0, 'weight_end' => 67.0, 'zone_1_cost_in_cents' => 127.5, 'zone_2_cost_in_cents' => 191.0, 'zone_3_cost_in_cents' => 214.0, 'zone_4_cost_in_cents' => 302.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 67.0, 'weight_end' => 68.0, 'zone_1_cost_in_cents' => 128.5, 'zone_2_cost_in_cents' => 192.5, 'zone_3_cost_in_cents' => 216.5, 'zone_4_cost_in_cents' => 306.5, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 68.0, 'weight_end' => 69.0, 'zone_1_cost_in_cents' => 130.0, 'zone_2_cost_in_cents' => 194.5, 'zone_3_cost_in_cents' => 219.0, 'zone_4_cost_in_cents' => 311.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 69.0, 'weight_end' => 70.0, 'zone_1_cost_in_cents' => 131.0, 'zone_2_cost_in_cents' => 196.5, 'zone_3_cost_in_cents' => 221.5, 'zone_4_cost_in_cents' => 315.0, 'created_at' => now(), 'updated_at' => now()],
                ['weight_start' => 70.0, 'weight_end' => 71.0, 'zone_1_cost_in_cents' => 132.0, 'zone_2_cost_in_cents' => 198.0, 'zone_3_cost_in_cents' => 224.0, 'zone_4_cost_in_cents' => 319.5, 'created_at' => now(), 'updated_at' => now()]
            ]
            );
    }
}
