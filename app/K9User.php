<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class K9User extends Model
{
    protected $table = 'NRLY.TAB_EMPLOYEE_VIEW';
    protected $connection = 'K9_server';
    protected $primaryKey = 'EMPLOYEE_CODE';
    public $incrementing = false;



}
