<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class K9CollectionScan extends Model
{

    protected $table = 'NRLY.TAB_SIGN';
    protected $connection = 'K9_server';
    protected $primaryKey = 'BILL_CODE';
    public $incrementing = false;

    public function employee()
    {
        return $this->belongsTo(k9Employee::class, 'RECORD_MAN_CODE', 'EMPLOYEE_CODE');
    }


    public function scan_site()
    {
        return $this->belongsTo(k9Site::class, 'RECORD_SITE_CODE', 'SITE_CODE');
    }

    // public function next_site()
    // {
    //     return $this->belongsTo(k9Site::class, 'PRE_OR_NEXT_STATION_CODE', 'SITE_CODE');
    // }
}
