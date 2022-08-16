<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class K9ReturnScan extends Model
{
    protected $table = 'NRLY.TAB_BILL_RETURN';
    protected $connection = 'K9_server';
    protected $primaryKey = 'BILLCODE';
    public $incrementing = false;




}
