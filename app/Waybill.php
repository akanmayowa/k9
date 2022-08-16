<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Waybill extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $dates = ['acknowledged_at', 'deleted_at', 'created_at', 'updated_at'];
    // protected $fillable = [
    //     'id',
    //     'manifest_id',
    //     'scan_site_id',
    //     'next_site_id',
    //     'status',
    //     'created_by',
    //     'updated_by',
    //     'acknwoledged_at', // rename
    //     'acknwoledged_by',
    //     'created_at',
    //     'updated_at',
    //     'shipment_type',
    //     ''
    // ];
    protected $guarded = [];
    //the below methods will not give you the right one ooo
    //Check the other parts u have used it. you must add manifest to it for it to be unique oo
    public function scan_site()
    {
        return $this->belongsTo(Site::class, 'scan_site_id', 'id');
    }

    public function next_site()
    {
        return $this->belongsTo(Site::class, 'next_site_id', 'id');
    }

    public function manifest()
    {
        return $this->belongsTo(Manifest::class, 'manifest_id', 'id');
    }


    public function created_by_user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function acknowledged_by_user()
    {
        return $this->belongsTo(User::class, 'acknwoledged_by', 'id'); ///rename
    }
}
