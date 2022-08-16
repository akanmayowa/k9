<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Api_user extends Model
{
    public $incrementing = false;
    protected $table = 'api_users';
    protected $fillable = ['id', 'name', 'api_token', 'is_active', 'access_type', 'created_by','updated_by','created_at','updated_at'];
}

