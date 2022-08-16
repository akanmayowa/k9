<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TarriffZonning extends Model
{
    public function departure_location()
    {
        return $this->belongsTo(TarriffLocation::class, 'departure_location_id', 'id');
    }

    public function destination_location()
    {
        return $this->belongsTo(TarriffLocation::class, ' departure_location_id', 'id');
    }

}
