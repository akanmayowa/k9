<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OverdueFlag extends Model
{
    protected $fillable = ['manifest_id', 'site_id', 'created_by', 'created_at',
    'updated_at'];
}
