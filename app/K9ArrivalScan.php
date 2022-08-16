<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class K9ArrivalScan extends Model
{
    protected $table = 'NRLY.TAB_SCAN_COME';
    protected $connection = 'K9_server';
    protected $primaryKey = 'BILL_CODE';
    public $incrementing = false;

    public function employee()
    {
        return $this->belongsTo(k9Employee::class, 'SCAN_MAN_CODE', 'EMPLOYEE_CODE');
    }


    public function scan_site()
    {
        return $this->belongsTo(k9Site::class, 'SCAN_SITE_CODE', 'SITE_CODE');
    }

    public function next_site()
    {
        return $this->belongsTo(k9Site::class, 'PRE_OR_NEXT_STATION_CODE', 'SITE_CODE');
    }

    //These methods where added because of Site Arrival Overview test

    public function delivery_records()
    {
        return $this->hasMany(K9DeliveryScan::class, 'BILL_CODE', 'BILL_CODE');//->where('SCAN_SITE_CODE', $this->SCAN_SITE_CODE);
    }

    public function departure_records()
    {
        return $this->hasMany(K9DepartureScan::class, 'BILL_CODE', 'BILL_CODE');//->where('SCAN_SITE_CODE', $this->SCAN_SITE_CODE);
    }

    public function collection_records()
    {
        return $this->hasMany(K9CollectionScan::class, 'BILL_CODE', 'BILL_CODE');//->where('SCAN_SITE_CODE', $this->SCAN_SITE_CODE);
    }


    public function issue_parcel_records()
    {
        return $this->hasMany(K9IssueParcelScan::class, 'BILL_CODE', 'BILL_CODE');//->where('SCAN_SITE_CODE', $this->SCAN_SITE_CODE);
    }




}
