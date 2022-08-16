<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class K9DepartureScan extends Model
{
    protected $table = 'NRLY.TAB_SCAN_SEND';
    protected $connection = 'K9_server';
    protected $primaryKey = 'BILL_CODE';
    // protected $dates = ['SCAN_DATE']; // so regular where can be honoured but will k9 agree ?
    public $incrementing = false;

    public function employee()
    {
        return $this->belongsTo(K9Employee::class, 'SCAN_MAN_CODE', 'EMPLOYEE_CODE');
    }


    public function scan_site()
    {
        return $this->belongsTo(K9Site::class, 'SCAN_SITE_CODE', 'SITE_CODE');
    }

    public function next_site()
    {
        return $this->belongsTo(K9Site::class, 'PRE_OR_NEXT_STATION_CODE', 'SITE_CODE');
    }

    public function info()
    {
        return $this->belongsTo(K9Waybill::class, 'BILL_CODE', 'BILL_CODE');
    }


}
