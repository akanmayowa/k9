<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EcommerceTariff extends Model
{
    protected $table = 'ecommerce_tariffs';
    protected $fillable = ['type', 'weight_start', 'weight_end', 'zone_1_cost', 'zone_2_cost', 'zone_3_cost', 'zone_4_cost'];

}
