<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class K9IssueParcelScan extends Model
{
    protected $table = 'NRLY.TAB_PROBLEM';
    protected $connection = 'K9_server';
    protected $primaryKey = 'BILL_CODE';
    public $incrementing = false;




}
