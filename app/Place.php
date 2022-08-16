<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $fillable = ['name', 'state_id','created_by', 'updated_by'];

    public function state(){
        return $this->belongsTo(State::class, 'state_id', 'id');
    }
}
