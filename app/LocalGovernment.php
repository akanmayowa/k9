<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocalGovernment extends Model
{
    protected $fillable = [
     'name', 'state_id', 'created_by', 'updated_by'
    ];
}
