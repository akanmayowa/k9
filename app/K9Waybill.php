<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class K9Waybill extends Model
{
    protected $table = 'NRLY.TAB_BILL';
    protected $connection = 'K9_server';
    protected $primaryKey = 'BILL_CODE';
    public $incrementing = false;




}
