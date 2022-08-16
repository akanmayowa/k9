<?php

use App\TarriffLocation;
use Illuminate\Database\Seeder;

class TarriffLocationSeeder extends Seeder
{
    
    public function run()
    {
        TarriffLocation::truncate();
        TarriffLocation::insert([
            ['name' => 'ABA', 'code' => 'ABA', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ABEOKUTA', 'code' => 'ABK', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ABAKALIKI', 'code' => 'ABL', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ABUJA', 'code' => 'ABV', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ADO EKITI', 'code' => 'ADO', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'AKURE', 'code' => 'AKE', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ASABA', 'code' => 'ASA', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'AUCHI', 'code' => 'AUC', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'BAUCHI', 'code' => 'BAU', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'BENIN', 'code' => 'BNI', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'BONNY', 'code' => 'BNY', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'CALABAR', 'code' => 'CAL', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'DAMATURU', 'code' => 'DAM', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'DUTSE', 'code' => 'DUT', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'EKET', 'code' => 'EKT', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'EKPOMA', 'code' => 'EKP', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ENUGU', 'code' => 'ENU', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'GOMBE', 'code' => 'GOM', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'GUSAU', 'code' => 'GUS', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'GWAGWALADA', 'code' => 'GWA', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'IBADAN', 'code' => 'IBA', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'IFE', 'code' => 'IFE', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'IJEBU-ODE', 'code' => 'IJB', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'JALINGO', 'code' => 'JAL', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'KEBBI', 'code' => 'KBB', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ILORIN', 'code' => 'ILR', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'JOS', 'code' => 'JOS', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'KADUNA', 'code' => 'KAD', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'KANO', 'code' => 'KAN', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'KATSINA', 'code' => 'KAS', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'LAFIA', 'code' => 'LFA', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'LOKOJA', 'code' => 'LKJ', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'LAGOS', 'code' => 'LOS', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'MAKURDI', 'code' => 'MDI', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'MAIDUGURI', 'code' => 'MAI', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'MINNA', 'code' => 'MNA', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'NNEWI', 'code' => 'NNI', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'NSUKKA', 'code' => 'NSK', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ONITSHA', 'code' => 'ONA', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'OWERRI', 'code' => 'ORI', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'OSHOGBO', 'code' => 'OSO', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'PORT HARCOURT', 'code' => 'PHC', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'WARRI', 'code' => 'QRW', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SAPELE', 'code' => 'SAE', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SOKOTO/GUSAU', 'code' => 'SKO', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'UMUAHIA', 'code' => 'UMH', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'UYO', 'code' => 'UYO', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'YENAGOA', 'code' => 'YEN', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'YOLA/JALINGO', 'code' => 'YOL', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ZARIA', 'code' => 'ZRI', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
}
