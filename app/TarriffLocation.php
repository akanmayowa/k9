<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TarriffLocation extends Model
{
    //use this method or garded way to allow you seeder insert many rows
    protected $fillable = ['code', 'name', 'created_at', 'updated_at'];



}
